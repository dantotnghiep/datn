<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users
        $users = DB::table('users')->get();
        if ($users->isEmpty()) {
            // If no users exist, create at least one for testing
            $userId = DB::table('users')->insertGetId([
                'name' => 'Khách hàng Test',
                'email' => 'customer@example.com',
                'password' => bcrypt('password'),
                'phone' => '0987654321',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $users = DB::table('users')->get();
        }
        
        // Get order statuses
        $statuses = DB::table('order_status')->get();
        
        // Create 20 sample orders
        for ($i = 1; $i <= 20; $i++) {
            $user = $users->random();
            $status = $statuses->random();
            
            // Generate order
            $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT);
            $discount = rand(0, 15) * 10000; // 0 - 150,000 VND
            
            // Insert order record
            $orderId = DB::table('orders')->insertGetId([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'status_id' => $status->id,
                'user_name' => $user->name,
                'user_phone' => $user->phone ?? '0912345678',
                'province' => 'Hà Nội',
                'district' => 'Cầu Giấy',
                'ward' => 'Dịch Vọng',
                'address' => 'Số 123 Đường ABC',
                'discount' => $discount,
                'total' => 0, // Will update after adding items
                'total_with_discount' => 0, // Will update after adding items
                'notes' => rand(0, 1) ? 'Giao hàng ngoài giờ hành chính' : null,
                'payment_method' => rand(0, 1) ? 'bank' : 'cod',
                'payment_status' => rand(0, 2) < 2 ? 'pending' : 'completed',
                'paid_at' => rand(0, 2) < 2 ? null : now(),
                'created_at' => now()->subDays(rand(0, 30)),
                'updated_at' => now(),
            ]);
            
            // Get random product variations for order items
            $variations = DB::table('product_variations')
                ->inRandomOrder()
                ->limit(rand(1, 5))
                ->get();
                
            $orderTotal = 0;
            
            // Add order items
            foreach ($variations as $variation) {
                $quantity = rand(1, 3);
                $price = $variation->sale_price ?? $variation->price;
                $itemTotal = $price * $quantity;
                $orderTotal += $itemTotal;
                
                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'product_variation_id' => $variation->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $itemTotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Update order totals
            $totalWithDiscount = max(0, $orderTotal - $discount);
            DB::table('orders')
                ->where('id', $orderId)
                ->update([
                    'total' => $orderTotal,
                    'total_with_discount' => $totalWithDiscount,
                ]);
                
            // Add order status history
            DB::table('order_status_history')->insert([
                'order_id' => $orderId,
                'status_id' => $status->id,
                'notes' => 'Cập nhật trạng thái đơn hàng',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 