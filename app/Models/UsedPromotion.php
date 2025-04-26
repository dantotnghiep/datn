<?php

namespace App\Models;

class UsedPromotion extends BaseModel
{
    protected $fillable = ['promotion_id', 'order_id', 'discount_amount'];

    protected $casts = [
        'discount_amount' => 'decimal:2'
    ];

    public static function rules($id = null)
    {
        return [
            'promotion_id' => 'required|exists:promotions,id',
            'order_id' => 'required|exists:orders,id',
            'discount_amount' => 'required|numeric|min:0'
        ];
    }

    public static function getFields()
    {
        return [
            'promotion_id' => [
                'label' => 'Khuyến mãi',
                'type' => 'select',
                'options' => Promotion::pluck('name', 'id')->toArray(),
                'filterable' => true,
                'filter_options' => Promotion::pluck('name', 'id')->toArray(),
                'sortable' => true
            ],
            'order_id' => [
                'label' => 'Đơn hàng',
                'type' => 'select',
                'options' => Order::pluck('order_number', 'id')->toArray(),
                'filterable' => true,
                'filter_options' => Order::pluck('order_number', 'id')->toArray(),
                'sortable' => true
            ],
            'discount_amount' => [
                'label' => 'Số tiền giảm',
                'type' => 'number',
                'step' => '0.01',
                'sortable' => true
            ]
        ];
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
} 