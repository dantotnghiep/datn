<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

class UserController extends Controller
{
    // Hiển thị danh sách người dùng
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Hiển thị form tạo mới
    public function create()
    {
        return view('admin.users.create');
    }

    // Lưu người dùng mới
    public function store(UserRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => $request->status,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Thêm người dùng thành công!');
    }

    // Hiển thị form chỉnh sửa
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Cập nhật người dùng
    public function update(UserRequest $request, User $user)
    {
        $data = $request->only(['name', 'email', 'phone', 'status', 'role']);

        // Nếu có password mới thì cập nhật
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Cập nhật người dùng thành công!');
    }
    public function verify()
    {

        if (!is_null(auth()->user()->email_verified)) {
            return redirect()->route('client.index')->with('warning', 'Tài khoản đã xác thực.');
        }
        return view('client.auth.verify');
    }
    public function postVerify(Request $request)
    {
        $user = User::where('id', auth()->user()->id)->first();

        if (!is_null($user->email_verified)) {
            return redirect()->route('client.index')->with('warning','Tài khoản đã xác thực.' );
        }
        $user->sendEmailVerificationNotificationCustom();
        return redirect()->route('verify')->with('success','Đã gửi mail thành công.');
    }
    public function checkVerify($id, $hash)
    {
        try {

            $decodedId = Crypt::decryptString($id);
            if (!$decodedId) {
                return redirect()->route('verify')->with('error','Liên kết không hợp lệ.' );
            }

            if (!URL::hasValidSignature(request())) {
                return redirect()->route('verify')->with('error','Liên kết không hợp lệ.');
            }
            $user = User::findOrFail($decodedId);

            if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
                return redirect()->route('verify')->with('error','Liên kết không hợp lệ.');
            }

            if ($user->hasVerifiedEmail()) {
                return redirect()->route('verify')->with('warning','Email đã được xác minh.');
            }
            $user->markEmailAsVerifiedCustom();
            return redirect()->route('client.index')->with( 'success','Xác minh email thành công!');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('verify')->with('error','Không tìm thấy người dùng.');
        } catch (\Exception $e) {
            return redirect()->route('verify')->with( 'error','Đã xảy ra lỗi.');
        }
    }

}
