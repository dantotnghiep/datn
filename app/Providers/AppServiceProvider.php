<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Cart;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('client.layouts.partials.lelf-navbar', function ($view) {
            $cartItems = Cart::all();
            $cartTotal = $cartItems->sum(function ($item) {
                return $item->getFinalPriceAttribute() * $item->quantity;
            });

            $view->with([
                'cartItems' => $cartItems,
                'cartTotal' => $cartTotal
            ]);
        });
    }
}
