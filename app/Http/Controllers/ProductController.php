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
use App\Models\AttributeValue;
use App\Models\Product_image;

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
    public function show($id)
    {
        $product = Product::with([
            'category',
            'images',
            'variations.attributeValues.attribute',
        ])->findOrFail($id);
    
        // Lấy ra tất cả value của từng attribute
        $attributeValues = $product->variations->flatMap(function ($variation) {
            return $variation->attributeValues;
        });
    
        $colorValues = $attributeValues
            ->where('attribute_id', 2)
            ->unique('value')
            ->values();
    
        $sizeValues = $attributeValues
            ->where('attribute_id', 1)
            ->unique('value')
            ->values();
    
        return view('client.product.product-details', compact('product', 'colorValues', 'sizeValues'));
    }
    public function index()
    {
        $products = Product::with(['variations', 'images', 'category'])->orderBy('created_at', 'desc')->get();
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
                // Tạo sản phẩm
                $product = Product::create($request->validated());
                // Xử lý upload hình ảnh chính
                if ($request->hasFile('main_image')) {
                    $mainImage = new ProductImage();
                    $mainImage->product_id = $product->id; // Liên kết với sản phẩm
                    $mainImage->url = $request->file('main_image')->store('products');
                    $mainImage->is_main = true; // Đánh dấu là hình chính
                    $mainImage->save();
                }

                // Xử lý ảnh phụ
                if ($request->hasFile('additional_images')) {
                    foreach ($request->file('additional_images') as $image) {
                        $additionalImage = new ProductImage();
                        $additionalImage->product_id = $product->id;
                        $additionalImage->url = $image->store('products');
                        $additionalImage->is_main = false; // Đánh dấu là hình phụ
                        $additionalImage->save();
                    }
                }

                // Xử lý biến thể
                if ($request->has('variations')) {
                    foreach ($request->variations as $variationData) {
                        $variation = Variation::create([
                            'product_id' => $product->id,
                            'sku' => $variationData['sku'],
                            'price' => $variationData['price'],
                            'stock' => $variationData['stock'],
                            'sale_price' => !empty($variationData['sale_price']) ? $variationData['sale_price'] : null,
                            'sale_start' => !empty($variationData['sale_start']) ? date('Y-m-d H:i:s', strtotime($variationData['sale_start'])) : null,
                            'sale_end' => !empty($variationData['sale_end']) ? date('Y-m-d H:i:s', strtotime($variationData['sale_end'])) : null,
                        ]);
                        if (isset($variationData['attribute_values']) && is_array($variationData['attribute_values'])) {
                            $validIds = AttributeValue::whereIn('id', $variationData['attribute_values'])->pluck('id')->toArray();
                            foreach ($variationData['attribute_values'] as $attributeValueId) {
                                if (in_array($attributeValueId, $validIds)) {
                                    try {
                                        $variation->attributeValues()->attach($attributeValueId);
                                    } catch (\Exception $e) {
                                        Log::error('Error attaching attribute value:', ['error' => $e->getMessage()]);
                                    }
                                }
                            }
                        }
                    }
                }
            });

            return redirect()->route('admin.product.product-list')
                ->with('success', 'Sản phẩm và biến thể đã được thêm thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())
                ->withInput();
        }
    }


    public function showVariations($id)
    {
        $product = Product::with(['variations.attributeValues'])->findOrFail($id);
        return view('admin.variation.variation-list-of-product', compact('product'));
    }


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
            'category_id' => 'required|exists:categphpories,id',
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
