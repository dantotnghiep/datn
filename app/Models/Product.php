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

    public function mainImage()
    {
        return $this->defaultVariation()->with('mainImage');
    }

    public function additionalImages()
    {
        return $this->defaultVariation()->with(['images' => function($query) {
            $query->where('is_main', false);
        }]);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

}
