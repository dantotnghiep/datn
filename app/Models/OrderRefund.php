<?php

namespace App\Models;

class OrderRefund extends BaseModel
{
    protected $fillable = ['order_id', 'amount', 'reason', 'notes', 'status', 'user_id'];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public static function rules($id = null)
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
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
            'amount' => [
                'label' => 'Số tiền hoàn',
                'type' => 'number',
                'step' => '0.01',
                'sortable' => true
            ],
            'reason' => [
                'label' => 'Lý do hoàn tiền',
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
            'status' => [
                'label' => 'Trạng thái',
                'type' => 'select',
                'options' => [
                    'pending' => 'Đang chờ xử lý',
                    'approved' => 'Đã duyệt',
                    'rejected' => 'Từ chối'
                ],
                'filterable' => true,
                'filter_options' => [
                    'pending' => 'Đang chờ xử lý',
                    'approved' => 'Đã duyệt',
                    'rejected' => 'Từ chối'
                ],
                'sortable' => true
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
                'label' => 'Thời gian tạo',
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