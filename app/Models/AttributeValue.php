<?php

namespace App\Models;

class AttributeValue extends BaseModel
{
    protected $fillable = ['attribute_id', 'value'];

    public static function rules($id = null)
    {
        return [
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:255'
        ];
    }

    public static function getFields()
    {
        return [
            'attribute_id' => [
                'label' => 'Thuộc tính',
                'type' => 'select',
                'options' => Attribute::pluck('name', 'id')->toArray(),
                'filterable' => true,
                'filter_options' => Attribute::pluck('name', 'id')->toArray(),
                'sortable' => true
            ],
            'value' => [
                'label' => 'Giá trị',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ]
        ];
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function variations()
    {
        return $this->belongsToMany(ProductVariation::class, 'attribute_value_variations');
    }
} 