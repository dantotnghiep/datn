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
        return view("client.cart.cart", compact('cartItems'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'variation_id' => 'required|exists:variations,id',
            'product_name' => 'required|string',
            'color' => 'required|string',
            'size' => 'required|string',
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
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        Cart::where('id', $id)
            ->where('user_id', auth()->id())
            ->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
    }

    public function checkout()
    {
        return view('client.cart.checkout');
    }

    public function order()
    {
        return view('client.cart.order');
    }
}
