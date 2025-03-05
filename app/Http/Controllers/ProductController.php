<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProductRequest;

class ProductController extends Controller
{

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function listproduct()
    {
        return view('client.product.list-product');
    }

    public function productdetails()
    {
        return view('client.product.product-details');
    }

    public function index()
    {
        $products = Product::with('mainImage')->get();
        return view('admin.product.product-list', compact('products'));
    }


    public function create()
    {
        $categories = Category::all();
        $attributes = Attribute::with('values')->get();

        return view('admin.product.add-product', compact('categories', 'attributes'));
    }


    public function store(StoreProductRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // Create the product
                $product = Product::create($request->validated());


                // Handle main image upload

                // Handle variations
                if ($request->has('variations')) {
                    foreach ($request->variations as $variationData) {
                        $variation = new Variation();
                        $variation->product_id = $product->id; // Assign product ID
                        $variation->sku = $variationData['sku'];
                        $variation->price = $variationData['price'];
                        $variation->stock = $variationData['stock'];
                        $variation->save();

                        // Assign attribute values to the variation
                        if (isset($variationData['attribute_values'])) {
                            $variation->attributeValues()->sync($variationData['attribute_values']);
                        }
                    }
                }
                // Handle main image upload
                if ($request->hasFile('main_image')) {
                    $mainImage = new ProductImage();
                    $mainImage->variation_id = $variation->id; // Assuming you want to associate it with the last variation created
                    $mainImage->url = $request->file('main_image')->store('products');
                    $mainImage->is_main = true; // Set as main image
                    $mainImage->save();
                }
            });

            return redirect()->route('admin.product.product-list')
                ->with('success', 'Product and variations added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }


    public function showVariations($id)
    {
        $product = Product::with('productAttributes.attribute', 'productAttributes.attributeValue')->findOrFail($id);


        $attributes = $product->productAttributes->groupBy('attribute_id');


        $combinations = $this->generateCombinations($attributes);


        return view('admin.variation.variation-list-of-product', compact('product', 'combinations'));
    }

    private function generateCombinations($attributes)
    {
        $combinations = [[]];

        foreach ($attributes as $attributeValues) {
            $newCombinations = [];
            foreach ($combinations as $combination) {
                foreach ($attributeValues as $value) {
                    $newCombinations[] = array_merge($combination, [$value]);
                }
            }
            $combinations = $newCombinations;
        }

        return $combinations;
    }

    public function show(Product $product) {}


    public function edit(Product $product)
    {
        $categories = Category::all();
        $attributes = Attribute::with('values')->get(); // Lấy tất cả attributes và values
        $productAttributes = $product->productAttributes()
            ->with('attribute', 'attributeValues')
            ->get();
        return view('admin.product.edit-product', compact('product', 'categories', 'productAttributes', 'attributes'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'sale_start' => 'nullable|date',
            'sale_end' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'attributes' => 'nullable|array',
            'attributes.*.attribute_id' => 'nullable|exists:attributes,id',
            'attributes.*.value_ids' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            // Update product details
            $product = Product::findOrFail($id);
            $product->update([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'price' => $validated['price'],
                'sale_price' => $validated['sale_price'],
                'quantity' => $validated['quantity'],
                'category_id' => $validated['category_id'],
                'description' => $validated['description'],
                'sale_start' => $validated['sale_start'],
                'sale_end' => $validated['sale_end'],
                'status' => $validated['status'],
            ]);

            // Update main image if provided
            if ($request->hasFile('main_image')) {
                if ($product->main_image) {
                    Storage::delete($product->main_image);
                }
                $product->main_image = $request->file('main_image')->store('products');
                $product->save();
            }

            // Update additional images if provided
            if ($request->hasFile('additional_images')) {
                foreach ($product->additionalImages as $image) {
                    Storage::delete($image->url);
                    $image->delete();
                }

                foreach ($request->file('additional_images') as $additionalImage) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'url' => $additionalImage->store('products'),
                    ]);
                }
            }

            // Handle attributes
            if (isset($validated['attributes'])) {
                // Delete old attributes and values
                $product->productAttributes()->delete();

                // Add new attributes and values
                foreach ($validated['attributes'] as $attributeData) {
                    if (!empty($attributeData['attribute_id'])) {
                        $productAttribute = ProductAttribute::create([
                            'product_id' => $product->id,
                            'attribute_id' => $attributeData['attribute_id'],
                        ]);

                        if (isset($attributeData['value_ids'])) {
                            $productAttribute->attributeValue()->sync($attributeData['value_ids']);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.product.product-list')->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);


        $hasVariations = $product->variations()->exists();

        if ($hasVariations) {

            return redirect()->back()->with('error', 'Sản phẩm có biến thể. Không thể xóa trực tiếp! Cần Xóa Từ Các Biến Thể Trong Sản Phẩm');
        }

        try {
            $product->delete();
            return redirect()->back()->with('success', 'Sản phẩm đã được xóa thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
