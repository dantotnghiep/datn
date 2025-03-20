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
        'sale',
        'startDate',
        'endDate',
        'usageCount',
        'maxUsage',
        'minOrderValue',
        'maxDiscount'
    ];

    protected $casts = [
        'startDate' => 'datetime:Y-m-d H:i:s',
        'endDate' => 'datetime:Y-m-d H:i:s',
        'sale' => 'decimal:2',
        'minOrderValue' => 'decimal:2',
        'maxDiscount' => 'decimal:2',
    ];

    // Thêm accessor để format thời gian
    public function getStartDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value);
    }

    public function getEndDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value);
    }
}