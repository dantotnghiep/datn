<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Order_item;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\VNPayService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $vnpayService;

    public function __construct(VNPayService $vnpayService)
    {
        $this->vnpayService = $vnpayService;
    }

    // Hiển thị trang thanh toán
    public function showCheckout()
    {
        $cartItems = Cart::where('user_id', auth()->id())->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        // Lấy thông tin người dùng hiện tại (người đặt hàng)
        $user = Auth::user();
        $addresses = auth()->user()->addresses; // Lấy tất cả địa chỉ
        $userEmail = auth()->user()->email; // Lấy email từ bảng users
        $subtotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);
        $discountAmount = 0; // Giả sử không có giảm giá, bạn có thể điều chỉnh
        $finalTotal = $subtotal - $discountAmount;

        return view('client.cart.checkout', compact('cartItems', 'user','userEmail', 'addresses', 'subtotal', 'discountAmount', 'finalTotal'));
    }

    public function store(Request $request)
    {
        try {
            // 1. Validate input
            // dd($request->all());
            $validated = $request->validate([
                'address_id' => 'required|exists:addresses,id,user_id,' . auth()->id(), // Validate address_id
                'user_email' => 'required|email|max:255', // Thêm trường email vào validation
                'payment_method' => 'required|in:cod,vnpay',
            ]);

            // Lấy thông tin địa chỉ
            $address = Address::where('id', $validated['address_id'])
                ->where('user_id', auth()->id())
                ->firstOrFail();
            $shippingAddress = implode(', ', [$address->street, $address->ward, $address->district, $address->province]);

            // 2. Lấy thông tin giỏ hàng
            $cartItems = Cart::where('user_id', auth()->id())->get();
            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Giỏ hàng trống!');
            }

            // 3. Tính tổng tiền
            $totalAmount = $cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            // 4. Xử lý theo phương thức thanh toán
            if ($validated['payment_method'] === 'vnpay') {
                // Lưu thông tin đơn hàng vào session để dùng sau khi thanh toán
                session([
                    'pending_order' => [
                        'user_name' => $address->recipient_name,
                        'user_phone' => $address->phone,
                        'user_email' => $validated['user_email'], // Dùng email từ form
                        'shipping_address' => $shippingAddress,
                        'payment_method' => 'vnpay',
                        'cart_items' => $cartItems->map(function ($item) {
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
                'user_name' => $address->recipient_name,
                'user_phone' => $address->phone,
                'user_email' => $validated['user_email'], // Dùng email từ form
                'shipping_address' => $shippingAddress,
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

    // Thêm địa chỉ mới
    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'street' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'is_default' => 'boolean',
        ]);

        \DB::beginTransaction();
        try {
            if ($request->boolean('is_default')) {
                // Nếu đặt làm mặc định, cập nhật tất cả địa chỉ khác thành không mặc định
                Address::where('user_id', auth()->id())->update(['is_default' => false]);
            }

            $address = Address::create([
                'user_id' => auth()->id(),
                'recipient_name' => $validated['recipient_name'],
                'phone' => $validated['phone'],
                'street' => $validated['street'],
                'ward' => $validated['ward'],
                'district' => $validated['district'],
                'province' => $validated['province'],
                'is_default' => $request->boolean('is_default', false),
            ]);

            \DB::commit();
            return redirect()->route('cart.checkout')->with('success', 'Thêm địa chỉ thành công!');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Thêm địa chỉ thất bại: ' . $e->getMessage());
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
