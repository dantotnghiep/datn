<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordFormRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\ThemeMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('client.auth.login');
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials, $request->filled('remember'))) {
                return redirect()->route('client.index');
            }
            return redirect()->back()
                ->with('error', 'Email hoặc mật khẩu không chính xác')
                ->withInput($request->except('password'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi đăng nhập.')
                ->withInput($request->except('password'));
        }
    }

    public function showRegisterForm()
    {
        return view('client.auth.register');
    }

    public function register(RegisterRequest $request)
    {
        // Tạo người dùng mới
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
        ]);

        Auth::login($user);

        return redirect()->route('login')->with('success', 'Đăng ký tài khoản thành công!');
    }



    public function showForgotPasswordForm()
    {
        return view('client.auth.forgot-password');
    }

    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return redirect()->back()->with('error','Tài khoản không tồn tại.' );
            }
            $password_reset_tokens =  DB::table('password_reset_tokens')->where('email', $user->email);
            if ($password_reset_tokens->exists()) {
                $password_reset_tokens->delete();
            }

            $key = Config::get('app.key');
            $token = Str::random(16);
            $encryptedToken = encrypt($token, $key);
            $password_reset = DB::table('password_reset_tokens')->insert([
                'email' => $user->email,
                'token' =>  $encryptedToken,
                'created_at' => Carbon::now()
            ]);
            $resetLink = url(env('APP_URL_DEFAULT') . route('password.reset', ['token' => $encryptedToken, 'email' => $user->email], false));
            $dataMail =[
                'name' => $user->name ?? NULL,
                'resetLink' => $resetLink ?? NULL,
                'email' => $user->email ?? NULL,
            ];
            $mail = Mail::to($user->email)
            ->send((new ThemeMail($dataMail, 'reset'))->subject('Thay đổi mật khẩu'));
            if ($user && $mail) {
                return redirect()->route('forgot-password')->with('success','Liên kết đặt lại mật khẩu đã được gửi đến email.');
            } else {
                return redirect()->route('forgot-password')->with('warning','Có lỗi phát sinh');
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi gửi email khôi phục mật khẩu.')
                ->withInput();
        }
    }
    public function showResetPasswordForm(Request $request)
    {
        $check = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        $status = false;
        if ($check && $request->token) {
            if ($check->token === $request->token) {
                $createdAt = Carbon::parse($check->created_at);
                $expiresAt = $createdAt->addMinutes(60);
                $currentTime = now();
                if ($currentTime->lte($expiresAt)) { // Nếu thời gian hiện tại nhỏ hơn hoặc bằng thời gian hết hạn
                    //less than or equal to - nhỏ hơn hoặc bằng
                    $status = true;
                    session(['reset_email' => $check->email, 'reset_token' => $check->token]);
                } else {
                    $status = false;
                }
            }
        }
        if (Auth::check()) {
            return redirect(route('client.index'));
        }
        return view('client.auth.reset-password',compact('status'));
    }

    public function resetPassword(ResetPasswordFormRequest $request){
        $email = session('reset_email');
        $token = session('reset_token');
        $check = DB::table('password_reset_tokens')->where('email', $email)->first();
        if ($check && $token) {
            if ($check->token === $token) {
                $createdAt = Carbon::parse($check->created_at);
                $expiresAt = $createdAt->addMinutes(60);
                $currentTime = now();
                if ($currentTime->lte($expiresAt)) {
                    $user = User::where('email', $email)->first();
                    if ($user) {
                        $user->password = Hash::make($request->password);
                        $user->save();
                        DB::table('password_reset_tokens')->where('email', $email)->delete();
                        session()->forget('reset_email');
                        session()->forget('reset_token');
                        return redirect()->route('login')->with('success','Thay đổi mật khẩu thành công.');
                    }
                }
            }
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->forget('error'); // Xóa session error
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->with('success', 'Đăng xuất thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi đăng xuất.');
        }
    }
}
