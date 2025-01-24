<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductImage;
use App\Models\VariationAttributeValue;

class ProductVariationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($productId) {}
    /**
     * Show the form for creating a new resource.
     */
    public function create($productId)
    {
        $product = Product::findOrFail($productId);
        
        
        $attributes = $product->productAttributes()
            ->with('attributeValue')
            ->get()
            ->groupBy('attribute_id');

        return view('admin.variation.variation-add', compact('product', 'attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'sku' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'sale_price' => 'nullable|numeric|min:0|lt:price',
        'sale_start' => 'nullable|date',
        'sale_end' => 'nullable|date|after_or_equal:sale_start',
        'stock' => 'required|integer|min:0',
        'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'attributes' => 'required|array',
    ]);

    try {
        DB::beginTransaction();

        // Tạo biến thể sản phẩm
        $variation = ProductVariation::create([
            'product_id' => $validated['product_id'],
            'sku' => $validated['sku'],
            'price' => $validated['price'],
            'sale_price' => $validated['sale_price'] ?? null,
            'sale_start' => $validated['sale_start'] ?? null,
            'sale_end' => $validated['sale_end'] ?? null,
            'stock' => $validated['stock'],
        ]);

        // Lưu các giá trị thuộc tính liên kết với biến thể
        foreach ($validated['attributes'] as $attributeValueId) {
            VariationAttributeValue::create([
                'variation_id' => $variation->id,
                'attribute_value_id' => $attributeValueId,
            ]);
        }

        // Lưu ảnh sản phẩm
        $imagePath = $request->file('image')->store('variations');
        ProductImage::create([
            'product_id' => $validated['product_id'],
            'variation_id' => $variation->id,
            'url' => $imagePath,
            'is_main' => true,
        ]);

        DB::commit();

        return redirect()->route('product.variations', $validated['product_id'])->with('success', 'Variation created successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
