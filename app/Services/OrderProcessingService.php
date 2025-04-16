<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Variation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderProcessingService
{
    /**
     * Tạo và xử lý đơn hàng
     *
     * @param array $orderData Thông tin đơn hàng
     * @param array $cartItems Mảng các cart item
     * @param int $userId ID của người dùng
     * @return array Kết quả xử lý đơn hàng
     */
    public function processOrder(array $orderData, array $cartItems, int $userId): array
    {
        // Validate stock trước
        $stockValidation = $this->validateStock($cartItems);
        if (!$stockValidation['valid']) {
            return [
                'success' => false,
                'message' => 'Không thể đặt hàng do không đủ số lượng trong kho',
                'errors' => $stockValidation['errors']
            ];
        }

        // Nếu stock hợp lệ, xử lý đơn hàng
        try {
            DB::beginTransaction();

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => $userId,
                'user_name' => $orderData['user_name'],
                'user_phone' => $orderData['user_phone'],
                'user_email' => $orderData['user_email'],
                'shipping_address' => $orderData['shipping_address'],
                'payment_method' => $orderData['payment_method'],
                'total_amount' => $orderData['total_amount'],
                'status_id' => 1,
                'payment_status' => $orderData['payment_status'] ?? 'pending',
            ]);

            // Nếu là thanh toán VNPay, thêm thông tin giao dịch
            if (isset($orderData['vnpay_transaction_no'])) {
                $order->vnpay_transaction_no = $orderData['vnpay_transaction_no'];
                $order->vnpay_payment_date = $orderData['vnpay_payment_date'];
                $order->save();
            }

            // Tạo các item cho đơn hàng và trừ số lượng tồn kho
            foreach ($cartItems as $item) {
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
            Cart::where('user_id', $userId)->delete();

            DB::commit();
            
            Log::info("Order #{$order->id} processed successfully");
            
            return [
                'success' => true,
                'message' => 'Đơn hàng đã được xử lý thành công!',
                'order_id' => $order->id
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to process order: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => $userId
            ]);
            
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý đơn hàng: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Kiểm tra số lượng tồn kho cho từng sản phẩm trong đơn hàng
     * Trả về mảng kết quả
     */
    public function validateStock(array $cartItems): array
    {
        $outOfStockItems = [];
        
        try {
            // Lấy danh sách IDs của variations
            $variationIds = array_column($cartItems, 'variation_id');
            
            // Lấy các variation từ database với eager loading sản phẩm
            $variations = Variation::with('product')->whereIn('id', $variationIds)->get()->keyBy('id');
            
            foreach ($cartItems as $item) {
                $variationId = $item['variation_id'];
                $variation = $variations->get($variationId);
                
                if (!$variation) {
                    $outOfStockItems[] = "Sản phẩm không tồn tại (ID: {$variationId})";
                    continue;
                }
                
                if ($variation->stock < $item['quantity']) {
                    // Lấy tên sản phẩm
                    $productName = $variation->product ? $variation->product->name : "ID: " . $variation->product_id;
                    
                    // Tạo thông báo đơn giản
                    if ($variation->stock > 0) {
                        $outOfStockItems[] = "<strong>{$productName}</strong> chỉ còn {$variation->stock} sản phẩm";
                    } else {
                        $outOfStockItems[] = "<strong>{$productName}</strong> đã hết hàng";
                    }
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

    /**
     * Xử lý đơn hàng theo kiểu queue (async)
     * Đưa vào hàng đợi để xử lý sau (giả lập queue)
     * 
     * @param array $orderData Thông tin đơn hàng
     * @param array $cartItems Mảng các cart item
     * @param int $userId ID của người dùng
     * @return bool Kết quả việc thêm vào queue
     */
    public function queueOrderProcessing(array $orderData, array $cartItems, int $userId): bool
    {
        // Validate stock trước khi đưa vào queue
        $stockValidation = $this->validateStock($cartItems);
        if (!$stockValidation['valid']) {
            Log::error('Cannot queue order processing - insufficient stock', [
                'errors' => $stockValidation['errors']
            ]);
            return false;
        }

        // Giả lập hành vi queue bằng cách chạy xử lý trong session mới
        try {
            // Trong thực tế, ở đây sẽ lưu thông tin vào bảng tạm hoặc file
            // Hoặc sử dụng một service thứ 3 như Redis, RabbitMQ, etc.
            // Nhưng vì yêu cầu không tạo bảng mới, chúng ta sẽ giả lập bằng session
            
            session()->put('pending_orders.' . time(), [
                'orderData' => $orderData,
                'cartItems' => $cartItems,
                'userId' => $userId,
                'created_at' => now()->toDateTimeString()
            ]);
            
            // Chạy xử lý ngay lập tức (trong thực tế sẽ để worker xử lý)
            // Nhưng ở đây ta kích hoạt nó ngay lập tức
            $this->processNextPendingOrder();
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to queue order processing', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
    
    /**
     * Xử lý đơn hàng tiếp theo trong hàng đợi
     * (Giả lập worker của queue system)
     */
    private function processNextPendingOrder()
    {
        $pendingOrders = session()->get('pending_orders', []);
        
        if (empty($pendingOrders)) {
            return;
        }
        
        // Lấy đơn hàng đầu tiên (FIFO)
        $orderKey = array_key_first($pendingOrders);
        $orderData = $pendingOrders[$orderKey];
        
        // Xóa đơn hàng khỏi hàng đợi
        session()->forget('pending_orders.' . $orderKey);
        
        // Xử lý đơn hàng bất đồng bộ
        // Trong thực tế, đây sẽ là một job riêng biệt
        // Nhưng ở đây ta xử lý ngay
        $result = $this->processOrder(
            $orderData['orderData'],
            $orderData['cartItems'],
            $orderData['userId']
        );
        
        // Log kết quả
        if ($result['success']) {
            Log::info('Queued order processed successfully', [
                'order_id' => $result['order_id'] ?? null
            ]);
        } else {
            Log::error('Failed to process queued order', [
                'error' => $result['error'] ?? 'Unknown error'
            ]);
        }
    }
} 