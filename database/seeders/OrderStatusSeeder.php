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
                'name' => 'Pending',
                'description' => 'Đơn hàng đang được xác nhận và xử lý',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Completed',
                'description' => 'Đơn hàng đã được xác nhận',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Shipping',
                'description' => 'Đơn hàng đang được giao',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cancelled',
                'description' => 'Đơn hàng đã bị hủy',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Refunded',
                'description' => 'Đơn hàng đang trong quá trình hoàn trả',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('order_status')->insert($statuses);
    }
} 