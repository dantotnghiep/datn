<?php

namespace App\Console\Commands;

use App\Services\OrderProcessingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessPendingOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:process-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all pending orders in the queue';

    /**
     * Execute the console command.
     */
    public function handle(OrderProcessingService $orderProcessingService)
    {
        $this->info('Starting to process pending orders...');
        
        $pendingOrders = session()->get('pending_orders', []);
        
        if (empty($pendingOrders)) {
            $this->info('No pending orders to process.');
            return;
        }
        
        $this->info('Found ' . count($pendingOrders) . ' pending orders.');
        
        $successCount = 0;
        $failureCount = 0;
        
        foreach ($pendingOrders as $key => $orderData) {
            $this->info("Processing order {$key}...");
            
            try {
                $result = $orderProcessingService->processOrder(
                    $orderData['orderData'],
                    $orderData['cartItems'],
                    $orderData['userId']
                );
                
                // Xóa đơn hàng khỏi hàng đợi
                session()->forget("pending_orders.{$key}");
                
                if ($result['success']) {
                    $this->info("Order {$key} processed successfully. Order ID: " . ($result['order_id'] ?? 'N/A'));
                    $successCount++;
                } else {
                    $this->error("Failed to process order {$key}: " . $result['message']);
                    $failureCount++;
                    
                    // Log lỗi
                    Log::error("Failed to process queued order {$key}", [
                        'error' => $result['error'] ?? 'Unknown error',
                        'user_id' => $orderData['userId'] ?? null
                    ]);
                }
            } catch (\Exception $e) {
                $this->error("Exception while processing order {$key}: " . $e->getMessage());
                $failureCount++;
                
                Log::error("Exception processing queued order {$key}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'user_id' => $orderData['userId'] ?? null
                ]);
            }
        }
        
        $this->info("Processed {$successCount} orders successfully, {$failureCount} failures.");
    }
} 