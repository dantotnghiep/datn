<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::Create([
            'name'=>'Áo Phông',
            'slug'=>'ao-phong',
            'description'=>'Áo Phông Chất Lượng Đẹp Thuộc Danh Mục Mới Của Nhà Phát Hành',
        ]);
    }
}
