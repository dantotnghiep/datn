<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'variation_id',
        'product_name',
        'color',
        'size',
        'quantity',
        'price'
    ];

    /**
     * Get the user that owns the cart item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the variation that belongs to the cart item.
     */
    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }

    /**
     * Get total price for this cart item
     */
    public function getTotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }
}