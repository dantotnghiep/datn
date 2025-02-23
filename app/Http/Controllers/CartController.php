<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function index(Cart $cart)
    {
        $cartItems = $cart->list();
        
        return view("client.cart.cart", compact('cartItems'));
    }
    public function add(Request $request,Cart $cart)
    {
        $product = Product::find($request->id);
        $quantity = $request->quantity;
        $cart->add($product,$quantity);
        return redirect()->route('cart.index');
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
