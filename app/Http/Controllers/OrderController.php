<?php

namespace App\Http\Controllers;

use App\Events\OrderCancelled;
use App\Events\OrderStatusUpdated;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Discount;
use App\Models\Order_cancellation;
use App\Models\Order_item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // DB::transaction(function () use ($request, $userId, $cartItems, $defaultStatusId, $totalAmount) {
    //     // Tạo đơn hàng
    //     $order = Order::create([
    //         'user_id'         => $userId,
    //         'status_id'       => $defaultStatusId,
    //         'order_code'      => 'ORD' . time(),
    //         'user_name'       => $request->first_name . ' ' . $request->last_name,
    //         'user_phone'      => $request->phone,
    //         'user_email'      => $request->email,
    //         'total_amount'    => $totalAmount,
    //         'shipping_address' => "{$request->street_address}, {$request->city}, {$request->country}",
    //         'payment_method'  => 'COD',
    //     ]);

    //     // Lưu từng sản phẩm trong đơn hàng
    //     foreach ($cartItems as $item) {
    //         Order_item::create([
    //             'order_id'     => $order->id,
    //             'variation_id' => $item->variation_id,
    //             'quantity'     => $item->quantity,
    //             'price'        => $item->price,
    //         ]);
    //     }

    //     // Xóa giỏ hàng sau khi đặt hàng thành công
    //     Cart::where('user_id', $userId)->delete();
    // });
    // Phương thức hiển thị trang checkout
    public function index()
    {
        $orders = Order::query()->where('user_id', Auth::id())->get();
        return view('client.orders.index', compact('orders'));
    }
    public function checkout()
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', auth()->id())
            ->with(['variation.product.images']) // Nạp cả product và images
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        // Tính tổng tiền trước giảm giá
        $subtotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);

        // Khởi tạo biến giảm giá
        $discountAmount = 0;
        $finalTotal = $subtotal; // Đảm bảo $finalTotal luôn được khởi tạo
        $discountCode = session('discount_code');

        // Kiểm tra và áp dụng giảm giá
        if ($discountCode) {
            $discount = Discount::where('code', $discountCode)
                ->where('startDate', '<=', now())
                ->where('endDate', '>', now())
                ->where(function ($query) {
                    $query->whereNull('maxUsage')
                        ->orWhereRaw('maxUsage > usageCount');
                })
                ->first();

            if ($discount && $subtotal >= $discount->minOrderValue) {
                $discountAmount = min(($subtotal * $discount->sale) / 100, $discount->maxDiscount ?? INF);
                $finalTotal = $subtotal - $discountAmount;
            }
        }
        // Truyền dữ liệu vào view
        return view('client.cart.checkout', compact('user', 'cartItems', 'subtotal', 'finalTotal', 'discountAmount', 'discountCode'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string',
            'country' => 'required|string',
            'street_address' => 'required|string',
            'city' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'order_notes' => 'nullable|string',
        ]);

        $userId = Auth::id();

        // Lấy giỏ hàng của người dùng từ database
        $cartItems = Cart::where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        // Lấy ID của trạng thái mặc định thay vì hardcode
        $defaultStatusId = DB::table('order_statuses')->where('id', 1)->value('id');

        if (!$defaultStatusId) {
            return redirect()->back()->with('error', 'Trạng thái đơn hàng không tồn tại!');
        }

        // Tính tổng tiền trước khi áp dụng giảm giá
        $totalAmount = $cartItems->sum(fn($item) => $item->price * $item->quantity);
        $discountAmount = 0;
        $discountCode = session('discount_code');

        // Kiểm tra và áp dụng mã giảm giá từ session
        if ($discountCode) {
            $discount = Discount::where('code', $discountCode)
                ->where('startDate', '<=', now())
                ->where('endDate', '>', now())
                ->where(function ($query) {
                    $query->whereNull('maxUsage')
                        ->orWhereRaw('maxUsage > usageCount');
                })
                ->first();

            if ($discount && $totalAmount >= $discount->minOrderValue) {
                $discountAmount = min(($totalAmount * $discount->sale) / 100, $discount->maxDiscount ?? INF);
                $discount->increment('usageCount');
            }
        }

        // Tính tổng tiền cuối cùng sau khi áp dụng giảm giá
        $finalTotal = $totalAmount - $discountAmount;

        return DB::transaction(function () use ($request, $userId, $cartItems, $defaultStatusId, $finalTotal, $discountCode, $discountAmount) {
            // Tạo đơn hàng
            $order = Order::create([
                'user_id'         => $userId,
                'status_id'       => $defaultStatusId,
                'order_code'      => 'ORD-' . Str::uuid(),
                'user_name'       => $request->user_name,
                'user_phone'      => $request->phone,
                'user_email'      => $request->email,
                'total_amount'    => $finalTotal,
                'shipping_address' => "{$request->street_address}, {$request->city}, {$request->country}",
                'payment_method'  => 'COD',
                'discount_code'   => $discountCode,
                'discount_amount' => $discountAmount,
            ]);

            // Lưu sản phẩm trong đơn hàng
            foreach ($cartItems as $item) {
                Order_item::create([
                    'order_id'     => $order->id,
                    'variation_id' => $item->variation_id,
                    'quantity'     => $item->quantity,
                    'price'        => $item->price,
                ]);
            }

            // Xóa mã giảm giá trong session nếu có
            if (session()->has('discount_code')) {
                session()->forget('discount_code');
            }

            // Xóa giỏ hàng sau khi đặt hàng thành công
            Cart::where('user_id', $userId)->delete();

            return redirect()->route('order')->with('success', 'Đơn hàng đã được đặt thành công!');
        });
    }

    public function order()
    {
        return view('client.cart.order');
    }
    
    public function cancel($id)
    {
        try {
            DB::beginTransaction();
            
            $order = Order::with(['user', 'status'])
                         ->where('user_id', auth()->id())
                         ->where('id', $id)
                         ->firstOrFail();

            if ($order->status_id == 3 || $order->status_id == 4) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể hủy đơn hàng này'
                ], 400);
            }

            $order->status_id = 3; // Trạng thái đã hủy
            $order->save();
            
            // Load lại relationship để đảm bảo dữ liệu mới nhất
            $order->load(['status', 'user']);
            
            DB::commit();
            
            // Broadcast event với dữ liệu đầy đủ
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
                'message' => 'Có lỗi xảy ra khi hủy đơn hàng'
            ], 500);
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status_id' => 'required|exists:order_statuses,id'
        ]);

        $order->status_id = $request->status_id;
        $order->save();

        // Load the status relationship
        $order->load('status');
        
        // Broadcast the event for real-time updates
        event(new OrderStatusUpdated($order));

        return redirect()->back()->with('success', 'Order status updated successfully');
    }
}
