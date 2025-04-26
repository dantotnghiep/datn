<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends BaseModel
{
    use SoftDeletes;
    
    protected $fillable = ['name', 'slug', 'description', 'parent_id'];

    public static function rules($id = null)
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
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
            'parent_id' => [
                'label' => 'Danh mục cha',
                'type' => 'select',
                'options' => self::pluck('name', 'id')->toArray(),
                'filterable' => true,
                'filter_options' => self::pluck('name', 'id')->toArray(),
                'sortable' => true
            ]
        ];
    }

}
