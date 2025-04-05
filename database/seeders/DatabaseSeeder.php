<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Attribute_value_variation;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Discount;
use App\Models\HotProduct;
use App\Models\Location;
use App\Models\Order;
use App\Models\Order_cancellation;
use App\Models\Order_item;
use App\Models\Order_status;
use App\Models\Order_status_time;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\Variation;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $fakerEn = Faker::create('en_US'); // English faker for compatibility

        // Make sure storage directory exists
        if (!Storage::exists('products')) {
            Storage::makeDirectory('products');
        }

        // 1. Seed Users (20)
        $users = [];

        // Get existing users
        $existingUsers = User::all();
        foreach ($existingUsers as $user) {
            $users[] = $user;
        }

        // Keep track of existing emails and phones
        $existingEmails = $existingUsers->pluck('email')->toArray();
        $existingPhones = $existingUsers->pluck('phone')->toArray();

        // Create admin user if not exists
        if (!in_array('admin@example.com', $existingEmails)) {
            $users[] = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'phone' => '0123456789',
                'address' => $faker->address,
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
            $existingEmails[] = 'admin@example.com';
            $existingPhones[] = '0123456789';
        }

        // Create staff user if not exists
        if (!in_array('staff@example.com', $existingEmails)) {
            $users[] = User::create([
                'name' => 'Staff User',
                'email' => 'staff@example.com',
                'password' => Hash::make('password'),
                'phone' => '0123456788',
                'address' => $faker->address,
                'role' => 'staff',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
            $existingEmails[] = 'staff@example.com';
            $existingPhones[] = '0123456788';
        }

        // Create regular users up to 20 total
        $usersToCreate = max(0, 20 - count($users));
        for ($i = 0; $i < $usersToCreate; $i++) {
            // Generate unique email
            $email = $faker->unique()->safeEmail;
            while (in_array($email, $existingEmails)) {
                $email = $faker->unique()->safeEmail;
            }
            
            // Generate unique phone
            $phone = $faker->numerify('0#########');
            while (in_array($phone, $existingPhones)) {
                $phone = $faker->numerify('0#########');
            }
            
            $status = $faker->randomElement(['active', 'inactive']);
            $users[] = User::create([
                'name' => $faker->name,
                'email' => $email,
                'password' => Hash::make('password'),
                'phone' => $phone,
                'address' => $faker->address,
                'role' => 'user',
                'status' => $status,
                'email_verified_at' => now(),
            ]);
            
            $existingEmails[] = $email;
            $existingPhones[] = $phone;
        }

        // 2. Seed Categories (20)
        $categories = [];
        $categoryNames = [
            'Áo Phông', 'Quần Jean', 'Áo Khoác', 'Váy Đầm', 'Phụ Kiện',
            'Giày', 'Túi Xách', 'Nón', 'Đồng Hồ', 'Kính Mắt',
            'Quần Short', 'Áo Sơ Mi', 'Quần Tây', 'Áo Len', 'Vest',
            'Đồ Thể Thao', 'Đồ Ngủ', 'Đồ Bơi', 'Đồ Trẻ Em', 'Đồ Công Sở'
        ];

        // Get existing categories
        $existingCategories = Category::all();
        foreach ($existingCategories as $category) {
            $categories[] = $category;
        }

        // Track existing slugs
        $existingSlugs = $existingCategories->pluck('slug')->toArray();

        foreach ($categoryNames as $index => $name) {
            $slug = Str::slug($name);
            
            // Only create if slug doesn't exist
            if (!in_array($slug, $existingSlugs)) {
                $category = Category::create([
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $fakerEn->sentence,
                    'status' => $faker->randomElement(['active', 'inactive']),
                ]);
                $categories[] = $category;
                $existingSlugs[] = $slug;
            }
        }

        // 3. Seed Attributes (already have 2, so adding 18 more for a total of 20)
        $attributes = [];
        
        // Load existing attributes
        $existingAttributes = Attribute::all();
        foreach ($existingAttributes as $attr) {
            $attributes[] = $attr;
        }
        
        // Track existing attribute slugs
        $existingAttrSlugs = $existingAttributes->pluck('slug')->toArray();
        
        // Add new attributes
        $attributeNames = [
            'Chất Liệu', 'Mùa', 'Phong Cách', 'Xuất Xứ', 'Loại Cổ',
            'Chiều Dài Tay', 'Họa Tiết', 'Kiểu Dáng', 'Độ Co Giãn', 'Độ Dày',
            'Kiểu Quần', 'Phom', 'Thương Hiệu', 'Độ Tuổi', 'Kỹ Thuật May',
            'Cấu Trúc', 'Loại Váy', 'Kiểu Mũ'
        ];
        
        for ($i = 0; $i < count($attributeNames); $i++) {
            $slug = Str::slug($attributeNames[$i]);
            
            // Only create if slug doesn't exist
            if (!in_array($slug, $existingAttrSlugs)) {
                $attribute = Attribute::create([
                    'name' => $attributeNames[$i],
                    'slug' => $slug,
                ]);
                $attributes[] = $attribute;
                $existingAttrSlugs[] = $slug;
            }
        }

        // 4. Seed Attribute Values (20 for each attribute)
        $attributeValues = [];
        
        // Get existing attribute values
        $existingValues = AttributeValue::all();
        foreach ($existingValues as $value) {
            $attributeValues[] = $value;
        }
        
        // Values for existing Kích Thước attribute (if needed more)
        $sizeValues = ['XXS', 'XXXL', '4XL', '5XL', '6XL', '7XL', '8XL', 'Free Size', '28', '29', '30', '31', '32', '33', '34', 'S-M', 'L-XL', 'Custom'];
        
        // Values for existing Màu Sắc attribute (if needed more)
        $colorValues = ['Xanh Nhạt', 'Xanh Đậm', 'Hồng', 'Cam', 'Tím', 'Nâu', 'Xám', 'Bạc', 'Ánh Kim', 'Xanh Dương', 'Xanh Lá', 'Kem', 'Be', 'Đa Màu', 'Sọc'];
        
        // Add more values for existing attributes if needed
        foreach ($attributes as $attribute) {
            $existingCount = AttributeValue::where('attribute_id', $attribute->id)->count();
            $valuesToAdd = 20 - $existingCount;
            
            if ($valuesToAdd > 0) {
                if ($attribute->name == 'Kích Thước') {
                    // Add more size values
                    for ($i = 0; $i < min($valuesToAdd, count($sizeValues)); $i++) {
                        $attributeValues[] = AttributeValue::create([
                            'attribute_id' => $attribute->id,
                            'value' => $sizeValues[$i],
                        ]);
                    }
                } elseif ($attribute->name == 'Màu Sắc') {
                    // Add more color values
                    for ($i = 0; $i < min($valuesToAdd, count($colorValues)); $i++) {
                        $attributeValues[] = AttributeValue::create([
                            'attribute_id' => $attribute->id,
                            'value' => $colorValues[$i],
                        ]);
                    }
                } else {
                    // Generate random values for other attributes
                    for ($i = 0; $i < $valuesToAdd; $i++) {
                        $value = $fakerEn->word;
                        $attributeValues[] = AttributeValue::create([
                            'attribute_id' => $attribute->id,
                            'value' => $value,
                        ]);
                    }
                }
            }
        }

        // 5. Seed Products (20)
        $products = [];

        // Get existing products and their slugs
        $existingProducts = Product::all();
        foreach ($existingProducts as $product) {
            $products[] = $product;
        }
        $existingProductSlugs = $existingProducts->pluck('slug')->toArray();

        // Create products up to 20 total
        $productsToCreate = max(0, 20 - count($existingProducts));
        for ($i = 0; $i < $productsToCreate; $i++) {
            $name = $fakerEn->words(3, true) . ' ' . $categoryNames[$i % count($categoryNames)];
            $slug = Str::slug($name);
            
            // Make slug unique if it already exists
            $originalSlug = $slug;
            $counter = 1;
            while (in_array($slug, $existingProductSlugs)) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $product = Product::create([
                'name' => $name,
                'slug' => $slug,
                'description' => $fakerEn->paragraphs(3, true),
                'category_id' => $categories[$i % count($categories)]->id,
                'status' => $faker->randomElement(['active', 'inactive']),
            ]);
            
            $products[] = $product;
            $existingProductSlugs[] = $slug;
        }

        // 6. Seed Variations (20 for each product, multiple per product)
        $variations = [];
        foreach ($products as $product) {
            $numVariations = rand(3, 5); // 3-5 variations per product
            
            for ($i = 0; $i < $numVariations; $i++) {
                $price = $faker->numberBetween(100000, 2000000);
                $salePrice = $faker->optional(0.3)->numberBetween(80000, $price - 10000);
                
                $variation = Variation::create([
                    'product_id' => $product->id,
                    'sku' => strtoupper(Str::random(8)),
                    'price' => $price,
                    'sale_price' => $salePrice,
                    'sale_start' => $salePrice ? $faker->dateTimeBetween('-1 month', '+1 week') : null,
                    'sale_end' => $salePrice ? $faker->dateTimeBetween('+1 week', '+2 months') : null,
                    'stock' => $faker->numberBetween(0, 100),
                ]);
                
                $variations[] = $variation;
                
                // Attach attribute values to variations
                try {
                    // Size
                    $sizeAttrValues = AttributeValue::where('attribute_id', 1)->get();
                    if ($sizeAttrValues->count() > 0) {
                        $variation->attributeValues()->attach($sizeAttrValues->random()->id);
                    }
                    
                    // Color
                    $colorAttrValues = AttributeValue::where('attribute_id', 2)->get();
                    if ($colorAttrValues->count() > 0) {
                        $variation->attributeValues()->attach($colorAttrValues->random()->id);
                    }
                    
                    // Optionally attach more attribute values
                    if (count($attributes) > 2 && rand(0, 1)) {
                        $randomAttribute = $attributes[rand(2, count($attributes) - 1)];
                        $randomAttrValue = AttributeValue::where('attribute_id', $randomAttribute->id)->inRandomOrder()->first();
                        if ($randomAttrValue) {
                            $variation->attributeValues()->attach($randomAttrValue->id);
                        }
                    }
                } catch (\Exception $e) {
                    echo "Error attaching attribute values: " . $e->getMessage() . "\n";
                }
            }
        }

        // 7. Seed ProductImages (20 per product)
        $productImages = [];
        $imagePlaceholders = [
            'products/product1.jpg',
            'products/product2.jpg',
            'products/product3.jpg',
            'products/product4.jpg',
            'products/product5.jpg',
        ];

        // Create dummy image files in storage
        foreach ($imagePlaceholders as $path) {
            if (!Storage::exists($path)) {
                Storage::put($path, 'dummy image content');
            }
        }

        foreach ($products as $product) {
            // Main image
            $mainImagePath = $faker->randomElement($imagePlaceholders);
            $productImages[] = ProductImage::create([
                'product_id' => $product->id,
                'url' => $mainImagePath,
                'is_main' => true,
            ]);
            
            // Additional images
            for ($i = 0; $i < rand(3, 5); $i++) {
                $additionalImagePath = $faker->randomElement($imagePlaceholders);
                $productImages[] = ProductImage::create([
                    'product_id' => $product->id,
                    'url' => $additionalImagePath,
                    'is_main' => false,
                ]);
            }
        }

        // 8. Seed HotProducts (20)
        $hotProducts = [];
        $selectedProducts = $faker->randomElements($products, 20);
        foreach ($selectedProducts as $product) {
            $hotProducts[] = HotProduct::create([
                'product_id' => $product->id,
            ]);
        }

        // 9. Seed Locations (20)
        $locations = [];
        $vietnameseProvinces = [
            'Hà Nội', 'Hồ Chí Minh', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ', 
            'An Giang', 'Bà Rịa - Vũng Tàu', 'Bắc Giang', 'Bắc Kạn', 'Bạc Liêu',
            'Bắc Ninh', 'Bến Tre', 'Bình Định', 'Bình Dương', 'Bình Phước',
            'Bình Thuận', 'Cà Mau', 'Cao Bằng', 'Đắk Lắk', 'Đắk Nông'
        ];

        $vietnameseDistricts = [
            'Ba Đình', 'Hoàn Kiếm', 'Tây Hồ', 'Long Biên', 'Cầu Giấy',
            'Đống Đa', 'Hai Bà Trưng', 'Hoàng Mai', 'Thanh Xuân', 'Hà Đông',
            'Quận 1', 'Quận 2', 'Quận 3', 'Quận 4', 'Quận 5',
            'Quận 6', 'Quận 7', 'Quận 8', 'Quận 9', 'Quận 10'
        ];

        $vietnameseWards = [
            'Phường Phúc Xá', 'Phường Trúc Bạch', 'Phường Vĩnh Phúc', 'Phường Cống Vị', 'Phường Liễu Giai',
            'Phường Nguyễn Trung Trực', 'Phường Quán Thánh', 'Phường Ngọc Hà', 'Phường Điện Biên', 'Phường Đội Cấn',
            'Phường Phúc Tân', 'Phường Đồng Xuân', 'Phường Hàng Mã', 'Phường Hàng Buồm', 'Phường Hàng Đào',
            'Phường Hàng Bồ', 'Phường Hàng Bạc', 'Phường Hàng Gai', 'Phường Chương Dương', 'Phường Hàng Trống'
        ];

        for ($i = 0; $i < 20; $i++) {
            $locations[] = Location::create([
                'user_id' => $users[array_rand($users)]->id,
                'address' => $faker->streetAddress,
                'province' => $vietnameseProvinces[$i],
                'district' => $vietnameseDistricts[$i % count($vietnameseDistricts)],
                'ward' => $vietnameseWards[$i % count($vietnameseWards)],
            ]);
        }

        // 10. Seed Order_statuses (4 trạng thái)
        // Xóa tất cả các trạng thái cũ
        Order_status::query()->delete();
        
        $orderStatuses = [];
        $statusNames = [
            'Chờ xử lý', 'Đang vận chuyển', 'Thành công', 'Đã hủy'
        ];

        // Tạo mới 4 trạng thái với ID từ 1-4
        foreach ($statusNames as $index => $statusName) {
            $status = Order_status::create([
                'id' => $index + 1,
                'status_name' => $statusName,
            ]);
            $orderStatuses[] = $status;
        }

        // 11. Seed Orders (20)
        $orders = [];

        // Xóa các orders hiện tại
        Order::query()->delete();
        
        // Create orders
        for ($i = 0; $i < 20; $i++) {
            $user = $faker->randomElement($users);
            $totalAmount = 0;
            $discountAmount = 0;
            
            // Randomly apply discount
            if ($faker->boolean(30)) {
                $discountAmount = $faker->numberBetween(10000, 100000);
            }
            
            $paymentMethod = $faker->randomElement(['COD', 'Bank Transfer', 'VNPAY', 'Momo', 'Wallet']);
            $paymentStatus = $faker->randomElement(['pending', 'completed', 'failed']);
            
            // Chọn trạng thái ngẫu nhiên
            $statusId = $orderStatuses[$faker->numberBetween(0, count($orderStatuses) - 1)]->id;
            
            // Generate unique order code
            $orderCode = 'ORD' . time() . rand(1000, 9999) . $i;
            
            $order = Order::create([
                'user_id' => $user->id,
                'status_id' => $statusId,
                'order_code' => $orderCode,
                'user_name' => $user->name,
                'user_phone' => $user->phone,
                'user_email' => $user->email,
                'total_amount' => 0, // Will update after adding items
                'shipping_address' => $faker->address,
                'payment_method' => $paymentMethod,
                'discount_code' => $discountAmount > 0 ? strtoupper(Str::random(6)) : null,
                'discount_amount' => $discountAmount,
                'payment_status' => $paymentStatus,
                'vnpay_transaction_no' => $paymentMethod == 'VNPAY' ? Str::random(12) : null,
                'vnpay_payment_date' => $paymentMethod == 'VNPAY' ? $faker->dateTimeThisYear : null,
                'created_at' => $faker->dateTimeThisYear,
                'updated_at' => $faker->dateTimeThisYear,
            ]);
            
            $orders[] = $order;
        }

        // 12. Seed Order_items (Multiple per order)
        Order_item::query()->delete();
        
        $orderItems = [];
        foreach ($orders as $order) {
            $itemCount = $faker->numberBetween(1, 5);
            $orderTotal = 0;
            
            for ($i = 0; $i < $itemCount; $i++) {
                $variation = $faker->randomElement($variations);
                $quantity = $faker->numberBetween(1, 5);
                $price = $variation->sale_price ? $variation->sale_price : $variation->price;
                $itemTotal = $price * $quantity;
                $orderTotal += $itemTotal;
                
                $orderItems[] = Order_item::create([
                    'order_id' => $order->id,
                    'variation_id' => $variation->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);
            }
            
            // Update order total
            $finalTotal = $orderTotal - $order->discount_amount;
            if ($finalTotal < 0) $finalTotal = 0;
            
            $order->update([
                'total_amount' => $finalTotal
            ]);
        }

        // 13. Seed Order_status_times (20)
        Order_status_time::query()->delete();
        
        $orderStatusTimes = [];
        for ($i = 0; $i < 20; $i++) {
            $order = $faker->randomElement($orders);
            $orderStatusTimes[] = Order_status_time::create([
                'order_id' => $order->id,
                'status_id' => $faker->randomElement($orderStatuses)->id,
                'created_at' => $faker->dateTimeBetween($order->created_at, 'now'),
                'updated_at' => now(),
            ]);
        }

        // 14. Seed Order_cancellations
        Order_cancellation::query()->delete();
        
        $cancelledStatus = Order_status::where('status_name', 'Đã hủy')->first();
        $orderCancellations = [];
        $cancellationReasons = [
            'Thay đổi ý định', 'Tìm thấy giá tốt hơn ở nơi khác', 'Đặt nhầm sản phẩm',
            'Thời gian giao hàng quá lâu', 'Sản phẩm không có sẵn', 'Vấn đề thanh toán',
            'Sai kích cỡ', 'Sai màu sắc', 'Lo ngại về chất lượng', 'Nhận xét xấu',
            'Không còn cần thiết', 'Đơn hàng trùng lặp', 'Chi phí vận chuyển quá đắt',
            'Vấn đề địa chỉ giao hàng', 'Vấn đề giao tiếp với người bán',
            'Thiếu tính năng', 'Vấn đề kỹ thuật', 'Thông tin sản phẩm không chính xác',
            'Chi phí phát sinh không mong muốn', 'Lý do khác'
        ];
        
        // Lấy tất cả đơn hàng có trạng thái "Đã hủy"
        $cancelledOrders = Order::where('status_id', $cancelledStatus->id)->get();
        
        foreach ($cancelledOrders as $index => $order) {
            $orderCancellations[] = Order_cancellation::create([
                'order_id' => $order->id,
                'reason' => $cancellationReasons[$index % count($cancellationReasons)],
                'created_at' => $faker->dateTimeBetween($order->created_at, 'now'),
                'updated_at' => now(),
            ]);
        }

        // 15. Seed Discounts (20)
        $discounts = [];
        for ($i = 0; $i < 20; $i++) {
            $startDate = $faker->dateTimeBetween('-1 month', '+1 month');
            $endDate = $faker->dateTimeBetween($startDate, '+3 months');
            
            $discounts[] = Discount::create([
                'code' => strtoupper(Str::random(6)),
                'sale' => $faker->randomElement([10, 15, 20, 25, 30, 40, 50]),
                'startDate' => $startDate,
                'endDate' => $endDate,
                'usageCount' => $faker->numberBetween(0, 100),
                'maxUsage' => $faker->optional(0.7)->numberBetween(100, 1000),
                'minOrderValue' => $faker->optional(0.5)->numberBetween(100000, 500000),
                'maxDiscount' => $faker->optional(0.5)->numberBetween(50000, 200000),
            ]);
        }

        // 16. Seed Carts (20)
        $carts = [];
        for ($i = 0; $i < 20; $i++) {
            $user = $faker->randomElement($users);
            $variation = $faker->randomElement($variations);
            $product = $variation->product;
            
            // Get attribute values
            $colorAttrValue = $variation->attributeValues->where('attribute_id', 2)->first();
            $sizeAttrValue = $variation->attributeValues->where('attribute_id', 1)->first();
            
            $carts[] = Cart::create([
                'user_id' => $user->id,
                'variation_id' => $variation->id,
                'product_name' => $product->name,
                'color' => $colorAttrValue ? $colorAttrValue->value : null,
                'size' => $sizeAttrValue ? $sizeAttrValue->value : null,
                'quantity' => $faker->numberBetween(1, 5),
                'price' => $variation->price,
            ]);
        }

        echo "Database seeded successfully with 20 records for each model!\n";
    }
}
