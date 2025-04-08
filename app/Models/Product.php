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
        'category_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function variations()
    {
        return $this->hasMany(Variation::class);
    }

    public function defaultVariation()
    {
        return $this->hasOne(Variation::class)->oldest();
    }


    public function additionalImages()
    {
        return $this->images()->where('is_main', false);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'product_id', 'id');
    }
    

}
