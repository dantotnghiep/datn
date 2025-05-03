<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Vui lòng đăng nhập');
        }

        // Kiểm tra role
        $user = Auth::user();
        $isAdmin = $user->role_id === 1;
        $isStaff = $user->role_id === 3;

        // Nếu không phải admin hoặc nhân viên
        if (!$isAdmin && !$isStaff) {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này');
        }

        // Nếu là nhân viên, chỉ cho phép truy cập một số route
        if ($isStaff) {
            $allowedRoutes = ['admin.dashboard', 'admin.orders.', 'admin.promotions.'];
            $currentRoute = $request->route()->getName();

            $hasAccess = false;
            foreach ($allowedRoutes as $route) {
                if (str_starts_with($currentRoute, $route)) {
                    $hasAccess = true;
                    break;
                }
            }

            if (!$hasAccess) {
                return redirect()->route('admin.dashboard')->with('error', 'Bạn không có quyền truy cập trang này');
            }
        }

        return $next($request);
    }
}
