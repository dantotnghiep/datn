<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)
            ->with(['productVariation.product', 'productVariation.attributeValues.attribute'])
            ->get();

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->total;
        }

        return view('client.cart.cart', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variation_id' => 'required|exists:product_variations,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $variation = ProductVariation::find($request->variation_id);

        if ($variation->stock < $request->quantity) {
            return back()->with('error', 'Số lượng sản phẩm không đủ');
        }

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $existingCart = Cart::where('user_id', $user->id)
            ->where('product_variation_id', $request->variation_id)
            ->first();

        if ($existingCart) {
            // Nếu đã có thì cập nhật số lượng
            $existingCart->quantity += $request->quantity;
            $existingCart->total = $existingCart->quantity * ($variation->sale_price ?: $variation->price);
            $existingCart->save();
        } else {
            // Nếu chưa có thì tạo mới
            Cart::create([
                'user_id' => $user->id,
                'product_variation_id' => $request->variation_id,
                'quantity' => $request->quantity,
                'price' => $variation->sale_price ?: $variation->price,
                'total' => $request->quantity * ($variation->sale_price ?: $variation->price)
            ]);
        }

        return redirect()->route('cart')->with('success', 'Đã thêm sản phẩm vào giỏ hàng');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variation_id' => 'required|exists:product_variations,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $variation = ProductVariation::find($request->variation_id);

        if ($variation->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng sản phẩm không đủ'
            ]);
        }

        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_variation_id', $request->variation_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity = $request->quantity;
            $cartItem->total = $request->quantity * ($variation->sale_price ?: $variation->price);
            $cartItem->save();
        }

        // Tính toán lại tổng tiền
        $total = 0;
        $cartItems = Cart::where('user_id', $user->id)->get();
        foreach ($cartItems as $item) {
            $total += $item->total;
        }

        return response()->json([
            'success' => true,
            'totals' => [
                'total' => number_format($total) . 'đ'
            ]
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variation_id' => 'required|exists:product_variations,id'
        ]);

        $user = Auth::user();
        Cart::where('user_id', $user->id)
            ->where('product_variation_id', $request->variation_id)
            ->delete();

        // Tính toán lại tổng tiền
        $total = 0;
        $cartItems = Cart::where('user_id', $user->id)->get();
        foreach ($cartItems as $item) {
            $total += $item->total;
        }

        return response()->json([
            'success' => true,
            'totals' => [
                'total' => number_format($total) . 'đ'
            ]
        ]);
    }

    public function clear()
    {
        $user = Auth::user();
        Cart::where('user_id', $user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa giỏ hàng'
        ]);
    }
    public function saveSelectedItems(Request $request)
    {
        $selected = json_decode($request->input('selected_items'), true);
        session(['selected_cart_items' => $selected]);
        return redirect()->route('checkout');
    }
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $selectedVariationIds = session('selected_cart_items', []);
        if (empty($selectedVariationIds)) {
            return redirect()->route('cart')->with('error', 'Vui lòng chọn sản phẩm để thanh toán');
        }
    
        $selectedItems = Cart::where('user_id', $user->id)
            ->whereIn('product_variation_id', $selectedVariationIds)
            ->with(['productVariation.product', 'productVariation.attributeValues.attribute'])
            ->get();
    
        $subtotal = $selectedItems->sum('total');
        $discount = 0;
        $shippingFee = 0;
        $total = $subtotal - $discount + $shippingFee;
    
        return view('client.cart.checkout', compact(
            'selectedItems', 'subtotal', 'discount', 'shippingFee', 'total'
        ));
    }
}
