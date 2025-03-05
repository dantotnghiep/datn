<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'sale_price',
        'sale_start',
        'sale_end',
        'stock'
    ];

    protected $casts = [
        'sale_start' => 'datetime',
        'sale_end' => 'datetime',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'variation_id');
    }

    public function mainImage()
    {
        return $this->hasOne(ProductImage::class, 'variation_id')->where('is_main', true);
    }

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'attribute_values_variations', 'variation_id', 'attribute_value_id');
    }

    // Helper method để lấy giá trị của một attribute cụ thể
    public function getAttributeValue($attributeName)
    {
        return $this->attributeValues()
                    ->whereHas('attribute', function($query) use ($attributeName) {
                        $query->where('name', $attributeName);
                    })
                    ->first();
    }
} 