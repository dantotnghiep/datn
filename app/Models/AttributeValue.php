<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;

    protected $table = 'attribute_values';
    
    protected $fillable = [
        'attribute_id',
        'value',
        'description'
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function variations()
    {
        return $this->belongsToMany(Variation::class);
    }
}
