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
}