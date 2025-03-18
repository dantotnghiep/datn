<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $cartCount = Cart::where('user_id', auth()->id())->sum('quantity');
            } else {
                $cartCount = 0;
            }

            $view->with('cartCount', $cartCount);
        });

        View::composer('client.layouts.partials.lelf-navbar', function ($view) {
            if (auth()->check()) {
                $cartItems = Cart::with(['variation.product.images'])
                    ->where('user_id', auth()->id())
                    ->get();
                $cartCount = $cartItems->sum('quantity');
            } else {
                $cartItems = collect();
                $cartCount = 0;
            }

            $view->with([
                'cartItems' => $cartItems,
                'cartCount' => $cartCount
            ]);
        });
    }
}