<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('manage-all', function ($user) {
            return $user->role_id === 1; // Chỉ admin (role_id = 1) mới có quyền truy cập tất cả
        });

        // Định nghĩa response khi không có quyền
        Gate::after(function ($user, $ability, $result, $arguments) {
            if (!$result) {
                abort(403, 'Bạn không có quyền truy cập trang này');
            }
        });
    }
}
