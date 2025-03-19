<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order_status extends Model
{
    use HasFactory;

    protected $table = 'order_statuses';
    protected $fillable = ['name', 'description'];

    public function orderStatusTimes()
    {
        return $this->hasMany(Order_status_time::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
