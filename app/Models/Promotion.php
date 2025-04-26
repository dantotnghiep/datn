<?php

namespace App\Models;

class Promotion extends BaseModel
{
    protected $fillable = [
        'code', 'name', 'description', 'discount_type', 'discount_value',
        'minimum_spend', 'maximum_discount', 'usage_limit', 'usage_count',
        'is_active', 'starts_at', 'expires_at'
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_spend' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_count' => 'integer',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    public static function rules($id = null)
    {
        return [
            'code' => 'required|string|max:255|unique:promotions,code,' . $id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'minimum_spend' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:0',
            'usage_count' => 'integer|min:0',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at'
        ];
    }

    public static function getFields()
    {
        return [
            'code' => [
                'label' => 'Mã khuyến mãi',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'name' => [
                'label' => 'Tên khuyến mãi',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'description' => [
                'label' => 'Mô tả',
                'type' => 'textarea',
                'searchable' => true,
                'sortable' => false
            ],
            'discount_type' => [
                'label' => 'Loại giảm giá',
                'type' => 'select',
                'options' => [
                    'percentage' => 'Phần trăm',
                    'fixed' => 'Số tiền cố định'
                ],
                'filterable' => true,
                'filter_options' => [
                    'percentage' => 'Phần trăm',
                    'fixed' => 'Số tiền cố định'
                ],
                'sortable' => true
            ],
            'discount_value' => [
                'label' => 'Giá trị giảm giá',
                'type' => 'number',
                'step' => '0.01',
                'sortable' => true
            ],
            'minimum_spend' => [
                'label' => 'Giá trị đơn hàng tối thiểu',
                'type' => 'number',
                'step' => '0.01',
                'sortable' => true
            ],
            'maximum_discount' => [
                'label' => 'Giảm giá tối đa',
                'type' => 'number',
                'step' => '0.01',
                'sortable' => true
            ],
            'usage_limit' => [
                'label' => 'Giới hạn sử dụng',
                'type' => 'number',
                'sortable' => true
            ],
            'usage_count' => [
                'label' => 'Đã sử dụng',
                'type' => 'number',
                'sortable' => true
            ],
            'is_active' => [
                'label' => 'Kích hoạt',
                'type' => 'boolean',
                'filterable' => true,
                'filter_options' => [0 => 'Không', 1 => 'Có'],
                'sortable' => true
            ],
            'starts_at' => [
                'label' => 'Ngày bắt đầu',
                'type' => 'datetime',
                'sortable' => true
            ],
            'expires_at' => [
                'label' => 'Ngày kết thúc',
                'type' => 'datetime',
                'sortable' => true
            ]
        ];
    }

    public function usedPromotions()
    {
        return $this->hasMany(UsedPromotion::class);
    }
} 