<?php

namespace App\Http\Controllers\Client;

use App\Events\OrderCancellationRequestCreated;
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
            
            // Chỉ cho phép gửi yêu cầu khi chưa ở trạng thái 2, 4, 5 và chưa có yêu cầu hủy
            if (in_array($order->status_id, [2, 4, 5]) || $order->cancellation) {
                Log::warning('Client OrderController@cancelRequest - Cannot cancel order', [
                    'order_id' => $order->id,
                    'status_id' => $order->status_id,
                    'has_cancellation' => $order->cancellation ? 'yes' : 'no'
                ]);
                return back()->with('error', 'Không thể gửi yêu cầu hủy cho đơn hàng này.');
            }
            
            // Tạo yêu cầu hủy
            $cancellation = $order->cancellation()->create([
                'user_id' => auth()->id(),
                'reason' => 'Khách hàng yêu cầu hủy',
                'status' => 'pending'
            ]);
            
            Log::info('Client OrderController@cancelRequest - Cancellation created', [
                'order_id' => $order->id,
                'cancellation_id' => $cancellation->id
            ]);
            
            // Kích hoạt sự kiện real-time
            try {
                event(new OrderCancellationRequestCreated($cancellation));
                Log::info('Client OrderController@cancelRequest - Event dispatched');
            } catch (\Exception $eventError) {
                Log::error('Client OrderController@cancelRequest - Error dispatching event', [
                    'error' => $eventError->getMessage(),
                    'trace' => $eventError->getTraceAsString()
                ]);
            }
            
            return back()->with('success', 'Đã gửi yêu cầu hủy đơn hàng. Vui lòng chờ xác nhận.');
            
        } catch (\Exception $e) {
            Log::error('Client OrderController@cancelRequest - Error', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Đã xảy ra lỗi khi gửi yêu cầu hủy đơn hàng. Vui lòng thử lại sau.');
        }
    }
} 