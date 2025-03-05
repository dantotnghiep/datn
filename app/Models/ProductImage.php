<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'variation_id',
        'url',
        'is_main'
    ];

    protected $casts = [
        'is_main' => 'boolean'
    ];

    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }
}
