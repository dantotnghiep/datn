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
        'startDate' => 'datetime',
        'endDate' => 'datetime',
        'sale' => 'decimal:2',
        'minOrderValue' => 'decimal:2',
        'maxDiscount' => 'decimal:2',
    ];
}