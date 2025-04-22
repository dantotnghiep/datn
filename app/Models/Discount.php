<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use SoftDeletes;

    protected $table = 'discounts';

    protected $fillable = [
        'code',
        'type',
        'sale',
        'startDate',
        'endDate',
        'usageCount',
        'maxUsage',
        'user_limit',
        'is_public',
        'minOrderValue',
        'maxDiscount',
        'applicable_products',
        'applicable_categories',
        'status'
    ];

    protected $casts = [
        'startDate' => 'datetime:Y-m-d H:i:s',
        'endDate' => 'datetime:Y-m-d H:i:s',
        'sale' => 'decimal:2',
        'minOrderValue' => 'decimal:2',
        'maxDiscount' => 'decimal:2',
        'usageCount' => 'integer',
        'maxUsage' => 'integer',
        'user_limit' => 'integer',
        'is_public' => 'boolean',
        'applicable_products' => 'json',
        'applicable_categories' => 'json'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'startDate',
        'endDate',
        'deleted_at'
    ];

    /**
     * The possible values for the type field.
     */
    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED = 'fixed';

    /**
     * The possible values for the status field.
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_EXPIRED = 'expired';

    /**
     * Get formatted start date
     */
    public function getStartDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value);
    }

    /**
     * Get formatted end date
     */
    public function getEndDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value);
    }

    /**
     * Check if discount can be applied to cart
     */
    public function canApplyToCart($cartTotal, $userId = null)
    {
        // Kiểm tra trạng thái
        if ($this->status !== 'active') {
            return false;
        }

        // Kiểm tra thời gian
        $now = now();
        if ($now < $this->startDate || $now > $this->endDate) {
            return false;
        }

        // Kiểm tra giá trị đơn hàng tối thiểu
        if ($this->minOrderValue && $cartTotal < $this->minOrderValue) {
            return false;
        }

        // Kiểm tra số lần sử dụng tổng
        if ($this->maxUsage && $this->usageCount >= $this->maxUsage) {
            return false;
        }

        // Kiểm tra quyền sử dụng
        if (!$this->is_public && $userId) {
            // Nếu là mã private, kiểm tra user có được assign không
            if (!$this->isAssignedToUser($userId)) {
                return false;
            }
        }

        // Kiểm tra giới hạn sử dụng của user
        if ($this->user_limit && $userId) {
            $userUsageCount = \DB::table('orders')
                ->where('user_id', $userId)
                ->where('discount_code', $this->code)
                ->count();
            if ($userUsageCount >= $this->user_limit) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate discount amount for cart total
     */
    public function calculateDiscountAmount($cartTotal)
    {
        if ($this->type === self::TYPE_PERCENTAGE) {
            $amount = ($cartTotal * $this->sale) / 100;
            if ($this->maxDiscount > 0) {
                return min($amount, $this->maxDiscount);
            }
            return $amount;
        }
        
        return $this->sale; // Fixed amount
    }

    /**
     * Check if discount is assigned to user
     */
    protected function isAssignedToUser($userId)
    {
        // Implement your logic to check if discount is assigned to user
        // For example, if you have a pivot table discount_user:
        // return $this->users()->where('user_id', $userId)->exists();
        
        // For now, we'll return true if the discount is public
        return $this->is_public;
    }
}