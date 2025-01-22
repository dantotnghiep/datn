<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;
    protected $fillable =[
        'product_id',
        'sku',
        'price',
        'sale_price',
        'sale_start',
        'sale_end',
        'stock',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributes(){
        return $this->hasMany(VariationAttributeValue::class,'variation_id');
    }
    public function images(){
        return $this->hasMany(ProductImage::class,'variation_id');
    }
    
}
