<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Models\Order_item;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\VNPayService;

class OrderController extends Controller
{
    protected $vnpayService;

    public function __construct(VNPayService $vnpayService)
    {
        $this->vnpayService = $vnpayService;
    }

    public function store(Request $request)
    {
        try {
            // 1. Validate input
            $validated = $request->validate([
                'user_name' => 'required',
                'user_phone' => 'required',
                'user_email' => 'required|email',
                'shipping_address' => 'required',
                'payment_method' => 'required|in:cod,vnpay',
            ]);

            // 2. Lấy thông tin giỏ hàng
            $cartItems = Cart::where('user_id', auth()->id())->get();
            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Giỏ hàng trống!');
            }

            // 3. Tính tổng tiền
            $totalAmount = $cartItems->sum(function($item) {
                return $item->price * $item->quantity;
            });

            // 4. Xử lý theo phương thức thanh toán
            if ($validated['payment_method'] === 'vnpay') {
                // Lưu thông tin đơn hàng vào session để dùng sau khi thanh toán
                session([
                    'pending_order' => [
                        'user_name' => $validated['user_name'],
                        'user_phone' => $validated['user_phone'],
                        'user_email' => $validated['user_email'],
                        'shipping_address' => $validated['shipping_address'],
                        'payment_method' => 'vnpay',
                        'cart_items' => $cartItems->toArray(),
                        'total_amount' => $totalAmount
                    ]
                ]);

                // Tạo URL thanh toán VNPay với mã đơn hàng tạm thời
                $tempOrderCode = 'TEMP_' . time() . '_' . auth()->id();
                $vnpayUrl = $this->vnpayService->createPaymentUrl([
                    'order_code' => $tempOrderCode,
                    'total_amount' => $totalAmount,
                ]);

                return redirect($vnpayUrl);
            }

            // Xử lý COD (giữ nguyên logic cũ)
            \DB::beginTransaction();

            $order = Order::create([
                'user_id' => auth()->id(),
                'user_name' => $validated['user_name'],
                'user_phone' => $validated['user_phone'],
                'user_email' => $validated['user_email'],
                'shipping_address' => $validated['shipping_address'],
                'payment_method' => 'cod',
                'total_amount' => $totalAmount,
                'status_id' => 1,
            ]);

            foreach ($cartItems as $item) {
                Order_item::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);

            }

            Cart::where('user_id', auth()->id())->delete();

            \DB::commit();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Order creation failed: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        return view('client.orders.show', compact('order'));
    }

    public function vnpayReturn(Request $request)
    {
        \Log::info('VNPay Callback Received', $request->all());

        $vnpayService = new VNPayService();
        $isValidHash = $vnpayService->validateResponse($request);

        if (!$isValidHash) {
            \Log::error('VNPay hash validation failed');
            return redirect()->route('cart.index')->with('error', 'Có lỗi xảy ra khi xử lý thanh toán');
        }

        try {
            // Kiểm tra trạng thái giao dịch từ VNPay
            if ($request->vnp_ResponseCode == '00') {
                // Lấy mã đơn hàng từ vnp_TxnRef
                $orderCode = $request->vnp_TxnRef;

                // Tìm đơn hàng trong database
                $order = Order::where('order_code', $orderCode)->first();

                if (!$order) {
                    \Log::error('Order not found: ' . $orderCode);
                    return redirect()->route('cart.index')->with('error', 'Không tìm thấy đơn hàng');
                }

                // Cập nhật trạng thái đơn hàng
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'vnpay_transaction_no' => $request->vnp_TransactionNo,
                    'vnpay_payment_date' => date('Y-m-d H:i:s', strtotime($request->vnp_PayDate))
                ]);

                // Xóa giỏ hàng
                Cart::where('user_id', auth()->id())->delete();

                return redirect()->route('orders.show', $order->id)
                               ->with('success', 'Thanh toán thành công');
            }

            \Log::error('VNPay payment failed with code: ' . $request->vnp_ResponseCode);
            return redirect()->route('cart.index')
                           ->with('error', 'Thanh toán không thành công');

        } catch (\Exception $e) {
            \Log::error('VNPay return processing failed: ' . $e->getMessage());
            return redirect()->route('cart.index')
                           ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán');
        }
    }
}