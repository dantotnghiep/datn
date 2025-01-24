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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product) {}
}
