<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy tất cả sản phẩm
        $products = DB::table('products')->get();
        
        // Mảng các đường dẫn ảnh mẫu
        $sampleImages = [
            'product_images/image1.jpg',
            'product_images/image2.jpg',
            'product_images/image3.jpg',
            'product_images/image4.jpg',
            'product_images/image5.jpg',
        ];
        
        foreach ($products as $product) {
            // Mỗi sản phẩm có 2-4 ảnh
            $imageCount = rand(2, 4);
            
            for ($i = 0; $i < $imageCount; $i++) {
                // Chọn ngẫu nhiên một ảnh từ mảng mẫu
                $imagePath = $sampleImages[array_rand($sampleImages)];
                
                // Ảnh đầu tiên là ảnh chính
                $isPrimary = ($i === 0);
                
                DB::table('product_images')->insert([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'is_primary' => $isPrimary,
                    'order' => $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
} 