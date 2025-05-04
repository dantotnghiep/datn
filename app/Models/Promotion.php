<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends BaseModel
{
    protected $hasSlug = false;

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
                'label' => 'Số lần sử dụng',
                'type' => 'number',
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
            ],
            'is_active' => [
                'label' => 'Trạng thái',
                'type' => 'boolean',
                'filterable' => true,
                'filter_options' => [0 => 'Không', 1 => 'Có'],
                'sortable' => true
            ],

        ];
    }


    public function usedPromotions()
    {
        return $this->hasMany(UsedPromotion::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($promotion) {
            $now = now()->setTimezone('Asia/Ho_Chi_Minh');

            // Check if usage limit is reached
            if ($promotion->usage_limit > 0 && $promotion->usage_count >= $promotion->usage_limit) {
                $promotion->is_active = false;
                return;
            }

            // Check if promotion is within valid date range
            if ($promotion->starts_at && $now->format('Y-m-d H:i:s') < $promotion->starts_at) {
                $promotion->is_active = false;
                return;
            }

            if ($promotion->expires_at && $now->format('Y-m-d H:i:s') >= $promotion->expires_at) {
                $promotion->is_active = false;
                return;
            }

            // If all checks pass, set promotion as active
            $promotion->is_active = true;
        });

        static::retrieved(function ($promotion) {
            $now = now()->setTimezone('Asia/Ho_Chi_Minh');
            $shouldUpdate = false;

            // Check if usage limit is reached
            if ($promotion->usage_limit > 0 && $promotion->usage_count >= $promotion->usage_limit) {
                $promotion->is_active = false;
                $shouldUpdate = true;
            }
            // Check if promotion is within valid date range
            else if ($promotion->starts_at && $now->format('Y-m-d H:i:s') < $promotion->starts_at) {
                $promotion->is_active = false;
                $shouldUpdate = true;
            }
            else if ($promotion->expires_at && $now->format('Y-m-d H:i:s') >= $promotion->expires_at) {
                $promotion->is_active = false;
                $shouldUpdate = true;
            }
            // If current time matches starts_at and promotion is not expired, activate it
            else if ($promotion->starts_at && $now->format('Y-m-d H:i:s') >= $promotion->starts_at &&
                    (!$promotion->expires_at || $now->format('Y-m-d H:i:s') < $promotion->expires_at)) {
                $promotion->is_active = true;
                $shouldUpdate = true;
            }

            if ($shouldUpdate) {
                $promotion->save();
            }
        });
    }

}
