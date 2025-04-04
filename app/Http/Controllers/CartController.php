<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Variation;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', auth()->id())->get();

        // Sửa lại query lấy discounts
        $now = now();
        \Log::info('Current time:', ['now' => $now->toDateTimeString()]);

        $discounts = \App\Models\Discount::query()
            ->whereNull('deleted_at')
            ->where('endDate', '>', $now->format('Y-m-d H:i:s'))  // Format datetime
            ->where('startDate', '<=', $now->format('Y-m-d H:i:s'))  // Format datetime
            ->where(function($query) {
                $query->whereNull('maxUsage')
                      ->orWhereRaw('maxUsage > usageCount');
            })
            ->get();

        // Log full query để debug
        \Log::info('SQL Query:', [
            'sql' => \App\Models\Discount::query()
                ->whereNull('deleted_at')
                ->where('endDate', '>', $now->format('Y-m-d H:i:s'))
                ->where('startDate', '<=', $now->format('Y-m-d H:i:s'))
                ->where(function($query) {
                    $query->whereNull('maxUsage')
                          ->orWhereRaw('maxUsage > usageCount');
                })
                ->toSql(),
            'bindings' => [
                'now' => $now->format('Y-m-d H:i:s')
            ]
        ]);

        \Log::info('Discounts found:', [
            'count' => $discounts->count(),
            'discounts' => $discounts->toArray()
        ]);

        // Tính tổng giá trị giỏ hàng
        $total = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        // Khởi tạo giá trị mặc định
        $discountAmount = 0;
        $finalTotal = $total;

        // Kiểm tra và áp dụng mã giảm giá từ session
        $discountCode = session('discount_code');
        if ($discountCode) {
            $discount = \App\Models\Discount::where('code', $discountCode)
                ->where('startDate', '<=', now())
                ->where('endDate', '>', now())
                ->first();

            if ($discount && $total >= $discount->minOrderValue) {
                $discountAmount = ($total * $discount->sale) / 100;
                if ($discount->maxDiscount > 0) {
                    $discountAmount = min($discountAmount, $discount->maxDiscount);
                }
                $finalTotal = $total - $discountAmount;
            }
        }

        return view("client.cart.cart", compact('cartItems', 'discounts', 'total', 'finalTotal', 'discountAmount'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'variation_id' => 'required|exists:variations,id',
            'product_name' => 'required|string',
            'color' => 'string',
            'size' => 'string',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric'
        ]);

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $existingCart = Cart::where([
            'user_id' => auth()->id(),
            'variation_id' => $request->variation_id,
            'color' => $request->color,
            'size' => $request->size,
        ])->first();

        if ($existingCart) {
            // Nếu đã có, cập nhật số lượng
            $existingCart->update([
                'quantity' => $existingCart->quantity + $request->quantity
            ]);
        } else {
            // Nếu chưa có, tạo mới
            Cart::create([
                'user_id' => auth()->id(),
                'variation_id' => $request->variation_id,
                'product_name' => $request->product_name,
                'color' => $request->color,
                'size' => $request->size,
                'quantity' => $request->quantity,
                'price' => $request->price
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully!');
    }

    public function remove($id)
    {
        Cart::where('id', $id)->where('user_id', auth()->id())->delete();
        return redirect()->route('cart.index')->with('success', 'Product removed from cart!');
    }

    public function update(Request $request, $id)
    {
        // Đảm bảo request có quantity
        if (!$request->has('quantity')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Thiếu thông tin số lượng'], 400);
            }
            return redirect()->route('cart.index')->with('error', 'Thiếu thông tin số lượng!');
        }

        // Validate dữ liệu
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        \Log::info('Update Cart Request', [
            'cart_id' => $id,
            'user_id' => auth()->id(),
            'quantity' => $request->quantity
        ]);

        $cart = Cart::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if ($cart) {
            $cart->update(['quantity' => $request->quantity]);

            // Trả về JSON response khi là AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Số lượng đã được cập nhật',
                    'quantity' => $request->quantity,
                    'id' => $id
                ]);
            }

            return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm'], 404);
        }

        return redirect()->route('cart.index')->with('error', 'Không tìm thấy sản phẩm!');
    }

    public function checkout(Request $request)
    {
        $selectedItems = $request->input('selected_items', []);

        // Kiểm tra nếu không có sản phẩm nào được chọn
        if (empty($selectedItems)) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán!');
        }

        $cartItems = Cart::whereIn('id', $selectedItems)->get();

        // Tính tổng tiền trước giảm giá
        $subtotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);

        // Khởi tạo giá trị mặc định
        $discountAmount = 0;
        $finalTotal = $subtotal;

        // Kiểm tra và áp dụng mã giảm giá từ session
        $discountCode = session('discount_code');
        if ($discountCode) {
            $discount = \App\Models\Discount::where('code', $discountCode)
                ->where('startDate', '<=', now())
                ->where('endDate', '>', now())
                ->first();

            if ($discount && $subtotal >= $discount->minOrderValue) {
                $discountAmount = ($subtotal * $discount->sale) / 100;
                if ($discount->maxDiscount > 0) {
                    $discountAmount = min($discountAmount, $discount->maxDiscount);
                }
                $finalTotal = $subtotal - $discountAmount;
            }
        }

        return view('client.cart.checkout', compact('cartItems', 'subtotal', 'finalTotal', 'discountAmount', 'discountCode'));
    }

    public function order()
    {
        return view('client.cart.order');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'discount_code' => 'required|exists:discounts,code'
        ]);

        // Xóa mã giảm giá cũ trong session (nếu có)
        session()->forget('discount_code');

        $discount = \App\Models\Discount::where('code', $request->discount_code)
            ->where('startDate', '<=', now())
            ->where('endDate', '>', now())
            ->first();

        if (!$discount) {
            return redirect()->back()->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết hạn!');
        }

        // Kiểm tra số lần sử dụng
        if ($discount->maxUsage > 0 && $discount->usageCount >= $discount->maxUsage) {
            return redirect()->back()->with('error', 'Mã giảm giá đã hết lượt sử dụng!');
        }

        // Tính tổng giá trị đơn hàng
        $cartTotal = Cart::where('user_id', auth()->id())
            ->sum(\DB::raw('price * quantity'));

        // Kiểm tra giá trị đơn hàng tối thiểu
        if ($cartTotal < $discount->minOrderValue) {
            return redirect()->back()->with('error', 'Giá trị đơn hàng chưa đạt mức tối thiểu!');
        }

        // Lưu mã giảm giá mới vào session
        session(['discount_code' => $discount->code]);

        return redirect()->back()->with('success', 'Áp dụng mã giảm giá thành công!');
    }
}
