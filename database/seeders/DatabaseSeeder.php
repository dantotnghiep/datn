<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Gọi các seeders theo thứ tự hợp lý
        $this->call([
            UserSeeder::class,          // Tạo người dùng trước
            AttributeSeeder::class,     // Tạo thuộc tính sản phẩm
            CategorySeeder::class,      // Tạo danh mục sản phẩm
            ProductSeeder::class,       // Tạo sản phẩm
            ProductImageSeeder::class,  // Tạo ảnh sản phẩm
            ProductVariationSeeder::class, // Tạo biến thể sản phẩm
            OrderStatusSeeder::class,   // Tạo trạng thái đơn hàng
            OrderSeeder::class,         // Tạo đơn hàng mẫu
        ]);
    }
}
