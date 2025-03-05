<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_image extends Model
{
    use HasFactory;

    protected $fillable = [
        'variation_id',
        'url',
        'is_main'
    ];

    protected $casts = [
        'is_main' => 'boolean'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
