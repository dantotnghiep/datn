<?php

namespace App\Models;

class OrderStatus extends BaseModel
{
    protected $fillable = ['name', 'description'];

    public static function rules($id = null)
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    public static function getFields()
    {
        return [
            'name' => [
                'label' => 'Tên trạng thái',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'description' => [
                'label' => 'Mô tả',
                'type' => 'textarea',
                'searchable' => true,
                'sortable' => false
            ]
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'status_id');
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class, 'status_id');
    }
} 