<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Events\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    // Phương thức hiển thị trang orders
    public function index()
    {
        $orders = Order::with('status')
                      ->where('user_id', Auth::id())
                      ->orderBy('created_at', 'desc')
                      ->get();

        return view('client.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['orderItems.product', 'status'])
                     ->where('user_id', Auth::id())
                     ->findOrFail($id);

        return view('client.orders.show', compact('order'));
    }

    // Phương thức hiển thị trang checkout
    public function checkout()
    {
        $user = Auth::user();
        return view('client.cart.order');
    }

    // Phương thức cập nhật trạng thái đơn hàng
    public function updateStatus(Request $request, $orderId)
    {
        try {
            DB::beginTransaction();
            
            $order = Order::with(['status', 'user'])->findOrFail($orderId);
            $newStatusId = $request->status_id;

            // Kiểm tra quyền cập nhật
            if (!$this->canUpdateStatus($order, $newStatusId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể cập nhật trạng thái này'
                ], 403);
            }

            // Cập nhật trạng thái
            $order->status_id = $newStatusId;
            $order->save();
            
            // Load lại relationship để đảm bảo dữ liệu mới nhất
            $order->load(['status', 'user']);
            
            DB::commit();
            
            // Broadcast event
            broadcast(new OrderStatusUpdated($order))->toOthers();
            
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công',
                'order' => $order
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating order status: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái'
            ], 500);
        }
    }

    /**
     * Kiểm tra quyền cập nhật trạng thái
     */
    private function canUpdateStatus($order, $newStatusId)
    {
        // Nếu là admin
        if (Auth::user()->is_admin) {
            // Admin chỉ có thể xác nhận (status 2) đơn hàng đang chờ xác nhận (status 1)
            // Hoặc hoàn thành (status 4) đơn hàng đang giao (status 2)
            if (($order->status_id == 1 && $newStatusId == 2) || 
                ($order->status_id == 2 && $newStatusId == 4)) {
                return true;
            }
        }
        // Nếu là khách hàng
        else {
            // Khách hàng chỉ có thể hủy (status 3) đơn hàng đang chờ xác nhận (status 1)
            if ($order->user_id == Auth::id() && 
                $order->status_id == 1 && 
                $newStatusId == 4) {
                return true;
            }
        }

        return false;
    }

    /**
     * Lấy tên trạng thái theo ID
     */
    private function getStatusName($statusId)
    {
        $statuses = [
            1 => 'Chờ xác nhận',
            2 => 'Đang giao',
            3 => 'Đã hủy',
            4 => 'Hoàn thành'
        ];

        return $statuses[$statusId] ?? 'Không xác định';
    }

    // Phương thức hủy đơn hàng
    public function cancel($id)
    {
        try {
            DB::beginTransaction();
            
            // Tìm đơn hàng của người dùng hiện tại
            $order = Order::with(['status', 'user', 'items.variation'])
                         ->where('id', $id)
                         ->first();
            
            // Kiểm tra nếu đơn hàng không tồn tại
            if (!$order) {
                \Log::error('Order not found: ID ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng không tồn tại'
                ], 404);
            }
            
            // Kiểm tra quyền hủy đơn hàng
            if (Auth::id() != $order->user_id && !Auth::user()->is_admin) {
                \Log::warning('Unauthorized order cancellation attempt: User ' . Auth::id() . ' for Order ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền hủy đơn hàng này'
                ], 403);
            }
            
            // Kiểm tra trạng thái đơn hàng
            if ($order->status_id != 1 && $order->status_id != 2) {
                \Log::warning('Attempt to cancel order with invalid status: Order ' . $id . ' Status ' . $order->status_id);
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ có thể hủy đơn hàng ở trạng thái chờ xác nhận hoặc đang vận chuyển'
                ], 400);
            }

            // Hoàn lại số lượng sản phẩm vào kho
            foreach ($order->items as $item) {
                if ($item->variation) {
                    $item->variation->stock += $item->quantity;
                    $item->variation->save();
                } else {
                    \Log::warning('Variation not found for order item ID ' . $item->id);
                }
            }

            // Cập nhật trạng thái hủy (3)
            $order->status_id = 3; // Trạng thái hủy là 3, không phải 4
            $order->save();
            
            // Load lại relationship để đảm bảo dữ liệu mới nhất
            $order->load(['status', 'user']);
            
            DB::commit();
            
            // Broadcast event
            try {
                broadcast(new OrderStatusUpdated($order))->toOthers();
                \Log::info('Order cancelled successfully: ID ' . $id);
            } catch (\Exception $e) {
                \Log::error('Failed to broadcast order cancellation: ' . $e->getMessage());
                // Continue execution even if broadcasting fails
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Đơn hàng đã được hủy thành công và số lượng sản phẩm đã được hoàn lại',
                'order' => $order
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error cancelling order: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi hủy đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }
}