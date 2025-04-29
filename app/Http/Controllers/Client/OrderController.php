<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

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
        if ($order->getRawOriginal('user_id') !== auth()->id()) {
            abort(403);
        }
        // Chỉ cho phép gửi yêu cầu khi chưa ở trạng thái 2, 4, 5 và chưa có yêu cầu hủy
        if (in_array($order->status_id, [2, 4, 5]) || $order->cancellation) {
            return back()->with('error', 'Không thể gửi yêu cầu hủy cho đơn hàng này.');
        }
        // Tạo yêu cầu hủy (giả sử có model OrderCancellation)
        $order->cancellation()->create([
            'user_id' => auth()->id(),
            'reason' => 'Khách hàng yêu cầu hủy',
            'status' => 'pending'
        ]);
        return back()->with('success', 'Đã gửi yêu cầu hủy đơn hàng. Vui lòng chờ xác nhận.');
    }
} 