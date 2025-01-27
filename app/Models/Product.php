<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'sale_start',
        'sale_end',
        'category_id',
        'status',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
    public function mainImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_main', 1);
    }
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes', 'product_id', 'attribute_id')
            ->withPivot('attribute_value_id')
            ->withTimestamps();
    }
    public function additionalImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id')
            ->where('is_main', false); // Giả sử `is_main` là cột để phân biệt ảnh chính
    }
}
