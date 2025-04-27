<?php

namespace App\Models;

class OrderItem extends BaseModel
{
    protected $fillable = ['order_id', 'product_variation_id', 'quantity', 'price', 'total'];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public static function rules($id = null)
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'product_variation_id' => 'required|exists:product_variations,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0'
        ];
    }

    public static function getFields()
    {
        return [
            'order_id' => [
                'label' => 'Đơn hàng',
                'type' => 'select',
                'options' => Order::pluck('order_number', 'id')->toArray(),
                'filterable' => true,
                'filter_options' => Order::pluck('order_number', 'id')->toArray(),
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

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function productVariation()
    {
        return $this->belongsTo(ProductVariation::class);
    }
} 