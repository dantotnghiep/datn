<?php

namespace App\Http\Controllers\Client;

use App\Events\OrderStatusChanged;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderCancellation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('client.order.list', compact('orders'));
    }

    public function show(Order $order)
    {
        // Kiểm tra xem đơn hàng có thuộc về người dùng hiện tại không
        if ($order->getRawOriginal('user_id') !== auth()->id()) {
            abort(403);
        }

        return view('client.order.detail', compact('order'));
    }

    public function cancelRequest(Order $order)
    {
        try {
            // Ghi log để debug
            Log::info('Client OrderController@cancelRequest - Start', [
                'order_id' => $order->id,
                'user_id' => auth()->id()
            ]);
            
            if ($order->getRawOriginal('user_id') !== auth()->id()) {
                Log::warning('Client OrderController@cancelRequest - Unauthorized access', [
                    'order_id' => $order->id,
                    'user_id' => auth()->id()
                ]);
                abort(403);
            }
            
            // Chỉ cho phép hủy khi chưa ở trạng thái 2, 4, 5
            if (in_array($order->status_id, [2, 4, 5])) {
                Log::warning('Client OrderController@cancelRequest - Cannot cancel order', [
                    'order_id' => $order->id,
                    'status_id' => $order->status_id
                ]);
                return back()->with('error', 'Không thể hủy đơn hàng này.');
            }
            
            // Cập nhật trạng thái đơn hàng thành "Đã hủy" (status_id = 4)
            $order->status_id = 4;
            $order->save();
            
            Log::info('Client OrderController@cancelRequest - Order cancelled', [
                'order_id' => $order->id,
                'new_status_id' => 4
            ]);
            
            // Kích hoạt sự kiện real-time
            try {
                event(new OrderStatusChanged($order));
                Log::info('Client OrderController@cancelRequest - Status change event dispatched');
            } catch (\Exception $eventError) {
                Log::error('Client OrderController@cancelRequest - Error dispatching event', [
                    'error' => $eventError->getMessage(),
                    'trace' => $eventError->getTraceAsString()
                ]);
            }
            
            return back()->with('success', 'Đơn hàng đã bị hủy thành công.');
            
        } catch (\Exception $e) {
            Log::error('Client OrderController@cancelRequest - Error', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Đã xảy ra lỗi khi hủy đơn hàng. Vui lòng thử lại sau.');
        }
    }
} 