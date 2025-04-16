<?php

namespace App\Jobs;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Variation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderData;
    protected $cartItems;
    protected $userId;

    public function __construct(array $orderData, array $cartItems, int $userId)
    {
        $this->orderData = $orderData;
        $this->cartItems = $cartItems;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Kiểm tra số lượng tồn kho trước khi xử lý đơn hàng
            Log::info('Starting stock validation', ['user_id' => $this->userId]);
            
            $stockValidation = $this->validateStock();
            if (!$stockValidation['valid']) {
                $errorMsg = "Không thể đặt hàng do không đủ số lượng trong kho:\n" . implode("\n", $stockValidation['errors']);
                Log::error('Stock validation failed', ['errors' => $stockValidation['errors']]);
                throw new \Exception($errorMsg);
            }
            
            Log::info('Stock validation passed, starting order creation');
            
            DB::beginTransaction();

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => $this->userId,
                'user_name' => $this->orderData['user_name'],
                'user_phone' => $this->orderData['user_phone'],
                'user_email' => $this->orderData['user_email'],
                'shipping_address' => $this->orderData['shipping_address'],
                'payment_method' => $this->orderData['payment_method'],
                'total_amount' => $this->orderData['total_amount'],
                'status_id' => 1,
                'payment_status' => $this->orderData['payment_status'] ?? 'pending',
            ]);

            Log::info('Order created', ['order_id' => $order->id]);

            // Nếu là thanh toán VNPay, thêm thông tin giao dịch
            if (isset($this->orderData['vnpay_transaction_no'])) {
                $order->vnpay_transaction_no = $this->orderData['vnpay_transaction_no'];
                $order->vnpay_payment_date = $this->orderData['vnpay_payment_date'];
                $order->save();
            }

            // Tạo các item cho đơn hàng và trừ số lượng tồn kho
            foreach ($this->cartItems as $item) {
                // Tạo order item
                Order_item::create([
                    'order_id' => $order->id,
                    'variation_id' => $item['variation_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
                
                // Trừ số lượng tồn kho
                $variation = Variation::find($item['variation_id']);
                if ($variation) {
                    $variation->stock -= $item['quantity'];
                    $variation->save();
                }
            }

            // Xóa giỏ hàng
            Cart::where('user_id', $this->userId)->delete();

            DB::commit();
            
            Log::info("Order #{$order->id} processed successfully via queue");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to process order via queue: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => $this->userId
            ]);
            throw $e;
        }
    }
    
    /**
     * Kiểm tra số lượng tồn kho cho từng sản phẩm trong đơn hàng
     * Trả về mảng kết quả thay vì throw exception
     */
    private function validateStock(): array
    {
        $outOfStockItems = [];
        
        try {
            // Lấy danh sách IDs của variations
            $variationIds = array_column($this->cartItems, 'variation_id');
            
            Log::info('Checking stock for variations', ['variation_ids' => $variationIds]);
            
            // Lấy các variation từ database
            $variations = Variation::whereIn('id', $variationIds)->get()->keyBy('id');
            
            foreach ($this->cartItems as $item) {
                $variationId = $item['variation_id'];
                $variation = $variations->get($variationId);
                
                if (!$variation) {
                    $outOfStockItems[] = "Sản phẩm không tồn tại (ID: {$variationId})";
                    Log::warning('Variation not found', ['variation_id' => $variationId]);
                    continue;
                }
                
                Log::info('Checking stock for variation', [
                    'variation_id' => $variationId,
                    'requested' => $item['quantity'],
                    'available' => $variation->stock
                ]);
                
                if ($variation->stock < $item['quantity']) {
                    // Tìm tên sản phẩm nếu có
                    $productName = "ID: " . $variation->product_id;
                    
                    try {
                        // Tải thông tin sản phẩm
                        $variation->load('product');
                        if ($variation->product) {
                            $productName = $variation->product->name;
                        }
                    } catch (\Exception $e) {
                        Log::warning('Error loading product relation', [
                            'variation_id' => $variationId,
                            'error' => $e->getMessage()
                        ]);
                    }
                    
                    $outOfStockItems[] = "Sản phẩm " . $productName . 
                        " - Biến thể: {$variation->id} (Yêu cầu: {$item['quantity']}, Kho: {$variation->stock})";
                }
            }
        } catch (\Exception $e) {
            Log::error('Error in stock validation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $outOfStockItems[] = "Lỗi khi kiểm tra tồn kho: " . $e->getMessage();
        }
        
        return [
            'valid' => empty($outOfStockItems),
            'errors' => $outOfStockItems
        ];
    }
} 