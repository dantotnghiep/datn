<?php

namespace App\Models;


class Order extends BaseModel
{
    protected $hasSlug = false;

    protected $fillable = [
        'order_number',
        'user_id',
        'status_id',
        'user_name',
        'user_phone',
        'province',
        'district',
        'ward',
        'address',
        'discount',
        'total',
        'total_with_discount',
        'notes',
        'payment_method',
        'payment_status',
        'paid_at'
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
                'label' => 'Order Number',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],

            'user_name' => [
                'label' => 'Customer Name',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'user_phone' => [
                'label' => 'Customer Phone',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'address' => [
                'label' => 'Address',
                'type' => 'text',
                'searchable' => true,
                'sortable' => false
            ],
            'total_with_discount' => [
                'label' => 'Total Price',
                'type' => 'number',
                'step' => '0.01',
                'sortable' => true
            ],
            'payment_method' => [
                'label' => 'Payment Method',
                'type' => 'select',
                'options' => [
                    'bank' => 'Bank Transfer',
                    'cod' => 'Cash on Delivery'
                ],
                'filterable' => true,
                'filter_options' => [
                    'bank' => 'Bank Transfer',
                    'cod' => 'Cash on Delivery'
                ],
                'sortable' => true
            ],
            'payment_status' => [
                'label' => 'Payment Status',
                'type' => 'select',
                'options' => [
                    'pending' => 'Pending',
                    'completed' => 'Completed',
                    'failed' => 'Failed'
                ],
                'filterable' => true,
                'filter_options' => [
                    'pending' => 'Pending',
                    'completed' => 'Completed',
                    'failed' => 'Failed'
                ],
                'sortable' => true
            ],
            'paid_at' => [
                'label' => 'Payment Date',
                'type' => 'datetime',
                'sortable' => true
            ],
            'status_id' => [
                'label' => 'Status',
                'type' => 'select',
                'options' => OrderStatus::pluck('name', 'id')->toArray(),
                'filterable' => true,
                'filter_options' => OrderStatus::pluck('name', 'id')->toArray(),
                'sortable' => true
            ],
        ];
    }

    public function getUserIdAttribute($value)
    {
        $user = User::find($value);
        return $user ? $user->name : $value;
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

    public function refund()
    {
        return $this->hasOne(OrderRefund::class)->latest();
    }
}
