<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Đang xử lý',
                'description' => 'Đơn hàng đang được xác nhận và xử lý',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Đã xác nhận',
                'description' => 'Đơn hàng đã được xác nhận',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Đang chuẩn bị hàng',
                'description' => 'Đơn hàng đang được chuẩn bị để giao',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Đang giao hàng',
                'description' => 'Đơn hàng đang được giao',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Đã giao hàng',
                'description' => 'Đơn hàng đã được giao thành công',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Đã hủy',
                'description' => 'Đơn hàng đã bị hủy',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hoàn trả',
                'description' => 'Đơn hàng đang trong quá trình hoàn trả',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('order_status')->insert($statuses);
    }
} 