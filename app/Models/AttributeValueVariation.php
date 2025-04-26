<?php

namespace App\Models;

class AttributeValueVariation extends BaseModel
{
    protected $fillable = ['product_variation_id', 'attribute_value_id'];
    
    public $timestamps = false;

    protected $primaryKey = null;
    
    public $incrementing = false;

    public static function rules($id = null)
    {
        return [
            'product_variation_id' => 'required|exists:product_variations,id',
            'attribute_value_id' => 'required|exists:attribute_values,id'
        ];
    }

    public static function getFields()
    {
        return [
            'product_variation_id' => [
                'label' => 'Biến thể sản phẩm',
                'type' => 'select',
                'options' => ProductVariation::with('product')->get()->map(function ($variation) {
                    return [
                        'id' => $variation->id,
                        'name' => $variation->product->name . ' - ' . $variation->name
                    ];
                })->pluck('name', 'id')->toArray(),
                'filterable' => true,
                'sortable' => true
            ],
            'attribute_value_id' => [
                'label' => 'Giá trị thuộc tính',
                'type' => 'select',
                'options' => AttributeValue::with('attribute')->get()->map(function ($value) {
                    return [
                        'id' => $value->id,
                        'name' => $value->attribute->name . ': ' . $value->value
                    ];
                })->pluck('name', 'id')->toArray(),
                'filterable' => true,
                'sortable' => true
            ]
        ];
    }

    public function productVariation()
    {
        return $this->belongsTo(ProductVariation::class);
    }

    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class);
    }
} 