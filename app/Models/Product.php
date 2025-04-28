<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends BaseModel
{
    use SoftDeletes;

    protected $fillable = ['category_id', 'name', 'slug', 'sku', 'description', 'image', 'is_hot'];

    public static function rules($id = null)
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'is_hot' => 'boolean'
        ];
    }

    public static function getFields()
    {
        return [
            'category_id' => [
                'label' => 'Category',
                'type' => 'select',
                'options' => Category::pluck('name', 'id')->toArray(),
                'filterable' => true,
                'filter_options' => Category::pluck('name', 'id')->toArray(),
                'sortable' => true
            ],
            'name' => [
                'label' => 'Product Name',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'sku' => [
                'label' => 'SKU',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'description' => [
                'label' => 'Description',
                'type' => 'textarea',
                'searchable' => true,
                'sortable' => false
            ],
            'image' => [
                'label' => 'Product Image',
                'type' => 'file',
                'sortable' => false
            ],
            'is_hot' => [
                'label' => 'Featured Product',
                'type' => 'select',
                'options' => [0 => 'No', 1 => 'Yes'],
                'filterable' => true,
                'filter_options' => [0 => 'No', 1 => 'Yes'],
                'sortable' => true
            ]
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }
}
