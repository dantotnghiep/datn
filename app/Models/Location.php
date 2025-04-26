<?php

namespace App\Models;

class Location extends BaseModel
{
    protected $fillable = [
        'province', 
        'district', 
        'ward', 
        'address', 
        'user_id', 
        'is_default',
        'country'
    ];

    public static function rules($id = null)
    {
        return [
            'province' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'is_default' => 'boolean',
            'country' => 'required|string|max:255'
        ];
    }

    public static function getFields()
    {
        return [
            'province' => [
                'label' => 'Tỉnh/Thành phố',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'district' => [
                'label' => 'Quận/Huyện',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'ward' => [
                'label' => 'Phường/Xã',
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
            'country' => [
                'label' => 'Quốc gia',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'user_id' => [
                'label' => 'Người dùng',
                'type' => 'select',
                'options' => User::pluck('name', 'id')->toArray(),
                'filterable' => true,
                'filter_options' => User::pluck('name', 'id')->toArray(),
                'sortable' => true
            ],
            'is_default' => [
                'label' => 'Mặc định',
                'type' => 'boolean',
                'filterable' => true,
                'filter_options' => [0 => 'Không', 1 => 'Có'],
                'sortable' => true
            ]
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 