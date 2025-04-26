<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariation extends BaseModel
{
    use SoftDeletes;
    
    protected $fillable = ['product_id', 'sku', 'name', 'price', 'sale_price', 'stock'];

    public static function rules($id = null)
    {
        return [
            'product_id' => 'required|exists:products,id',
            'sku' => 'required|string|max:255|unique:product_variations,sku,' . $id,
            'name' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'integer|min:0'
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
            'sku' => [
                'label' => 'Mã biến thể',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'name' => [
                'label' => 'Tên biến thể',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'price' => [
                'label' => 'Giá',
                'type' => 'number',
                'step' => '0.01',
                'sortable' => true
            ],
            'sale_price' => [
                'label' => 'Giá khuyến mãi',
                'type' => 'number',
                'step' => '0.01',
                'sortable' => true
            ],
            'stock' => [
                'label' => 'Tồn kho',
                'type' => 'number',
                'sortable' => true
            ]
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'attribute_value_variations');
    }
} 