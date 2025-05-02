<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use Illuminate\Pagination\Paginator;

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
        // Configure Bootstrap pagination
        Paginator::useBootstrap();

        // Chia sẻ danh mục cho menu trong header
        View::composer('client.layouts.partials.menu', function ($view) {
            $categories = Category::all();
            $view->with('categories', $categories);
        });

        // Chia sẻ số lượng sản phẩm trong giỏ hàng cho header
        View::composer('client.layouts.partials.header', function ($view) {
            $cartCount = 0;
            if (Auth::check()) {
                $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
            }
            $view->with('cartCount', $cartCount);
        });
    }
}
