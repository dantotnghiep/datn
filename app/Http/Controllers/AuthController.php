<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function login()
    {
        return view('admin.auth.login');
    }

    public function forgotpassword()
    {
        return view('admin.auth.forgot-password');
    }

    public function loginclient()
    {
        return view('client.auth.login');
    }

    public function register()
    {
        return view('client.auth.register');
    }
    public function logout(Request $request)
    {
        Auth::logout();  // Đăng xuất người dùng
        $request->session()->invalidate();  // Hủy phiên làm việc
        $request->session()->regenerateToken();  // Tạo token mới để tránh tấn công CSRF

        return redirect('/admin/login');  // Chuyển hướng về trang đăng nhập
    }
}
