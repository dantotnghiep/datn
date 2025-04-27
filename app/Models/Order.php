<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends BaseModel
{
    use SoftDeletes;
    
    protected $fillable = [
        'order_number', 'user_id', 'status_id', 'user_name', 'user_phone',
        'province', 'district', 'ward', 'address', 'discount',
        'total', 'total_with_discount', 'notes', 'payment_method',
        'payment_status', 'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'total' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_with_discount' => 'decimal:2'
    ];

    public static function rules($id = null)
    {
        return [
            'order_number' => 'required|string|max:255|unique:orders,order_number,' . $id,
            'user_id' => 'required|exists:users,id',
            'status_id' => 'required|exists:order_status,id',
            'user_name' => 'nullable|string|max:255',
            'user_phone' => 'nullable|string|max:20',
            'province' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'ward' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'discount' => 'numeric|min:0',
            'total' => 'required|numeric|min:0',
            'total_with_discount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'payment_method' => 'nullable|in:bank,cod',
            'payment_status' => 'in:pending,completed,failed',
            'paid_at' => 'nullable|date'
        ];
    }

    public static function getFields()
    {
        return [
            'order_number' => [
                'label' => 'Mã đơn hàng',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'user_id' => [
                'label' => 'Khách hàng',
                'type' => 'select',
                'options' => User::pluck('name', 'id')->toArray(),
                'filterable' => true,
                'filter_options' => User::pluck('name', 'id')->toArray(),
                'sortable' => true
            ],
            'status_id' => [
                'label' => 'Trạng thái đơn hàng',
                'type' => 'select',
                'options' => OrderStatus::pluck('name', 'id')->toArray(),
                'filterable' => true,
                'filter_options' => OrderStatus::pluck('name', 'id')->toArray(),
                'sortable' => true
            ],
            'user_name' => [
                'label' => 'Tên người nhận',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'user_phone' => [
                'label' => 'Số điện thoại',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'address' => [
                'label' => 'Địa chỉ',
                'type' => 'text',
                'searchable' => true,
                'sortable' => false
            ],
            'total' => [
                'label' => 'Tổng tiền',
                'type' => 'number',
                'step' => '0.01',
                'sortable' => true
            ],
            'discount' => [
                'label' => 'Giảm giá',
                'type' => 'number',
                'step' => '0.01',
                'sortable' => true
            ],
            'total_with_discount' => [
                'label' => 'Tổng tiền sau giảm giá',
                'type' => 'number',
                'step' => '0.01',
                'sortable' => true
            ],
            'payment_method' => [
                'label' => 'Phương thức thanh toán',
                'type' => 'select',
                'options' => [
                    'bank' => 'Chuyển khoản',
                    'cod' => 'Thanh toán khi nhận hàng'
                ],
                'filterable' => true,
                'filter_options' => [
                    'bank' => 'Chuyển khoản',
                    'cod' => 'Thanh toán khi nhận hàng'
                ],
                'sortable' => true
            ],
            'payment_status' => [
                'label' => 'Trạng thái thanh toán',
                'type' => 'select',
                'options' => [
                    'pending' => 'Chờ thanh toán',
                    'completed' => 'Đã thanh toán',
                    'failed' => 'Thanh toán thất bại'
                ],
                'filterable' => true,
                'filter_options' => [
                    'pending' => 'Chờ thanh toán',
                    'completed' => 'Đã thanh toán',
                    'failed' => 'Thanh toán thất bại'
                ],
                'sortable' => true
            ],
            'paid_at' => [
                'label' => 'Ngày thanh toán',
                'type' => 'datetime',
                'sortable' => true
            ],
            'notes' => [
                'label' => 'Ghi chú',
                'type' => 'textarea',
                'searchable' => true,
                'sortable' => false
            ]
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function cancellation()
    {
        return $this->hasOne(OrderCancellation::class);
    }

    public function refunds()
    {
        return $this->hasMany(OrderRefund::class);
    }
} 