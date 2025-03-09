<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Variation;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // // Seed Categories
        // $categories = [
        //     [
        //         'name' => 'Áo Phông',
        //         'slug' => 'ao-phong',
        //         'description' => 'Các loại áo phông thời trang',
        //         'status' => 'active'
        //     ],
        //     [
        //         'name' => 'Quần Jean',
        //         'slug' => 'quan-jean',
        //         'description' => 'Quần jean đa dạng phong cách',
        //         'status' => 'active'
        //     ],
        //     [
        //         'name' => 'Áo Khoác',
        //         'slug' => 'ao-khoac',
        //         'description' => 'Áo khoác thời trang nam nữ',
        //         'status' => 'active'
        //     ],
        //     [
        //         'name' => 'Váy Đầm',
        //         'slug' => 'vay-dam',
        //         'description' => 'Váy đầm thời trang nữ',
        //         'status' => 'active'
        //     ],
        //     [
        //         'name' => 'Phụ Kiện',
        //         'slug' => 'phu-kien',
        //         'description' => 'Các loại phụ kiện thời trang',
        //         'status' => 'active'
        //     ],
        // ];

        // foreach ($categories as $category) {
        //     Category::create($category);
        // }

        // // Seed Attributes
        // $attributes = [
        //     [
        //         'name' => 'Kích Thước',
        //         'slug' => 'kich-thuoc',
        //     ],
        //     [
        //         'name' => 'Màu Sắc',
        //         'slug' => 'mau-sac',
        //     ],
        // ];

        // foreach ($attributes as $attribute) {
        //     Attribute::create($attribute);
        // }

        // // Seed Attribute Values
        // $attributeValues = [
        //     // Kích Thước (ID: 1)
        //     [
        //         'attribute_id' => 1,
        //         'value' => 'S',
        //     ],
        //     [
        //         'attribute_id' => 1,
        //         'value' => 'M',
        //     ],
        //     [
        //         'attribute_id' => 1,
        //         'value' => 'L',
        //     ],
        //     [
        //         'attribute_id' => 1,
        //         'value' => 'XL',
        //     ],
        //     [
        //         'attribute_id' => 1,
        //         'value' => 'XXL',
        //     ],

        //     // Màu Sắc (ID: 2)
        //     [
        //         'attribute_id' => 2,
        //         'value' => 'Đen',
        //     ],
        //     [
        //         'attribute_id' => 2,
        //         'value' => 'Trắng',
        //     ],
        //     [
        //         'attribute_id' => 2,
        //         'value' => 'Đỏ',
        //     ],
        //     [
        //         'attribute_id' => 2,
        //         'value' => 'Xanh',
        //     ],
        //     [
        //         'attribute_id' => 2,
        //         'value' => 'Vàng',
        //     ],
        // ];

        // foreach ($attributeValues as $value) {
        //     Attribute_value::create($value);
        // }

         // Tạo danh mục
         Category::factory(10)->create();

         // Tạo sản phẩm
         Product::factory(20)->create()->each(function ($product) {
             // Mỗi sản phẩm có từ 1-3 biến thể
             Variation::factory(rand(1, 3))->create(['product_id' => $product->id]);
 
             // Mỗi sản phẩm có từ 1-3 hình ảnh
             ProductImage::factory(rand(1, 3))->create(['product_id' => $product->id]);
             
         });
 
         // Tạo thuộc tính
         Attribute::factory(5)->create()->each(function ($attribute) {
             // Mỗi thuộc tính có từ 3-5 giá trị
             AttributeValue::factory(rand(3, 5))->create(['attribute_id' => $attribute->id]);
         });

        $this->call(ProductSeeder::class);
    }
}
