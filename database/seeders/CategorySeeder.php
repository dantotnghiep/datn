<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Áo',
                'slug' => 'ao',
                'description' => 'Các loại áo thời trang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Quần',
                'slug' => 'quan',
                'description' => 'Các loại quần thời trang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Giày dép',
                'slug' => 'giay-dep',
                'description' => 'Các loại giày dép thời trang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Phụ kiện',
                'slug' => 'phu-kien',
                'description' => 'Phụ kiện thời trang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);
    }
} 