<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Http\Controllers\Admin\Traits\HasUploadImage;

class ProductController extends BaseController
{
    use HasUploadImage;

    public function __construct()
    {
        $this->model = Product::class;
        $this->viewPath = 'admin.components.product';
        $this->route = 'admin.products';
        parent::__construct();
    }

    public function create()
    {
        $fields = $this->model::getFields();
        $attributes = Attribute::with('values')->get();
        $attributeValues = AttributeValue::all();

        // Debug for testing
        if ($attributes->isEmpty()) {
            dump('No attributes found. Please check your database.');
        } else {
            foreach ($attributes as $attr) {
                if ($attr->values->isEmpty()) {
                    // dump('No values found for attribute: ' . $attr->name);
                }
            }
        }

        return view($this->viewPath . '.form', [
            'fields' => $fields,
            'route' => $this->route,
            'attributes' => $attributes,
            'attributeValues' => $attributeValues
        ]);
    }

    public function store(Request $request)
    {
        // Validate basic product data
        $validated = $request->validate($this->model::rules());

        // Begin transaction to ensure all related operations succeed or fail together
        DB::beginTransaction();

        try {
            // Log for debugging
            Log::info('Creating product data', ['has_files' => $request->hasFile('images')]);

            // Create the product
            $product = $this->model::create($validated);

            // Handle product images
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                Log::info('Processing ' . count($images) . ' uploaded image files');

                // Validate that all files are valid images
                foreach ($images as $index => $image) {
                    if (!$image->isValid() || !in_array($image->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                        throw new \Exception('Invalid image file uploaded at index ' . $index . ': ' . $image->getClientOriginalName());
                    }
                }

                foreach ($images as $index => $image) {
                    // Store image directly without going through temporary storage
                    $imagePath = $image->store('products', 'public');
                    Log::info('Stored image directly at: ' . $imagePath);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'is_primary' => $index === 0, // First image is primary
                        'order' => $index
                    ]);
                }
            } elseif ($request->has('images')) {
                // This block is for the previous approach, can be kept for backward compatibility
                // Handle image paths from dropzone temp uploads
                $imagePaths = $request->input('images');
                Log::info('Alternative approach - Processing image paths', ['count' => is_array($imagePaths) ? count($imagePaths) : 0]);

                if (is_array($imagePaths) && !empty($imagePaths)) {
                    foreach ($imagePaths as $index => $path) {
                        // Check if the path exists
                        if (!Storage::disk('public')->exists($path)) {
                            Log::warning('Image path does not exist: ' . $path);
                            continue; // Skip if file doesn't exist
                        }

                        // Move from temp folder to permanent location if it's in temp
                        if (Str::startsWith($path, 'temp/')) {
                            $newPath = str_replace('temp/', '', $path);
                            try {
                                Storage::disk('public')->move($path, $newPath);
                                Log::info('Moved image from ' . $path . ' to ' . $newPath);
                                $path = $newPath;
                            } catch (\Exception $e) {
                                Log::error('Failed to move image: ' . $e->getMessage());
                                throw new \Exception('Failed to move image from temporary location: ' . $e->getMessage());
                            }
                        }

                        $productImage = ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $path,
                            'is_primary' => $index === 0, // First image is primary
                            'order' => $index
                        ]);
                        Log::info('Created product image record: ' . $productImage->id);
                    }
                }
            }

            // Handle product variations
            if ($request->has('variants')) {
                $variantsData = json_decode($request->variants, true);

                if (!empty($variantsData)) {
                    // Generate all possible combinations of attribute values
                    $combinations = $this->generateVariantCombinations($variantsData);

                    // Create a variation for each combination
                    foreach ($combinations as $index => $combination) {
                        // Generate a name and SKU based on the combination
                        $variationName = $product->name;
                        $skuSuffix = '';
                        $attributeValueIds = [];

                        foreach ($combination as $attrValue) {
                            $variationName .= ' - ' . $attrValue['value'];
                            $skuSuffix .= '-' . substr(strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $attrValue['value'])), 0, 3);
                            $attributeValueIds[] = $attrValue['id'];
                        }

                        // Ensure SKU is unique
                        $baseSku = $product->sku . $skuSuffix;
                        $sku = $baseSku;
                        $counter = 1;

                        while (ProductVariation::where('sku', $sku)->exists()) {
                            $sku = $baseSku . '-' . $counter;
                            $counter++;
                        }

                        // Create the variation
                        $newVariation = ProductVariation::create([
                            'product_id' => $product->id,
                            'sku' => $sku,
                            'name' => $variationName,
                            'price' => 0, // Default price, will need to be updated later
                            'stock' => 0   // Default stock, will need to be updated later
                        ]);

                        // Attach attribute values to the variation
                        if (!empty($attributeValueIds)) {
                            $newVariation->attributeValues()->attach($attributeValueIds);
                        }
                    }
                }
            }

            DB::commit();

            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product created successfully',
                    'redirect' => route('admin.products.index'),
                    'product' => $product
                ]);
            }

            return redirect()->route($this->route . '.index')
                ->with('success', 'Product created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating product: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating product: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error creating product: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $item = $this->model::with(['images', 'variations.attributeValues'])->findOrFail($id);
        $fields = $this->model::getFields();
        $attributes = Attribute::with('values')->get();

        return view($this->viewPath . '.form', [
            'item' => $item,
            'fields' => $fields,
            'route' => $this->route,
            'attributes' => $attributes
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = $this->model::findOrFail($id);
        $validated = $request->validate($this->model::rules($id));

        // Begin transaction
        DB::beginTransaction();

        try {
            Log::info('Updating product ID: ' . $id, ['has_files' => $request->hasFile('images')]);

            // Update the product
            $item->update($validated);

            // Handle product images
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                Log::info('Processing ' . count($images) . ' uploaded image files for update');

                // Validate that all files are valid images
                foreach ($images as $index => $image) {
                    if (!$image->isValid() || !in_array($image->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                        throw new \Exception('Invalid image file uploaded at index ' . $index . ': ' . $image->getClientOriginalName());
                    }
                }

                $maxOrder = ProductImage::where('product_id', $id)->max('order') ?? 0;

                foreach ($images as $index => $image) {
                    // Store image directly
                    $imagePath = $image->store('products', 'public');
                    Log::info('Stored updated image at: ' . $imagePath);

                    ProductImage::create([
                        'product_id' => $id,
                        'image_path' => $imagePath,
                        'is_primary' => false, // Never make new images primary when updating
                        'order' => $maxOrder + $index + 1
                    ]);
                }
            } elseif ($request->has('images')) {
                // Legacy approach - handle image paths from dropzone temp uploads
                $imagePaths = $request->input('images');
                Log::info('Alternative update approach - Processing image paths', ['count' => is_array($imagePaths) ? count($imagePaths) : 0]);

                if (is_array($imagePaths) && !empty($imagePaths)) {
                    $maxOrder = ProductImage::where('product_id', $id)->max('order') ?? 0;

                    foreach ($imagePaths as $index => $path) {
                        // Check if the path exists
                        if (!Storage::disk('public')->exists($path)) {
                            Log::warning('Image path does not exist: ' . $path);
                            continue; // Skip if file doesn't exist
                        }

                        // Move from temp folder to permanent location if it's in temp
                        if (Str::startsWith($path, 'temp/')) {
                            $newPath = str_replace('temp/', '', $path);
                            try {
                                Storage::disk('public')->move($path, $newPath);
                                Log::info('Moved image from ' . $path . ' to ' . $newPath);
                                $path = $newPath;
                            } catch (\Exception $e) {
                                Log::error('Failed to move image during update: ' . $e->getMessage());
                                throw new \Exception('Failed to move image from temporary location: ' . $e->getMessage());
                            }
                        }

                        $productImage = ProductImage::create([
                            'product_id' => $id,
                            'image_path' => $path,
                            'is_primary' => false, // Never make new images primary when updating
                            'order' => $maxOrder + $index + 1
                        ]);
                        Log::info('Created product image record during update: ' . $productImage->id);
                    }
                }
            }

            // Handle existing images updates
            if ($request->has('existing_images')) {
                foreach ($request->existing_images as $imageId => $imageData) {
                    $productImage = ProductImage::findOrFail($imageId);

                    // Update primary status
                    if (isset($imageData['is_primary']) && $imageData['is_primary']) {
                        // Reset all other images to non-primary
                        ProductImage::where('product_id', $id)
                            ->where('id', '!=', $imageId)
                            ->update(['is_primary' => false]);

                        $productImage->is_primary = true;
                    }

                    // Update order
                    if (isset($imageData['order'])) {
                        $productImage->order = $imageData['order'];
                    }

                    $productImage->save();
                }
            }

            // Handle removing images
            if ($request->has('remove_images')) {
                foreach ($request->remove_images as $imageId) {
                    $image = ProductImage::findOrFail($imageId);

                    // Delete the file
                    if ($image->image_path) {
                        Storage::disk('public')->delete($image->image_path);
                    }

                    $image->delete();
                }

                // Make sure there's a primary image
                $primaryExists = ProductImage::where('product_id', $id)
                    ->where('is_primary', true)
                    ->exists();

                if (!$primaryExists) {
                    $firstImage = ProductImage::where('product_id', $id)->first();
                    if ($firstImage) {
                        $firstImage->update(['is_primary' => true]);
                    }
                }
            }

            // Handle product variations
            if ($request->has('variants')) {
                $variantsData = json_decode($request->variants, true);

                if (!empty($variantsData)) {
                    // First, soft delete all existing variations
                    ProductVariation::where('product_id', $id)->delete();

                    // Generate all possible combinations of attribute values
                    $combinations = $this->generateVariantCombinations($variantsData);

                    // Create a variation for each combination
                    foreach ($combinations as $index => $combination) {
                        // Generate a name and SKU based on the combination
                        $variationName = $item->name;
                        $skuSuffix = '';
                        $attributeValueIds = [];

                        foreach ($combination as $attrValue) {
                            $variationName .= ' - ' . $attrValue['value'];
                            $skuSuffix .= '-' . substr(strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $attrValue['value'])), 0, 3);
                            $attributeValueIds[] = $attrValue['id'];
                        }

                        // Ensure SKU is unique
                        $baseSku = $item->sku . $skuSuffix;
                        $sku = $baseSku;
                        $counter = 1;

                        while (ProductVariation::where('sku', $sku)->exists()) {
                            $sku = $baseSku . '-' . $counter;
                            $counter++;
                        }

                        // Create the variation
                        $newVariation = ProductVariation::create([
                            'product_id' => $id,
                            'sku' => $sku,
                            'name' => $variationName,
                            'price' => 0, // Default price, will need to be updated later
                            'stock' => 0   // Default stock, will need to be updated later
                        ]);

                        // Attach attribute values to the variation
                        if (!empty($attributeValueIds)) {
                            $newVariation->attributeValues()->attach($attributeValueIds);
                        }
                    }
                }
            }

            DB::commit();

            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully',
                    'redirect' => route('admin.products.index'),
                    'product' => $item->fresh()
                ]);
            }

            return redirect()->route($this->route . '.index')
                ->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating product: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating product: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error updating product: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $item = $this->model::findOrFail($id);
        $this->handleImageDelete($item, 'image');
        $item->delete();

        return redirect()->route($this->route . '.index')
            ->with('success', 'Product moved to trash successfully!');
    }

    /**
     * Generate all possible combinations of attribute values for product variations
     *
     * @param array $variantsData An array of variant options with their attribute values
     * @return array All possible combinations of attribute values
     */
    protected function generateVariantCombinations(array $variantsData)
    {
        // Extract attribute values for each option
        $attributeValueSets = [];

        foreach ($variantsData as $option) {
            if (isset($option['values']) && !empty($option['values'])) {
                $attributeValueSets[] = $option['values'];
            }
        }

        // If no attribute values, return empty array
        if (empty($attributeValueSets)) {
            return [];
        }

        // Helper function to generate Cartesian product (all combinations)
        $combinations = [[]];

        foreach ($attributeValueSets as $attributeValues) {
            $result = [];

            foreach ($combinations as $combination) {
                foreach ($attributeValues as $attributeValue) {
                    $result[] = array_merge($combination, [$attributeValue]);
                }
            }

            $combinations = $result;
        }

        return $combinations;
    }
}
