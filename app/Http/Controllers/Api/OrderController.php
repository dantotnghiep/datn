<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Events\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function cancel($id)
    {
        try {
            DB::beginTransaction();
            
            $order = Order::with(['user', 'status'])
                         ->where('user_id', auth()->id())
                         ->where('id', $id)
                         ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đơn hàng'
                ], 404);
            }

            $order->status_id = 3; // Trạng thái đã hủy
            $order->save();
            
            DB::commit();
            
            // Broadcast event
            broadcast(new OrderStatusUpdated($order))->toOthers();
            
            return response()->json([
                'success' => true,
                'message' => 'Đơn hàng đã được hủy thành công',
                'order' => $order
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        try {
            $request->validate([
                'status_id' => 'required|exists:order_statuses,id'
            ]);

            if ($order->status_id == 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể cập nhật đơn hàng đã bị hủy'
                ], 400);
            }

            DB::beginTransaction();
            
            $order->status_id = $request->status_id;
            $order->save();
            
            $order->load('status');
            
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
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
} 