<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable =[
       'name',
       'slug',
       'description',
       'status',
    ];

    public function product(){
        return $this->hasMany(Product::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id'); // category_id là khóa ngoại trong bảng products
    }
}
