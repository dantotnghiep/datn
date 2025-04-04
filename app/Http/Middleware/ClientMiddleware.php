<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ClientMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next)
    // {
    //     if (!Auth::check()) {
    //         return redirect('/login')->withErrors(['email' => 'Vui lòng đăng nhập trước.']);
    //     }

    //     if (!in_array(Auth::user()->role, ['client', 'staff'])) {
    //         return redirect('/')->withErrors(['email' => 'Bạn không có quyền truy cập vào trang này.']);
    //     }

    //     return $next($request);
    // }
}
