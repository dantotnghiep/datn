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
            // Create the product
            $product = $this->model::create($validated);

            // Handle product images
            if ($request->hasFile('images')) {
                $images = $request->file('images');

                // Validate that all files are valid images
                foreach ($images as $image) {
                    if (!$image->isValid() || !in_array($image->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                        throw new \Exception('Invalid image file uploaded.');
                    }
                }

                foreach ($images as $index => $image) {
                    $imagePath = $image->store('products', 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'is_primary' => $index === 0, // First image is primary
                        'order' => $index
                    ]);
                }
            }

            // Handle product variations
            if ($request->has('variations')) {
                foreach ($request->variations as $variation) {
                    // Create the variation
                    $newVariation = ProductVariation::create([
                        'product_id' => $product->id,
                        'sku' => $variation['sku'],
                        'name' => $variation['name'],
                        'price' => $variation['price'],
                        'sale_price' => $variation['sale_price'] ?? null,
                        'stock' => $variation['stock'] ?? 0
                    ]);

                    // Attach attribute values to the variation
                    if (isset($variation['attribute_values']) && is_array($variation['attribute_values'])) {
                        $newVariation->attributeValues()->attach($variation['attribute_values']);
                    }
                }
            }

            DB::commit();

            return redirect()->route($this->route . '.index')
                ->with('success', 'Product created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

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
            // Update the product
            $item->update($validated);

            // Handle product images
            if ($request->hasFile('images')) {
                $images = $request->file('images');

                // Validate that all files are valid images
                foreach ($images as $image) {
                    if (!$image->isValid() || !in_array($image->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                        throw new \Exception('Invalid image file uploaded.');
                    }
                }

                $maxOrder = ProductImage::where('product_id', $id)->max('order') ?? 0;

                foreach ($images as $index => $image) {
                    $imagePath = $image->store('products', 'public');

                    ProductImage::create([
                        'product_id' => $id,
                        'image_path' => $imagePath,
                        'is_primary' => false, // Never make new images primary when updating
                        'order' => $maxOrder + $index + 1
                    ]);
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

            // Handle variations (update existing and create new)
            if ($request->has('variations')) {
                foreach ($request->variations as $variationData) {
                    if (isset($variationData['id'])) {
                        // Update existing variation
                        $variation = ProductVariation::findOrFail($variationData['id']);
                        $variation->update([
                            'sku' => $variationData['sku'],
                            'name' => $variationData['name'],
                            'price' => $variationData['price'],
                            'sale_price' => $variationData['sale_price'] ?? null,
                            'stock' => $variationData['stock'] ?? 0
                        ]);

                        // Update attribute values
                        if (isset($variationData['attribute_values']) && is_array($variationData['attribute_values'])) {
                            $variation->attributeValues()->sync($variationData['attribute_values']);
                        }
                    } else {
                        // Create new variation
                        $newVariation = ProductVariation::create([
                            'product_id' => $id,
                            'sku' => $variationData['sku'],
                            'name' => $variationData['name'],
                            'price' => $variationData['price'],
                            'sale_price' => $variationData['sale_price'] ?? null,
                            'stock' => $variationData['stock'] ?? 0
                        ]);

                        // Attach attribute values to the variation
                        if (isset($variationData['attribute_values']) && is_array($variationData['attribute_values'])) {
                            $newVariation->attributeValues()->attach($variationData['attribute_values']);
                        }
                    }
                }
            }

            // Handle removing variations
            if ($request->has('remove_variations')) {
                foreach ($request->remove_variations as $variationId) {
                    ProductVariation::destroy($variationId);
                }
            }

            DB::commit();

            return redirect()->route($this->route . '.index')
                ->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

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
}
