<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;

    protected $table = 'locations';
    protected $fillable = ['name', 'address', 'city', 'state', 'zip'];

    public function orders() {
        return $this->hasMany(Order::class);
    }
} 