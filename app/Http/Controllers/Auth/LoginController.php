<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }

    // Xử lý đăng nhập cho Admin
    //     public function adminLogin(Request $request)
    // {
    //     // Validate dữ liệu
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|min:8'
    //     ]);

    //     $credentials = $request->only('email', 'password');

    //     // Thử đăng nhập
    //     if (Auth::attempt($credentials)) {
    //         $user = Auth::user();

    //         // Nếu là admin, chuyển hướng đến trang admin dashboard
    //         if ($user->role === 'admin') {
    //             return redirect()->intended(route('admin.dashboard'));
    //         }

    //         // Nếu là user thường hoặc staff, chuyển về trang chính
    //         return redirect()->intended(route('client.index'));
    //     }

    //     // Nếu đăng nhập thất bại
    //     return back()->withErrors(['email' => 'Sai thông tin đăng nhập!']);
    // }

    // Xử lý đăng nhập cho Client
    // public function adminLogin(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|min:8'
    //     ]);

    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt($credentials)) {
    //         $user = Auth::user();

    //         if ($user->role === 'admin') {
    //             Auth::logout();
    //             return redirect()->route('admin.dashboard');
    //         }

    //         // Nếu là user/staff -> Chuyển về dashboard
    //         return redirect()->route('login');
    //     }

    //     return redirect()->back()->withErrors(['email' => 'Sai thông tin đăng nhập!']);
    // }



    // Xử lý đăng nhập cho Client
    // public function clientLogin(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|min:8'
    //     ]);

    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt($credentials)) {
    //         $user = Auth::user();

    //         if ($user->role === 'admin') {
    //             Auth::logout();
    //             return redirect()->route('admin.auth.login')->withErrors(['email' => 'Tài khoản admin phải đăng nhập tại trang admin.']);
    //         }

    //         // Nếu là user/staff -> Chuyển về dashboard
    //         return redirect()->route('client.index');
    //     }

    //     return redirect()->back()->withErrors(['email' => 'Sai thông tin đăng nhập!']);
    // }

    public function login(Request $request, $type)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user && $user->status === 'inactive') {
            session()->flash('error', 'Tài khoản của bạn đã bị khóa.');
            return redirect()->route('login');
        }
        

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Nếu đăng nhập admin nhưng tài khoản là user, phải logout hoàn toàn
            if ($user->role === 'admin' && $type === 'user') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.auth.login')->withErrors(['email' => 'Tài khoản admin phải đăng nhập tại trang admin.']);
            }

            if ($user->role === 'staff' && $type === 'user') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.auth.login')->withErrors(['email' => 'Tài khoản admin phải đăng nhập tại trang admin.']);
            }

            if ($user->role === 'staff' && $type === 'admin') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('staff.dashboard')->withErrors(['email' => 'Tài khoản admin phải đăng nhập tại trang admin.']);
            }

            // Nếu đăng nhập client nhưng tài khoản là admin, cũng phải logout
            if ($user->role === 'user' && $type === 'admin') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->withErrors(['email' => 'Bạn không có quyền truy cập admin.']);
            }

            return ($user->role === 'admin') ? redirect()->route('admin.dashboard') : redirect()->route('client.index');
        }

        return redirect()->back()->withErrors(['email' => 'Sai thông tin đăng nhập!']);
    }

    public function loginAdmin(Request $request)
    {
        return $this->login($request, 'admin');
    }

    public function loginUser(Request $request)
    {
        return $this->login($request, 'user');
    }

    public function sta()
    {
        return view('staff.dashboard');
    }
}
