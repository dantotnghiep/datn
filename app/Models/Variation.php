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


    public function mainImage()
    {
        return $this->hasOne(ProductImage::class, 'product_id', 'product_id')->where('is_main', true);
    }

    public function attributes()
    {
        // Adjust the relationship to use the correct table name `attribute_values_variations`
        return $this->belongsToMany(
            Attribute::class, // Related model
            'attribute_values_variations', // Pivot table
            'variation_id', // Foreign key on the pivot table
            'attribute_value_id' // Related key on the pivot table
        );
    }

    /**
     * Get the attribute values for the variation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributeValues()
    {
        // Cập nhật quan hệ sử dụng belongsToMany để liên kết với bảng pivot attribute_values_variations
        return $this->belongsToMany(
            Attribute_value::class, // Mô hình liên kết
            'attribute_values_variations', // Bảng pivot
            'variation_id', // Khóa ngoại trong bảng pivot
            'attribute_value_id' // Khóa ngoại trong bảng pivot
        );
    }
} 
