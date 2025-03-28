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
                        'cart_items' => $cartItems->map(function($item) {
                            return [
                                'variation_id' => $item->variation_id,
                                'quantity' => $item->quantity,
                                'price' => $item->price
                            ];
                        })->toArray(),
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
                    'variation_id' => $item->variation_id,
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
        return redirect()->route('cart.index')
            ->with('success', 'Đặt hàng thành công!');
    }

    public function vnpayReturn(Request $request)
    {
        // Validate response từ VNPay
        if (!$this->vnpayService->validateResponse($request)) {
            return redirect()->route('cart.checkout')->with('error', 'Invalid VNPay response');
        }

        try {
            \DB::beginTransaction();

            // Lấy thông tin đơn hàng từ session
            $pendingOrder = session('pending_order');
            if (!$pendingOrder) {
                throw new \Exception('Không tìm thấy thông tin đơn hàng');
            }

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => auth()->id(),
                'user_name' => $pendingOrder['user_name'],
                'user_phone' => $pendingOrder['user_phone'],
                'user_email' => $pendingOrder['user_email'],
                'shipping_address' => $pendingOrder['shipping_address'],
                'payment_method' => 'vnpay',
                'total_amount' => $pendingOrder['total_amount'],
                'status_id' => 1,
                'payment_status' => 'completed',
                'vnpay_transaction_no' => $request->vnp_TransactionNo,
                'vnpay_payment_date' => $request->vnp_PayDate
            ]);

            // Tạo các item cho đơn hàng
            foreach ($pendingOrder['cart_items'] as $item) {
                Order_item::create([
                    'order_id' => $order->id,
                    'variation_id' => $item['variation_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            // Xóa giỏ hàng
            Cart::where('user_id', auth()->id())->delete();

            // Xóa thông tin đơn hàng tạm từ session
            session()->forget('pending_order');

            \DB::commit();

            // Thay đổi redirect về cart.index thay vì orders.show
            return redirect()->route('cart.index')
                ->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('VNPay order creation failed: ' . $e->getMessage());
            return redirect()->route('cart.checkout')
                ->with('error', 'Có lỗi xảy ra khi xử lý đơn hàng: ' . $e->getMessage());
        }
    }
}