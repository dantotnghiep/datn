<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function cart()
    {
        return view('client.cart.cart');
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
