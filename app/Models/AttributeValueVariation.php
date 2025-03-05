<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttributeValueVariation extends Model
{
    use HasFactory;

    protected $table = 'attribute_values_variations';
    protected $fillable = ['attribute_value_id', 'variation_id'];

    public function attributeValue() {
        return $this->belongsTo(AttributeValue::class);
    }
    public function variation() {
        return $this->belongsTo(Variation::class);
    }
} 