<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::with([
            'category', 
            'images', 
            'variations',
            'variations.attributeValues',
            'variations.attributeValues.attribute'
        ])->where('slug', $slug)->firstOrFail();

        // Lấy danh sách các thuộc tính của sản phẩm
        $attributes = collect();
        foreach ($product->variations as $variation) {
            foreach ($variation->attributeValues as $value) {
                $attribute = $value->attribute;
                if (!$attributes->contains('id', $attribute->id)) {
                    $attributes->push([
                        'id' => $attribute->id,
                        'name' => $attribute->name,
                        'values' => collect()
                    ]);
                }
                
                $attr = $attributes->firstWhere('id', $attribute->id);
                if (!$attr['values']->contains('id', $value->id)) {
                    $attr['values']->push([
                        'id' => $value->id,
                        'value' => $value->value
                    ]);
                }
            }
        }
            
        return view('client.product.detail', compact('product', 'attributes'));
    }
} 