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

    public function cancelRequest(Order $order, Request $request)
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

            // Kiểm tra lý do hủy đơn hàng
            $request->validate([
                'reason' => 'required|string|max:255'
            ], [
                'reason.required' => 'Vui lòng nhập lý do hủy đơn hàng',
                'reason.max' => 'Lý do hủy đơn hàng không được vượt quá 255 ký tự'
            ]);
            
            // Lưu thông tin hủy đơn hàng
            OrderCancellation::create([
                'order_id' => $order->id,
                'reason' => $request->reason
            ]);
            
            // Cập nhật trạng thái đơn hàng thành "Đã hủy" (status_id = 4)
            $order->status_id = 4;
            $order->save();
            
            Log::info('Client OrderController@cancelRequest - Order cancelled', [
                'order_id' => $order->id,
                'new_status_id' => 4,
                'reason' => $request->reason
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
            
            // Nếu đơn hàng thanh toán bằng bank, chuyển hướng với session flag để hiển thị modal hoàn tiền
            if ($order->payment_method == 'bank' && $order->payment_status == 'completed') {
                return back()->with([
                    'success' => 'Đơn hàng đã bị hủy thành công.',
                    'show_refund_modal' => true,
                    'order_id' => $order->id
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

    /**
     * Hiển thị form hủy đơn hàng
     */
    public function showCancelForm(Order $order)
    {
        // Kiểm tra xem đơn hàng có thuộc về người dùng hiện tại không
        if ($order->getRawOriginal('user_id') !== auth()->id()) {
            abort(403);
        }

        // Kiểm tra xem đơn hàng có thể hủy không
        if (in_array($order->status_id, [2, 4, 5])) {
            return back()->with('error', 'Không thể hủy đơn hàng này.');
        }

        return view('client.order.cancel-form', compact('order'));
    }

    /**
     * Xử lý yêu cầu hoàn tiền từ khách hàng
     */
    public function requestRefund(Order $order, Request $request)
    {
        try {
            // Kiểm tra xem đơn hàng có thuộc về người dùng hiện tại không
            if ($order->getRawOriginal('user_id') !== auth()->id()) {
                return back()->with('error', 'Bạn không có quyền yêu cầu hoàn tiền cho đơn hàng này.');
            }
            
            // Kiểm tra trạng thái đơn hàng (chỉ đơn hàng đã hủy mới được hoàn tiền)
            if ($order->status_id != 4) {
                return back()->with('error', 'Chỉ những đơn hàng đã hủy mới có thể yêu cầu hoàn tiền.');
            }
            
            // Kiểm tra xem phương thức thanh toán có phải là bank không
            if ($order->payment_method != 'bank') {
                return back()->with('error', 'Chỉ đơn hàng thanh toán bằng chuyển khoản mới được yêu cầu hoàn tiền.');
            }
            
            // Xác thực dữ liệu gửi lên
            $request->validate([
                'bank' => 'required|string|max:100',
                'bank_number' => 'required|string|max:50',
                'bank_name' => 'required|string|max:100',
                'notes' => 'nullable|string',
            ], [
                'bank.required' => 'Vui lòng nhập tên ngân hàng',
                'bank_number.required' => 'Vui lòng nhập số tài khoản',
                'bank_name.required' => 'Vui lòng nhập tên chủ tài khoản',
            ]);
            
            // Kiểm tra xem đã có yêu cầu hoàn tiền nào chưa
            if ($order->refunds()->exists()) {
                return back()->with('error', 'Đơn hàng này đã có yêu cầu hoàn tiền.');
            }
            
            // Tạo yêu cầu hoàn tiền
            $refund = $order->refunds()->create([
                'user_id' => auth()->id(), // Người dùng yêu cầu hoàn tiền
                'amount' => $order->total_with_discount, // Hoàn toàn bộ số tiền đã thanh toán
                'refund_status' => 'pending',
                'reason' => 'Hoàn tiền sau khi hủy đơn hàng',
                'bank' => $request->bank,
                'bank_number' => $request->bank_number,
                'bank_name' => $request->bank_name,
                'notes' => $request->notes,
                'is_active' => true
            ]);
            
            // Lưu log
            Log::info('Client OrderController@requestRefund - Refund requested', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'refund_id' => $refund->id,
                'amount' => $order->total_with_discount,
                'user_id' => auth()->id()
            ]);
            
            return back()->with('success', 'Yêu cầu hoàn tiền đã được gửi. Chúng tôi sẽ xử lý trong thời gian sớm nhất.');
            
        } catch (\Exception $e) {
            Log::error('Client OrderController@requestRefund - Error', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Đã xảy ra lỗi khi yêu cầu hoàn tiền. Vui lòng thử lại sau.');
        }
    }

    /**
     * Kiểm tra xem đơn hàng có thể yêu cầu hoàn tiền hay không
     */
    public function canRequestRefund(Order $order)
    {
        try {
            // Kiểm tra xem đơn hàng có thuộc về người dùng hiện tại không
            if ($order->getRawOriginal('user_id') !== auth()->id()) {
                return response()->json(['can_request' => false, 'reason' => 'unauthorized']);
            }
            
            // Kiểm tra trạng thái đơn hàng (chỉ đơn hàng đã hủy mới được hoàn tiền)
            if ($order->status_id != 4) {
                return response()->json(['can_request' => false, 'reason' => 'not_cancelled']);
            }
            
            // Kiểm tra xem phương thức thanh toán có phải là bank không
            if ($order->payment_method != 'bank') {
                return response()->json(['can_request' => false, 'reason' => 'not_bank_payment']);
            }
            
            // Kiểm tra xem đã thanh toán chưa
            if ($order->payment_status != 'completed') {
                return response()->json(['can_request' => false, 'reason' => 'payment_not_completed']);
            }
            
            // Kiểm tra xem đã có yêu cầu hoàn tiền nào chưa
            if ($order->refunds()->exists()) {
                return response()->json(['can_request' => false, 'reason' => 'refund_exists']);
            }
            
            return response()->json(['can_request' => true]);
            
        } catch (\Exception $e) {
            Log::error('Client OrderController@canRequestRefund - Error', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['can_request' => false, 'reason' => 'error']);
        }
    }

    /**
     * Kiểm tra trạng thái hoàn tiền của đơn hàng
     */
    public function checkRefundStatus(Order $order)
    {
        try {
            // Kiểm tra xem đơn hàng có thuộc về người dùng hiện tại không
            if ($order->getRawOriginal('user_id') !== auth()->id()) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized']);
            }
            
            // Kiểm tra xem có yêu cầu hoàn tiền hay không
            if (!$order->refunds()->exists()) {
                return response()->json([
                    'status' => 'not_found',
                    'has_refund' => false
                ]);
            }
            
            // Lấy yêu cầu hoàn tiền mới nhất
            $refund = $order->refunds()->latest()->first();
            
            return response()->json([
                'status' => 'success',
                'has_refund' => true,
                'refund_status' => $refund->is_active == 0 ? 'completed' : 'processing',
                'refund_data' => [
                    'amount' => $refund->amount,
                    'bank' => $refund->bank,
                    'bank_number' => $refund->bank_number,
                    'bank_name' => $refund->bank_name,
                    'created_at' => $refund->created_at->format('d/m/Y H:i:s'),
                    'is_active' => $refund->is_active
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Client OrderController@checkRefundStatus - Error', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['status' => 'error', 'message' => 'Đã xảy ra lỗi']);
        }
    }
} 