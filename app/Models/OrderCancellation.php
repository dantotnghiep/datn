<?php

namespace App\Models;

class OrderCancellation extends BaseModel
{
    protected $fillable = ['order_id', 'reason', 'notes', 'user_id'];

    public static function rules($id = null)
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'user_id' => 'required|exists:users,id'
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
            'reason' => [
                'label' => 'Lý do hủy',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'notes' => [
                'label' => 'Ghi chú',
                'type' => 'textarea',
                'searchable' => true,
                'sortable' => false
            ],
            'user_id' => [
                'label' => 'Người thực hiện',
                'type' => 'select',
                'options' => User::pluck('name', 'id')->toArray(),
                'filterable' => true,
                'filter_options' => User::pluck('name', 'id')->toArray(),
                'sortable' => true
            ],
            'created_at' => [
                'label' => 'Thời gian hủy',
                'type' => 'datetime',
                'sortable' => true
            ]
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 