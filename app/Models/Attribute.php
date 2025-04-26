<?php

namespace App\Models;

class Attribute extends BaseModel
{
    protected $fillable = ['name'];

    public static function rules($id = null)
    {
        return [
            'name' => 'required|string|max:255'
        ];
    }

    public static function getFields()
    {
        return [
            'name' => [
                'label' => 'Tên thuộc tính',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ]
        ];
    }

    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
