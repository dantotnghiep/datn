<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Models\Order_item;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\PaymentIntent;
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

    public function paymentSuccess()
    {
        if (!session('order_id')) {
            return redirect()->route('cart.index');
        }

        return view('client.orders.success');
    }

    public function processStripePayment(Request $request)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            // Validate amount
            $amount = $request->input('amount');
            if (!is_numeric($amount) || $amount <= 0) {
                throw new \Exception('Invalid amount');
            }

            // Create PaymentIntent
            $paymentIntent = PaymentIntent::create([
                'amount' => (int)($amount * 100), // Convert to cents
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'user_id' => auth()->id()
                ]
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret
            ]);

        } catch (\Exception $e) {
            \Log::error('Stripe payment processing failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Payment processing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function vnpayReturn(Request $request)
    {
        try {
            \Log::info('VNPay Callback Received', $request->all());

            if (!$this->vnpayService->validateResponse($request)) {
                return redirect()->route('cart.index')
                    ->with('error', 'Chữ ký không hợp lệ!');
            }

            if ($request->vnp_ResponseCode != '00') {
                return redirect()->route('cart.index')
                    ->with('error', 'Thanh toán thất bại! Vui lòng thử lại.');
            }

            // Lấy thông tin đơn hàng từ session
            $pendingOrder = session('pending_order');
            if (!$pendingOrder) {
                return redirect()->route('cart.index')
                    ->with('error', 'Không tìm thấy thông tin đơn hàng!');
            }

            \DB::beginTransaction();

            // Tạo đơn hàng mới
            $order = Order::create([
                'user_id' => auth()->id(),
                'user_name' => $pendingOrder['user_name'],
                'user_phone' => $pendingOrder['user_phone'],
                'user_email' => $pendingOrder['user_email'],
                'shipping_address' => $pendingOrder['shipping_address'],
                'payment_method' => 'vnpay',
                'total_amount' => $pendingOrder['total_amount'],
                'status_id' => 1, // Pending
            ]);

            // Tạo chi tiết đơn hàng
            foreach ($pendingOrder['cart_items'] as $item) {
                Order_item::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            // Xóa giỏ hàng
            Cart::where('user_id', auth()->id())->delete();

            // Xóa thông tin đơn hàng tạm từ session
            session()->forget('pending_order');

            \DB::commit();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Đặt hàng và thanh toán thành công!');

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('VNPay return processing failed: ' . $e->getMessage());
            return redirect()->route('cart.index')
                ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán');
        }
    }
}