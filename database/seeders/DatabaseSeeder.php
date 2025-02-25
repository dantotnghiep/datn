<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Categories
        $categories = [
            [
                'name' => 'Áo Phông',
                'slug' => 'ao-phong',
                'description' => 'Các loại áo phông thời trang',
                'status' => 'active'
            ],
            [
                'name' => 'Quần Jean',
                'slug' => 'quan-jean',
                'description' => 'Quần jean đa dạng phong cách',
                'status' => 'active'
            ],
            [
                'name' => 'Áo Khoác',
                'slug' => 'ao-khoac',
                'description' => 'Áo khoác thời trang nam nữ',
                'status' => 'active'
            ],
            [
                'name' => 'Váy Đầm',
                'slug' => 'vay-dam',
                'description' => 'Váy đầm thời trang nữ',
                'status' => 'active'
            ],
            [
                'name' => 'Phụ Kiện',
                'slug' => 'phu-kien',
                'description' => 'Các loại phụ kiện thời trang',
                'status' => 'active'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Seed Attributes
        $attributes = [
            [
                'name' => 'Kích Thước',
                'slug' => 'kich-thuoc',
            ],
            [
                'name' => 'Màu Sắc',
                'slug' => 'mau-sac',
            ],
            [
                'name' => 'Chất Liệu',
                'slug' => 'chat-lieu',
            ],
            [
                'name' => 'Kiểu Dáng',
                'slug' => 'kieu-dang',
            ],
            [
                'name' => 'Mùa',
                'slug' => 'mua',
            ],
        ];

        foreach ($attributes as $attribute) {
            Attribute::create($attribute);
        }

        // Seed Attribute Values
        $attributeValues = [
            // Kích Thước (ID: 1)
            [
                'attribute_id' => 1,
                'value' => 'S',
                'slug' => 's'
            ],
            [
                'attribute_id' => 1,
                'value' => 'M',
                'slug' => 'm'
            ],
            [
                'attribute_id' => 1,
                'value' => 'L',
                'slug' => 'l'
            ],
            [
                'attribute_id' => 1,
                'value' => 'XL',
                'slug' => 'xl'
            ],
            [
                'attribute_id' => 1,
                'value' => 'XXL',
                'slug' => 'xxl'
            ],

            // Màu Sắc (ID: 2)
            [
                'attribute_id' => 2,
                'value' => 'Đen',
                'slug' => 'den'
            ],
            [
                'attribute_id' => 2,
                'value' => 'Trắng',
                'slug' => 'trang'
            ],
            [
                'attribute_id' => 2,
                'value' => 'Đỏ',
                'slug' => 'do'
            ],
            [
                'attribute_id' => 2,
                'value' => 'Xanh',
                'slug' => 'xanh'
            ],
            [
                'attribute_id' => 2,
                'value' => 'Vàng',
                'slug' => 'vang'
            ],

// Chất Liệu (ID: 3)
            [
                'attribute_id' => 3,
                'value' => 'Cotton',
                'slug' => 'cotton'
            ],
            [
                'attribute_id' => 3,
                'value' => 'Len',
                'slug' => 'len'
            ],
            [
                'attribute_id' => 3,
                'value' => 'Lụa',
                'slug' => 'lua'
            ],
            [
                'attribute_id' => 3,
                'value' => 'Jeans',
                'slug' => 'jeans'
            ],
            [
                'attribute_id' => 3,
                'value' => 'Kaki',
                'slug' => 'kaki'
            ],

            // Kiểu Dáng (ID: 4)
            [
                'attribute_id' => 4,
                'value' => 'Ôm',
                'slug' => 'om'
            ],
            [
                'attribute_id' => 4,
                'value' => 'Suông',
                'slug' => 'suong'
            ],
            [
                'attribute_id' => 4,
                'value' => 'Rộng',
                'slug' => 'rong'
            ],
            [
                'attribute_id' => 4,
                'value' => 'Cơ Bản',
                'slug' => 'co-ban'
            ],
            [
                'attribute_id' => 4,
                'value' => 'Oversize',
                'slug' => 'oversize'
            ],

            // Mùa (ID: 5)
            [
                'attribute_id' => 5,
                'value' => 'Xuân',
                'slug' => 'xuan'
            ],
            [
                'attribute_id' => 5,
                'value' => 'Hạ',
                'slug' => 'ha'
            ],
            [
                'attribute_id' => 5,
                'value' => 'Thu',
                'slug' => 'thu'
            ],
            [
                'attribute_id' => 5,
                'value' => 'Đông',
                'slug' => 'dong'
            ],
            [
                'attribute_id' => 5,
                'value' => '4 Mùa',
                'slug' => '4-mua'
            ],
        ];

        foreach ($attributeValues as $value) {
            AttributeValue::create($value);
        }
    }
}
