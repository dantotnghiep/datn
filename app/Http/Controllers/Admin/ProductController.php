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

    protected function generateSKU($name)
    {
        // Remove special characters and convert to uppercase
        $base = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $name));

        // Take first 6 characters
        $base = substr($base, 0, 6);

        // Add random number
        $random = mt_rand(1000, 9999);

        $sku = $base . $random;

        // Check if SKU exists
        while (Product::where('sku', $sku)->exists()) {
            $random = mt_rand(1000, 9999);
            $sku = $base . $random;
        }

        return $sku;
    }

    public function store(Request $request)
    {
        // Log request data for debugging
        Log::info('Product store request', [
            'has_variants' => $request->has('variants'),
            'variants_data' => $request->variants,
            'has_files' => $request->hasFile('images'),
            'files_count' => $request->hasFile('images') ? count($request->file('images')) : 0
        ]);

        $validated = $request->validate($this->model::rules());

        // Generate SKU and slug
        $validated['sku'] = $this->generateSKU($validated['name']);
        $validated['slug'] = Str::slug($validated['name']);

        // Begin transaction
        DB::beginTransaction();

        try {
            // Create the product
            $product = $this->model::create($validated);

            // Handle product images
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                Log::info('Processing ' . count($images) . ' uploaded image files');

                foreach ($images as $index => $image) {
                    // Validate image
                    if (!$image->isValid()) {
                        throw new \Exception('Invalid image file at index ' . $index);
                    }

                    if (!in_array($image->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                        throw new \Exception('Invalid image type at index ' . $index . ': ' . $image->getMimeType());
                    }

                    // Store image
                    try {
                        $imagePath = $image->store('products', 'public');
                        Log::info('Stored image at: ' . $imagePath);

                        // Create product image record
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $imagePath,
                            'is_primary' => $index === 0,
                            'order' => $index
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to store image: ' . $e->getMessage());
                        throw new \Exception('Failed to store image: ' . $e->getMessage());
                    }
                }
            }

            // Handle product variations
            if ($request->has('variants') && !empty($request->variants)) {
                $variantsData = json_decode($request->variants, true);

                if (!empty($variantsData) && is_array($variantsData)) {
                    $combinations = $this->generateVariantCombinations($variantsData);

                    foreach ($combinations as $index => $combination) {
                        $variationName = $product->name;
                        $skuSuffix = '';
                        $attributeValueIds = [];

                        foreach ($combination as $attrValue) {
                            $variationName .= ' - ' . $attrValue['value'];
                            $skuSuffix .= '-' . substr(strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $attrValue['value'])), 0, 3);
                            $attributeValueIds[] = $attrValue['id'];
                        }

                        // Generate variation SKU
                        $variationSku = $product->sku . $skuSuffix;
                        $counter = 1;
                        while (ProductVariation::where('sku', $variationSku)->exists()) {
                            $variationSku = $product->sku . $skuSuffix . '-' . $counter;
                            $counter++;
                        }

                        // Create variation
                        $newVariation = ProductVariation::create([
                            'product_id' => $product->id,
                            'sku' => $variationSku,
                            'name' => $variationName,
                            'price' => 0,
                            'stock' => 0
                        ]);

                        if (!empty($attributeValueIds)) {
                            $newVariation->attributeValues()->attach($attributeValueIds);
                        }
                    }
                }
            }

            DB::commit();

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
            Log::error('Error creating product: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

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
        $attributeValues = AttributeValue::all();

        return view($this->viewPath . '.form', [
            'item' => $item,
            'fields' => $fields,
            'route' => $this->route,
            'attributes' => $attributes,
            'attributeValues' => $attributeValues
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = $this->model::findOrFail($id);
        $validated = $request->validate($this->model::rules($id));

        // Begin transaction
        DB::beginTransaction();

        try {
            // Update the product
            $item->update($validated);

            // Handle product images
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                Log::info('Processing ' . count($images) . ' uploaded image files');

                foreach ($images as $index => $image) {
                    // Validate image
                    if (!$image->isValid()) {
                        throw new \Exception('Invalid image file at index ' . $index);
                    }

                    if (!in_array($image->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                        throw new \Exception('Invalid image type at index ' . $index . ': ' . $image->getMimeType());
                    }

                    // Store image
                    try {
                        $imagePath = $image->store('products', 'public');
                        Log::info('Stored image at: ' . $imagePath);

                        // Create product image record
                        ProductImage::create([
                            'product_id' => $id,
                            'image_path' => $imagePath,
                            'is_primary' => false,
                            'order' => ProductImage::where('product_id', $id)->max('order') + 1
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to store image: ' . $e->getMessage());
                        throw new \Exception('Failed to store image: ' . $e->getMessage());
                    }
                }
            }

            // Handle existing images updates
            if ($request->has('existing_images')) {
                foreach ($request->existing_images as $imageId => $imageData) {
                    $productImage = ProductImage::findOrFail($imageId);

                    if (isset($imageData['is_primary']) && $imageData['is_primary']) {
                        ProductImage::where('product_id', $id)
                            ->where('id', '!=', $imageId)
                            ->update(['is_primary' => false]);

                        $productImage->is_primary = true;
                    }

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
                    if ($image->image_path) {
                        Storage::disk('public')->delete($image->image_path);
                    }
                    $image->delete();
                }

                // Ensure there's a primary image
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
            if ($request->has('variants') && !empty($request->variants)) {
                $variantsData = json_decode($request->variants, true);

                if (!empty($variantsData) && is_array($variantsData)) {
                    // First, soft delete all existing variations
                    ProductVariation::where('product_id', $id)->delete();

                    // Generate all possible combinations
                    $combinations = $this->generateVariantCombinations($variantsData);

                    foreach ($combinations as $index => $combination) {
                        $variationName = $item->name;
                        $skuSuffix = '';
                        $attributeValueIds = [];

                        foreach ($combination as $attrValue) {
                            $variationName .= ' - ' . $attrValue['value'];
                            $skuSuffix .= '-' . substr(strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $attrValue['value'])), 0, 3);
                            $attributeValueIds[] = $attrValue['id'];
                        }

                        // Generate variation SKU
                        $variationSku = $item->sku . $skuSuffix;
                        $counter = 1;
                        while (ProductVariation::where('sku', $variationSku)->exists()) {
                            $variationSku = $item->sku . $skuSuffix . '-' . $counter;
                            $counter++;
                        }

                        // Create variation
                        $newVariation = ProductVariation::create([
                            'product_id' => $id,
                            'sku' => $variationSku,
                            'name' => $variationName,
                            'price' => 0,
                            'stock' => 0
                        ]);

                        if (!empty($attributeValueIds)) {
                            $newVariation->attributeValues()->attach($attributeValueIds);
                        }
                    }
                }
            }

            DB::commit();

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
            Log::error('Error updating product: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

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
        // Log input data for debugging
        Log::info('Generating variant combinations', [
            'input_data' => $variantsData
        ]);

        // Extract attribute values for each option
        $attributeValueSets = [];

        foreach ($variantsData as $option) {
            if (isset($option['values']) && !empty($option['values'])) {
                $attributeValueSets[] = $option['values'];
                Log::debug('Added attribute values', [
                    'option' => $option['option'],
                    'attribute_id' => $option['attribute_id'],
                    'values_count' => count($option['values'])
                ]);
            }
        }

        // If no attribute values, return empty array
        if (empty($attributeValueSets)) {
            Log::warning('No attribute value sets found');
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

        Log::info('Generated combinations', [
            'combinations_count' => count($combinations)
        ]);

        return $combinations;
    }
}
