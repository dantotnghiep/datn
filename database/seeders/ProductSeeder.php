<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mảng tên sản phẩm cho mỗi danh mục
        $productNames = [
            'ao' => [
                'Áo Thun Basic', 'Áo Sơ Mi Nam', 'Áo Polo Cotton', 'Áo Khoác Bomber',
                'Áo Hoodie', 'Áo Khoác Denim', 'Áo Len', 'Áo Thun Graphic',
                'Áo Cardigan', 'Áo Phông Dài Tay', 'Áo Tank Top', 'Áo Blazer',
                'Áo Thun Nữ', 'Áo Linen', 'Áo Cổ Lọ', 'Áo Thun Premium',
                'Áo Khoác Gió', 'Áo Sweater', 'Áo Phông Local Brand', 'Áo Thun Oversize'
            ],
            'quan' => [
                'Quần Jeans Slim Fit', 'Quần Kaki Nam', 'Quần Short Thể Thao', 'Quần Jogger Cotton',
                'Quần Tây Công Sở', 'Quần Jean Baggy', 'Quần Shorts Đi Biển', 'Quần Lót Nam',
                'Quần Dài Nữ', 'Quần Culottes', 'Quần Jean Skinny', 'Quần Legging',
                'Quần Ống Rộng', 'Quần Linen', 'Quần Short Jeans', 'Quần Thun Nữ',
                'Quần Tây Co Giãn', 'Quần Nỉ Nam', 'Quần Đùi Thể Thao', 'Quần Vải Flannel'
            ],
            'giay-dep' => [
                'Giày Sneaker', 'Dép Lê Nam', 'Giày Tây Công Sở', 'Giày Thể Thao Nữ',
                'Dép Quai Hậu', 'Giày Lười Nam', 'Sandal Nữ', 'Giày Boot Nữ',
                'Giày Bata', 'Dép Eva', 'Giày Cao Gót', 'Giày Oxford',
                'Dép Đi Trong Nhà', 'Giày Mọi', 'Giày Đế Bằng', 'Giày Slip-on',
                'Dép Xỏ Ngón', 'Giày Espadrilles', 'Dép Thể Thao', 'Giày Búp Bê'
            ],
            'phu-kien' => [
                'Túi Xách Nữ', 'Ví Nam', 'Thắt Lưng Da', 'Đồng Hồ Đeo Tay',
                'Mũ Nón Thời Trang', 'Kính Mát', 'Cà Vạt', 'Khăn Quàng Cổ',
                'Túi Đeo Chéo', 'Balo Laptop', 'Vòng Tay', 'Dây Chuyền',
                'Tất Vớ Nam', 'Găng Tay', 'Phụ Kiện Tóc', 'Mặt Nạ Dưỡng Da',
                'Ví Nữ', 'Bông Tai', 'Bút Ký Cao Cấp', 'Móc Khóa'
            ]
        ];
        
        // Get category IDs from database
        $categories = DB::table('categories')->get();
        
        // Create 20 products for each category
        foreach ($categories as $category) {
            // Lấy mảng tên sản phẩm tương ứng với slug của danh mục
            $names = $productNames[$category->slug] ?? [];
            
            // Nếu không có tên sản phẩm trong mảng, tạo tên ngẫu nhiên
            if (empty($names)) {
                for ($i = 1; $i <= 20; $i++) {
                    $productName = "Sản phẩm {$category->name} $i";
                    $slug = Str::slug($productName);
                    $sku = strtoupper(substr($category->slug, 0, 2) . rand(1000, 9999));
                    
                    DB::table('products')->insert([
                        'category_id' => $category->id,
                        'name' => $productName,
                        'slug' => $slug,
                        'sku' => $sku,
                        'description' => "Mô tả chi tiết cho {$productName}",
                        'is_hot' => rand(0, 1),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                // Sử dụng tên từ mảng đã định nghĩa
                foreach ($names as $name) {
                    $slug = Str::slug($name);
                    $sku = strtoupper(substr($category->slug, 0, 2) . rand(1000, 9999));
                    
                    DB::table('products')->insert([
                        'category_id' => $category->id,
                        'name' => $name,
                        'slug' => $slug,
                        'sku' => $sku,
                        'description' => "Mô tả chi tiết cho {$name}. Sản phẩm chất lượng cao, thiết kế hiện đại, phù hợp với nhiều phong cách thời trang.",
                        'is_hot' => rand(0, 1),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
} 