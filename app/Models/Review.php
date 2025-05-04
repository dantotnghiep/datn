<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends BaseModel
{
    use SoftDeletes;

    protected $hasSlug = false;

    protected $fillable = [
        'user_id',
        'order_id',
        'rating',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    protected $appends = ['user_name', 'order_number'];

    public static function rules($id = null)
    {
        return [
            'user_id' => 'required|exists:users,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
        ];
    }

    public static function getFields()
    {
        return [
            'user_id' => [
                'label' => 'Khách hàng',
                'type' => 'select',
                'options' => User::pluck('name', 'id')->toArray(),
                'filterable' => true,
                'filter_options' => User::pluck('name', 'id')->toArray(),
                'sortable' => true,
                'display' => 'user_name'
            ],
            'order_id' => [
                'label' => 'Đơn hàng',
                'type' => 'select',
                'options' => Order::pluck('order_number', 'id')->toArray(),
                'filterable' => true,
                'filter_options' => Order::pluck('order_number', 'id')->toArray(),
                'sortable' => true,
                'display' => 'order_number'
            ],
            'rating' => [
                'label' => 'Đánh giá sao',
                'type' => 'number',
                'min' => 1,
                'max' => 5,
                'options' => [
                    1 => '1 sao',
                    2 => '2 sao',
                    3 => '3 sao',
                    4 => '4 sao',
                    5 => '5 sao'
                ],
                'filterable' => true,
                'filter_options' => [
                    1 => '1 sao',
                    2 => '2 sao',
                    3 => '3 sao',
                    4 => '4 sao',
                    5 => '5 sao'
                ],
                'sortable' => true               
            ],
            'created_at' => [
                'label' => 'Ngày đánh giá',
                'type' => 'datetime',
                'sortable' => true
            ]
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getUserNameAttribute()
    {
        return $this->user->name ?? '';
    }

    public function getOrderNumberAttribute()
    {
        return $this->order->order_number ?? '';
    }
} 