<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasSlug;

class Product extends BaseModel
{
    use SoftDeletes, HasSlug;

    protected $fillable = ['category_id', 'name', 'slug', 'sku', 'description', 'image', 'is_hot'];

    // Prevent HasSlug trait from regenerating slug
    protected $hasSlug = false;

    public static function rules($id = null)
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:products,name,' . $id,
            'sku' => 'sometimes|string|max:100|unique:products,sku,' . $id,
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'is_hot' => 'boolean'
        ];
    }

    public static function getFields()
    {
        return [
            'sku' => [
                'label' => 'SKU',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'category_id' => [
                'label' => 'Danh mục',
                'type' => 'select',
                'options' => Category::orderBy('name')->pluck('name', 'id')->toArray(),
                'filterable' => true,
                'filter_options' => Category::orderBy('name')->pluck('name', 'id')->toArray(),
                'sortable' => true
            ],
            'image' => [
                'label' => 'Hình ảnh',
                'type' => 'file',
                'sortable' => false,
                'searchable' => false
            ],
            'name' => [
                'label' => 'Tên sản phẩm',
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
                'type' => 'select',
                'options' => [
                    0 => 'Không',
                    1 => 'Có'
                ],
                'filter_options' => [
                    0 => 'Không',
                    1 => 'Có'
                ],
                'filterable' => true,
                'sortable' => true
            ]
        ];
    }

    public function getIsHotAttribute($value)
    {
        return $value == 1 ? 'Có' : 'Không';
    }

    public function getCategoryIdAttribute($value)
    {
        $category = Category::find($value);
        return $category ? $category->name : $value;
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function getFirstImageAttribute()
    {
        $firstImage = $this->images()->where('is_primary', 1)->first() ?? $this->images->first();
        return $firstImage ? asset('storage/' . $firstImage->image_path) : asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/products/6.png');
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }
}
