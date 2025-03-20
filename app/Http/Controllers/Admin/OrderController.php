<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Order_status;
use Illuminate\Http\Request;
use App\Events\OrderStatusUpdated;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'status'])->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items.variation.product', 'status', 'user']);
        $statuses = Order_status::all();
        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        try {
            $request->validate([
                'status_id' => 'required|exists:order_statuses,id'
            ]);

            // Kiểm tra nếu đơn hàng đã bị hủy
            if ($order->status_id == 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể cập nhật đơn hàng đã bị hủy'
                ], 400);
            }

            DB::beginTransaction();
            
            $order->status_id = $request->status_id;
            $order->save();
            
            // Load relationship status
            $order->load('status');
            
            DB::commit();
            
            // Broadcast event
            broadcast(new OrderStatusUpdated($order))->toOthers();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cập nhật trạng thái thành công',
                    'order' => [
                        'id' => $order->id,
                        'status_id' => $order->status_id,
                        'status' => [
                            'status_name' => $order->status->status_name
                        ]
                    ]
                ]);
            }

            return redirect()->back()->with('success', 'Cập nhật trạng thái thành công');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật trạng thái');
        }
    }
}