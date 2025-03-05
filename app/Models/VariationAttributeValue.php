<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationAttributeValue extends Model
{
    use HasFactory;
    protected $fillable = [
        'variation_id',
        'attribute_value_id',
    ];

    public function variation()
    {
        return $this->belongsTo(Variation::class,'variation_id');
    }

    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class,'attribute_value_id');
    }
}
