<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';
    protected $fillable = ['code', 'amount', 'type', 'start_date', 'end_date'];
} 