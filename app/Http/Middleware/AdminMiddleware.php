<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in and has admin role
        if (Auth::check() && Auth::user()->role_id == 1 || Auth::user()->role_id == 3) { // Assuming role_id 1 is for admin
            return $next($request);
        }

        // If not admin, redirect to home page
        return redirect()->route('home');
    }
} 