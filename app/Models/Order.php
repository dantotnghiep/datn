<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status_id',
        'order_code',
        'user_name',
        'user_phone',
        'user_email',
        'total_amount',
        'shipping_address',
        'payment_method',
        'discount_code',
        'discount_amount',
    ];

    protected $attributes = [
        'status_id' => 1,
    ];

    public function items()
    {
        return $this->hasMany(Order_item::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function status(){
        return $this->belongsTo(Order_status::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_code = 'ORD' . time() . rand(1000,9999);
        });
    }
}
