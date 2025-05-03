<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderRefund;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderRefundSeederCancelled extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find cancelled orders with bank payment method
        $orders = Order::where('status_id', 4)
            ->where('payment_method', 'bank')
            ->get();

        if ($orders->isEmpty()) {
            $this->command->info('No cancelled bank payment orders found. Creating a test order...');

            // Check if we have any bank payment orders to update to cancelled
            $bankOrders = Order::where('payment_method', 'bank')->get();

            if (!$bankOrders->isEmpty()) {
                $order = $bankOrders->first();
                $order->status_id = 4; // Set to cancelled
                $order->save();

                $this->command->info("Updated order {$order->order_number} to cancelled status.");
            } else {
                $this->command->error("No bank payment orders found at all. Cannot create test data.");
                return;
            }
        } else {
            $order = $orders->first();
        }

        // Check if refund already exists for this order
        $existingRefund = OrderRefund::where('order_id', $order->id)->first();

        if ($existingRefund) {
            $this->command->info("Updating existing refund for order {$order->order_number}");

            // Update the existing refund
            $existingRefund->update([
                'status' => 'approved',
                'refund_status' => 'pending',
                'is_active' => 1
            ]);
        } else {
            // Create a refund request for the order
            OrderRefund::create([
                'order_id' => $order->id,
                'user_id' => 1, // Assuming admin user with ID 1
                'amount' => $order->total_with_discount ?? 19000.00,
                'reason' => 'Hoàn tiền theo yêu cầu khách hàng',
                'status' => 'approved',
                'refund_status' => 'pending',
                'bank' => 'ACB',
                'bank_number' => '12345678',
                'bank_name' => 'Nguyen Van A',
                'notes' => 'Refund request created by seeder',
                'is_active' => 1
            ]);

            $this->command->info("Created refund request for order {$order->order_number}");
        }
    }
}
