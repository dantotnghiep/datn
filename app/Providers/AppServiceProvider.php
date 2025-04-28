<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        // Chia sẻ danh mục cho menu trong header
        View::composer('client.layouts.partials.menu', function ($view) {
            $categories = Category::all();
            $view->with('categories', $categories);
        });
    }
}
