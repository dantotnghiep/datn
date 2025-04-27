<?php

namespace App\Models;

class Wishlist extends BaseModel
{
    protected $fillable = ['user_id', 'product_variation_id'];

    public static function rules($id = null)
    {
        return [
            'user_id' => 'required|exists:users,id',
            'product_variation_id' => 'required|exists:product_variations,id'
        ];
    }

    public static function getFields()
    {
        return [
            'user_id' => [
                'label' => 'Người dùng',
                'type' => 'select',
                'options' => User::pluck('name', 'id')->toArray(),
                'filterable' => true,
                'filter_options' => User::pluck('name', 'id')->toArray(),
                'sortable' => true
            ],
            'product_variation_id' => [
                'label' => 'Sản phẩm',
                'type' => 'select',
                'options' => ProductVariation::with('product')->get()->map(function ($variation) {
                    return [
                        'id' => $variation->id,
                        'name' => $variation->product->name . ' - ' . $variation->name
                    ];
                })->pluck('name', 'id')->toArray(),
                'filterable' => true,
                'sortable' => true
            ],
            'created_at' => [
                'label' => 'Ngày thêm',
                'type' => 'datetime',
                'sortable' => true
            ]
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productVariation()
    {
        return $this->belongsTo(ProductVariation::class);
    }
} 