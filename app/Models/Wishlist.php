<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = ['user_id', 'variation_id'];

    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }
}