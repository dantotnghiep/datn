<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends BaseModel
{
    use SoftDeletes;
    
    protected $fillable = ['name', 'slug', 'description'];

    public static function rules($id = null)
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
        ];
    }

    public static function getFields()
    {
        return [
            'name' => [
                'label' => 'Tên danh mục',
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
            'description' => [
                'label' => 'Mô tả',
                'type' => 'textarea',
                'searchable' => true,
                'sortable' => false
            ],

        ];
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
