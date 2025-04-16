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
        'payment_status',
        'vnpay_transaction_no',
        'vnpay_payment_date'
    ];

    protected $attributes = [
        'status_id' => 1,
        'payment_status' => 'pending',
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

    public function canUpdateStatus($newStatusId, $user)
    {
        // Admin có thể cập nhật bất kỳ trạng thái nào
        if ($user->role === 'admin') {
            return true;
        }

        // Khách hàng chỉ có thể hủy đơn hàng khi ở trạng thái chờ xác nhận hoặc đang vận chuyển
        if ($user->role === 'customer' && $newStatusId == 5) {
            return $this->status_id == 1 || $this->status_id == 2;
        }

        // Khách hàng không thể cập nhật các trạng thái khác
        return false;
    }
}
