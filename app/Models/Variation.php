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
        return $this->hasOne(Product_image::class, 'variation_id')->where('is_main', true);
    }

    public function attributeValues()
    {
        return $this->belongsToMany(Attribute_value::class);
    }

} 