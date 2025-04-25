<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Variation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\CartItem;

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

            // Format cart items for order line validation
            $orderItems = [];
            foreach ($cartItems as $item) {
                $orderItems[] = [
                    'variation_id' => $item['variation_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ];
            }

            // Tạo đơn hàng
            $order = $this->createOrder($orderData, $userId);
            
            // Tạo các item cho đơn hàng và trừ số lượng tồn kho
            $this->createOrderItems($order, $orderItems, true);

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

    /**
     * Process order without checking stock (for VNPay after successful payment)
     * 
     * @param array $orderData Order information
     * @param array $cartItems Cart items to process
     * @param int|null $userId User ID (optional)
     * @return array Result with success status and message
     */
    public function processOrderWithoutStockCheck(array $orderData, array $cartItems, ?int $userId = null): array
    {
        try {
            \DB::beginTransaction();
            
            // Format cart items for order line validation
            $orderItems = [];
            foreach ($cartItems as $item) {
                $orderItems[] = [
                    'variation_id' => $item['variation_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ];
            }
            
            // Create order record
            $order = $this->createOrder($orderData, $userId);
            
            // Create order items without updating stock
            $this->createOrderItems($order, $orderItems, false);
            
            // Xóa cart sau khi đặt hàng thành công
            if ($userId) {
                $cart = Cart::where('user_id', $userId)->first();
                if ($cart) {
                    // Xóa tất cả các cart items
                    CartItem::where('cart_id', $cart->id)->delete();
                }
            }
            
            \DB::commit();
            
            // Gửi email thông báo
            try {
                // Thêm phần gửi email thông báo ở đây nếu cần
            } catch (\Exception $e) {
                \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
            }
            
            return [
                'success' => true,
                'message' => 'Đặt hàng thành công. Cảm ơn bạn đã mua hàng!',
                'order_id' => $order->id
            ];
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Order processing failed (without stock check): ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý đơn hàng: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Helper method to create order
     * 
     * @param array $orderData Order information
     * @param int|null $userId User ID
     * @return Order Created order instance
     */
    private function createOrder(array $orderData, ?int $userId): Order
    {
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

        return $order;
    }

    /**
     * Helper method to create order items
     * 
     * @param Order $order Order instance
     * @param array $orderItems Array of order items
     * @param bool $updateStock Whether to update stock or not
     * @return void
     */
    private function createOrderItems(Order $order, array $orderItems, bool $updateStock = true): void
    {
        foreach ($orderItems as $item) {
            // Tạo order item
            Order_item::create([
                'order_id' => $order->id,
                'variation_id' => $item['variation_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
            
            // Trừ số lượng tồn kho nếu yêu cầu
            if ($updateStock) {
                $variation = Variation::find($item['variation_id']);
                if ($variation) {
                    $variation->stock -= $item['quantity'];
                    $variation->save();
                }
            }
        }
    }
} 