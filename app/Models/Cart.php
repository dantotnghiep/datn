<?php

namespace App\Models;

class Cart extends BaseModel
{
    protected $fillable = ['user_id', 'product_variation_id', 'quantity', 'price', 'total'];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public static function rules($id = null)
    {
        return [
            'user_id' => 'required|exists:users,id',
            'product_variation_id' => 'nullable|exists:product_variations,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0'
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
            'quantity' => [
                'label' => 'Số lượng',
                'type' => 'number',
                'sortable' => true
            ],
            'price' => [
                'label' => 'Đơn giá',
                'type' => 'number',
                'step' => '0.01',
                'sortable' => true
            ],
            'total' => [
                'label' => 'Tổng tiền',
                'type' => 'number',
                'step' => '0.01',
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