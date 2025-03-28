<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('client.auth.login');
    }

    // public function login(LoginRequest $request)
    // {
    //     try {
    //         $credentials = $request->validated();

    //         if (Auth::attempt($credentials, $request->filled('remember'))) {
    //             $user = Auth::user();

    //             // Điều hướng dựa trên role
    //             if ($user->role === 'admin') {
    //                 return redirect()->intended(route('admin.dashboard'));
    //             }

    //             return redirect()->intended(route('dashboard'));
    //         }

    //         return redirect()->back()
    //             ->with('error', 'Email hoặc mật khẩu không chính xác')
    //             ->withInput($request->except('password'));
    //     } catch (\Exception $e) {
    //         return redirect()->back()
    //             ->with('error', 'Đã xảy ra lỗi khi đăng nhập.')
    //             ->withInput($request->except('password'));
    //     }
    // }

    public function showRegisterForm()
    {
        return view('client.auth.register');
    }

    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
            ]);

            Auth::login($user);

            return redirect()->route('dashboard')
                ->with('success', 'Đăng ký tài khoản thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi đăng ký.')
                ->withInput($request->except('password'));
        }
    }

    public function showForgotPasswordForm()
    {
        return view('client.auth.forgot-password');
    }

    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT
                ? back()->with(['success' => 'Chúng tôi đã gửi email khôi phục mật khẩu cho bạn!'])
                : back()->withErrors(['email' => __($status)]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi gửi email khôi phục mật khẩu.')
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->with('success', 'Đăng xuất thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi đăng xuất.');
        }
    }
}
