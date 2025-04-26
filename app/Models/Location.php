<?php

namespace App\Models;

class Location extends BaseModel
{
    protected $fillable = ['province', 'district', 'ward', 'address', 'user_id', 'is_default', 'recipient_name', 'recipient_phone'];

    public static function rules($id = null)
    {
        return [
            'province' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'is_default' => 'boolean',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20'
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
            ],
            'recipient_name' => [
                'label' => 'Tên người nhận',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'recipient_phone' => [
                'label' => 'Số điện thoại người nhận',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ]
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 