<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends BaseModel
{
    use SoftDeletes;
    
    protected $fillable = ['category_id', 'name', 'slug', 'sku', 'description', 'is_hot'];

    public static function rules($id = null)
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $id,
            'sku' => 'required|string|max:255|unique:products,sku,' . $id,
            'description' => 'nullable|string',
            'is_hot' => 'boolean'
        ];
    }

    public static function getFields()
    {
        return [
            'category_id' => [
                'label' => 'Danh mục',
                'type' => 'select',
                'options' => Category::pluck('name', 'id')->toArray(),
                'filterable' => true,
                'filter_options' => Category::pluck('name', 'id')->toArray(),
                'sortable' => true
            ],
            'name' => [
                'label' => 'Tên sản phẩm',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'slug' => [
                'label' => 'Slug',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'sku' => [
                'label' => 'Mã sản phẩm',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'description' => [
                'label' => 'Mô tả',
                'type' => 'textarea',
                'searchable' => true,
                'sortable' => false
            ],
            'is_hot' => [
                'label' => 'Sản phẩm nổi bật',
                'type' => 'boolean',
                'filterable' => true,
                'filter_options' => [0 => 'Không', 1 => 'Có'],
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
