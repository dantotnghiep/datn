<?php

namespace App\Models;

class ProductImage extends BaseModel
{
    protected $fillable = ['product_id', 'image_path', 'is_primary', 'order'];

    public static function rules($id = null)
    {
        return [
            'product_id' => 'required|exists:products,id',
            'image_path' => 'required|string',
            'is_primary' => 'boolean',
            'order' => 'integer'
        ];
    }

    public static function getFields()
    {
        return [
            'product_id' => [
                'label' => 'Sản phẩm',
                'type' => 'select',
                'options' => Product::pluck('name', 'id')->toArray(),
                'filterable' => true,
                'filter_options' => Product::pluck('name', 'id')->toArray(),
                'sortable' => true
            ],
            'image_path' => [
                'label' => 'Đường dẫn ảnh',
                'type' => 'file',
                'searchable' => true,
                'sortable' => false
            ],
            'is_primary' => [
                'label' => 'Ảnh chính',
                'type' => 'boolean',
                'filterable' => true,
                'filter_options' => [0 => 'Không', 1 => 'Có'],
                'sortable' => true
            ],
            'order' => [
                'label' => 'Thứ tự',
                'type' => 'number',
                'sortable' => true
            ]
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
} 