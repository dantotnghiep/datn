<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attribute_value_variation extends Model
{
    use HasFactory;

    protected $fillable = ['attribute_value_id', 'variation_id'];
    

    public function attributeValue()
    {
        return $this->belongsTo(Attribute_value::class);
    }
    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }
}
