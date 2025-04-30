<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kiểm tra và chỉ thêm dữ liệu mới nếu bảng còn trống
        if (DB::table('promotions')->count() == 0) {
            // Tạo một số khuyến mãi mẫu
            $promotions = [
                [
                    'code' => 'WELCOME20',
                    'name' => 'Chào mừng thành viên mới',
                    'description' => 'Giảm 20% cho khách hàng mới đăng ký',
                    'discount_type' => 'percentage',
                    'discount_value' => 20,
                    'minimum_spend' => 100000,
                    'maximum_discount' => 500000,
                    'usage_limit' => 1000,
                    'usage_count' => 250,
                    'is_active' => 1,
                    'starts_at' => Carbon::now()->subDays(30),
                    'expires_at' => Carbon::now()->addDays(60),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'code' => 'SUMMER2023',
                    'name' => 'Khuyến mãi hè 2023',
                    'description' => 'Giảm 15% cho tất cả đơn hàng mùa hè',
                    'discount_type' => 'percentage',
                    'discount_value' => 15,
                    'minimum_spend' => 200000,
                    'maximum_discount' => 300000,
                    'usage_limit' => 500,
                    'usage_count' => 120,
                    'is_active' => 1,
                    'starts_at' => Carbon::now()->subDays(15),
                    'expires_at' => Carbon::now()->addDays(45),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'code' => 'FIXED100K',
                    'name' => 'Giảm 100K đơn từ 500K',
                    'description' => 'Giảm 100.000đ cho đơn hàng từ 500.000đ',
                    'discount_type' => 'fixed',
                    'discount_value' => 100000,
                    'minimum_spend' => 500000,
                    'maximum_discount' => 100000,
                    'usage_limit' => 200,
                    'usage_count' => 85,
                    'is_active' => 1,
                    'starts_at' => Carbon::now()->subDays(5),
                    'expires_at' => Carbon::now()->addDays(25),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'code' => 'FREESHIP',
                    'name' => 'Miễn phí vận chuyển',
                    'description' => 'Giảm phí vận chuyển cho đơn hàng từ 300.000đ',
                    'discount_type' => 'fixed',
                    'discount_value' => 50000,
                    'minimum_spend' => 300000,
                    'maximum_discount' => 50000,
                    'usage_limit' => 300,
                    'usage_count' => 130,
                    'is_active' => 1,
                    'starts_at' => Carbon::now()->subDays(10),
                    'expires_at' => Carbon::now()->addDays(20),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'code' => 'FLASH25',
                    'name' => 'Flash Sale 25%',
                    'description' => 'Giảm 25% flash sale trong ngày',
                    'discount_type' => 'percentage',
                    'discount_value' => 25,
                    'minimum_spend' => 150000,
                    'maximum_discount' => 200000,
                    'usage_limit' => 100,
                    'usage_count' => 42,
                    'is_active' => 1,
                    'starts_at' => Carbon::now(),
                    'expires_at' => Carbon::now()->addDays(1),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            ];

            // Chèn dữ liệu vào bảng
            DB::table('promotions')->insert($promotions);
        }
    }
}
