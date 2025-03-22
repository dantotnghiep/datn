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

            \DB::beginTransaction();

            // 2. Lấy thông tin giỏ hàng
            $cartItems = Cart::where('user_id', auth()->id())->get();
            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Giỏ hàng trống!');
            }

            // 3. Tính tổng tiền
            $totalAmount = $cartItems->sum(function($item) {
                return $item->price * $item->quantity;
            });

            // 4. Tạo đơn hàng
            $order = Order::create([
                'user_id' => auth()->id(),
                'user_name' => $validated['user_name'],
                'user_phone' => $validated['user_phone'],
                'user_email' => $validated['user_email'],
                'shipping_address' => $validated['shipping_address'],
                'payment_method' => $validated['payment_method'],
                'total_amount' => $totalAmount,
                'status_id' => 1, // Trạng thái chờ thanh toán
            ]);

            // 5. Tạo chi tiết đơn hàng
            foreach ($cartItems as $item) {
                Order_item::create([
                    'order_id' => $order->id,
                    'variation_id' => $item->variation_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }

            // 6. Xử lý theo phương thức thanh toán
            if ($validated['payment_method'] === 'vnpay') {
                // Đảm bảo order_code được tạo
                if (empty($order->order_code)) {
                    $order->order_code = 'ORD' . time() . rand(1000,9999);
                    $order->save();
                }

                $vnpayUrl = $this->vnpayService->createPaymentUrl([
                    'order_code' => $order->order_code,
                    'total_amount' => (int)$totalAmount,
                    'order_desc' => 'Thanh toan don hang ' . $order->order_code
                ]);

                \DB::commit();

                // Thêm debug log
                \Log::info('Redirecting to VNPay', [
                    'order_code' => $order->order_code,
                    'total_amount' => $totalAmount,
                    'vnpay_url' => $vnpayUrl
                ]);

                return redirect($vnpayUrl);
            }

            // Nếu là COD
            Cart::where('user_id', auth()->id())->delete();
            \DB::commit();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Order creation failed: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi xử lý đơn hàng: ' . $e->getMessage());
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
            if ($this->vnpayService->validateResponse($request)) {
                $orderId = $request->vnp_TxnRef;
                $order = Order::where('order_code', $orderId)->firstOrFail();

                if ($request->vnp_ResponseCode == '00') {
                    // Thanh toán thành công
                    $order->update([
                        'status_id' => 2, // Đã thanh toán
                    ]);

                    // Xóa giỏ hàng
                    Cart::where('user_id', auth()->id())->delete();

                    return redirect()->route('orders.show', $order->id)
                        ->with('success', 'Thanh toán thành công!');
                } else {
                    // Thanh toán thất bại
                    $order->update([
                        'status_id' => 6, // Hủy
                    ]);

                    return redirect()->route('orders.show', $order->id)
                        ->with('error', 'Thanh toán thất bại! Mã lỗi: ' . $request->vnp_ResponseCode);
                }
            }

            return redirect()->route('home')
                ->with('error', 'Chữ ký không hợp lệ!');

        } catch (\Exception $e) {
            \Log::error('VNPay return processing failed: ' . $e->getMessage());
            return redirect()->route('home')
                ->with('error', 'Có lỗi xảy ra khi xử lý kết quả thanh toán');
        }
    }
}