<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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


        Log::info('Categories Loaded:', $categories->toArray());
        Log::info('Attributes Loaded:', $attributes->toArray());

        return view('admin.product.add-product', compact('categories', 'attributes'));
    }


    public function store(Request $request)
    {
        try {

            DB::beginTransaction();


            Log::info('Request Data:', $request->all());


            if ($request->has('attributes')) {
                Log::info('Attributes from Request:', $request->input('attributes'));
            }


            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:products',
                'price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'quantity' => 'required|integer|min:0',
                'category_id' => 'required|integer|exists:categories,id',
                'main_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'attributes' => 'required|array|min:1',
                'attributes.*.attribute_id' => 'required|integer|exists:attributes,id',
                'attributes.*.value_ids' => 'required|array|min:1',
                'attributes.*.value_ids.*' => 'integer|exists:attribute_values,id',
                'description' => 'nullable|string|max:2000',
                'sale_start' => 'nullable|date|after: . now()->addHour()->toDateTimeString(),|before:sale_end',
                'sale_end' => 'nullable|date|after:sale_start',
                'status' => 'required|in:active,inactive',
            ]);


            $product = Product::create([
                'name' => $validatedData['name'],
                'slug' => $validatedData['slug'],
                'price' => $validatedData['price'],
                'sale_price' => $validatedData['sale_price'] ?? null,
                'quantity' => $validatedData['quantity'],
                'category_id' => $validatedData['category_id'],
                'description' => $validatedData['description'],
                'sale_start' => $validatedData['sale_start'],
                'sale_end' => $validatedData['sale_end'],
                'status' => $validatedData['status'],

            ]);

            Log::info('Product Created:', $product->toArray());


            if ($request->hasFile('main_image')) {
                $mainImagePath = $request->file('main_image')->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'variation_id' => null,
                    'url' => $mainImagePath,
                    'is_main' => true,
                ]);
                Log::info('Main Image Saved:', ['url' => $mainImagePath]);
            }


            if ($request->hasFile('additional_images')) {
                foreach ($request->file('additional_images') as $image) {
                    $imagePath = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'variation_id' => null,
                        'url' => $imagePath,
                        'is_main' => false,
                    ]);
                    Log::info('Additional Image Saved:', ['url' => $imagePath]);
                }
            }


            if ($request->has('attributes')) {
                foreach ($request->input('attributes') as $attribute) {
                    Log::info('Processing Attribute:', ['attribute_id' => $attribute['attribute_id']]);

                    foreach ($attribute['value_ids'] as $valueId) {
                        $data = [
                            'product_id' => $product->id,
                            'attribute_id' => $attribute['attribute_id'],
                            'attribute_value_id' => $valueId,
                        ];


                        Log::info('Inserting Product Attribute:', $data);

                        ProductAttribute::create($data);
                    }
                }
            }


            DB::commit();
            Log::info('Transaction Committed Successfully.');

            return redirect()->route('admin.product.product-list')->with('success', 'Sản phẩm đã được thêm thành công!');
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error('Error saving product: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
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
        return view('admin.product.edit-product', compact('product','categories', 'productAttributes', 'attributes'));
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
