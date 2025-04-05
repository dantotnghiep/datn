<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->status === 'inactive') {
                Auth::logout();
                session()->flash('error', 'Tài khoản của bạn đã bị khóa.');
                $request->session()->regenerateToken(); // Chỉ regenerate token
                return redirect()->route('login');
            }

            // Xóa session error nếu tài khoản active
            if ($user->status === 'active') {
                $request->session()->forget('error');
            }
        }


        return $next($request);
    }
}
