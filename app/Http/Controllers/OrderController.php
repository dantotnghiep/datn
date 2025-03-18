<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Order_item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'country' => 'required|string',
            'street_address' => 'required|string',
            'city' => 'required|string',
            'order_code' => 'required|string',
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

        // Tính tổng tiền trước khi tạo đơn hàng
        $totalAmount = $cartItems->sum(fn($item) => $item->price * $item->quantity);

        
            // Tạo đơn hàng
            $order = Order::create([
                'user_id'         => $userId,
                'status_id'       => $defaultStatusId,
                'order_code'      => 'ORD' . time(),
                'user_name'       => $request->first_name . ' ' . $request->last_name,
                'user_phone'      => $request->phone,
                'user_email'      => $request->email,
                'total_amount'    => $totalAmount,
                'shipping_address' => "{$request->street_address}, {$request->city}, {$request->country}",
                'payment_method'  => 'COD',
            ]);

            // Lưu từng sản phẩm trong đơn hàng
            foreach ($cartItems as $item) {
                Order_item::create([
                    'order_id'     => $order->id,
                    'variation_id' => $item->variation_id,
                    'quantity'     => $item->quantity,
                    'price'        => $item->price,
                ]);
            }

            // Xóa giỏ hàng sau khi đặt hàng thành công
            Cart::where('user_id', $userId)->delete();
        

        return redirect()->route('cart.order')->with('success', 'Đơn hàng đã được đặt thành công!');
    }
}

