<?php

namespace App\Models;

class OrderRefund extends BaseModel
{
    protected $fillable = ['order_id', 'amount', 'reason', 'notes', 'status', 'refund_status', 'user_id', 'bank', 'bank_number', 'bank_name', 'is_active'];

    protected $casts = [
            'amount' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public static function rules($id = null)
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
            'refund_status' => 'required|in:pending,approved,rejected',
            'user_id' => 'required|exists:users,id',
            'bank' => 'nullable|string|max:255',
            'bank_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'is_active' => 'required|boolean'
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
            'refund_status' => [
                'label' => 'Trạng thái hoàn tiền',
                'type' => 'select',
                'options' => [
                    'pending' => 'Chưa hoàn tiền',
                    'approved' => 'Đã hoàn tiền',
                    'rejected' => 'Từ chối hoàn tiền'
                ],
                'filterable' => true,
                'filter_options' => [
                    'pending' => 'Chưa hoàn tiền',
                    'approved' => 'Đã hoàn tiền',
                    'rejected' => 'Từ chối hoàn tiền'
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
            'bank' => [
                'label' => 'Ngân hàng',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'bank_number' => [
                'label' => 'Số tài khoản',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'bank_name' => [
                'label' => 'Tên ngân hàng',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'is_active' => [
                'label' => 'Kích hoạt',
                'type' => 'checkbox',
                'filterable' => true,
                'filter_options' => [
                    '1' => 'Kích hoạt',
                    '0' => 'Vô hiệu'
                ],
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
