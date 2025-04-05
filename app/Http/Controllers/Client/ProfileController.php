<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();
        $addresses = $user->addresses;
        return view('client.auth.profile', compact('user', 'addresses'));
    }

    public function update(UpdateProfileRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = Auth::user();

            // Cập nhật thông tin người dùng
            $user->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
            ]);

            return redirect()->route('profile')->with('success', 'Cập nhật thông tin thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật thông tin.')
                ->withInput();
        }
    }

    // Thêm địa chỉ
    public function storeAddress(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'street' => 'required|string|max:255',
            'ward' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'is_default' => 'boolean',
        ]);

        $user = Auth::user();
        $data = $request->all();
        $data['user_id'] = $user->id;

        if ($request->input('is_default')) {
            Address::where('user_id', $user->id)->update(['is_default' => false]);
            $data['is_default'] = true;
        } elseif ($user->addresses->isEmpty()) {
            $data['is_default'] = true;
        }

        Address::create($data);

        return redirect()->route('profile')->with('success', 'Địa chỉ đã được thêm thành công.');
    }

    // Cập nhật địa chỉ
    public function updateAddress(Request $request, Address $address)
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'street' => 'required|string|max:255',
            'ward' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'is_default' => 'boolean',
        ]);

        $user = Auth::user();
        if ($address->user_id !== $user->id) {
            return redirect()->route('profile')->with('error', 'Bạn không có quyền chỉnh sửa địa chỉ này.');
        }

        if ($request->input('is_default')) {
            Address::where('user_id', $user->id)->update(['is_default' => false]);
        }

        $address->update($request->all());

        return redirect()->route('profile')->with('success', 'Địa chỉ đã được cập nhật thành công.');
    }

    // Xóa địa chỉ
    public function destroyAddress(Address $address)
    {
        $user = Auth::user();
        if ($address->user_id !== $user->id) {
            return redirect()->route('profile')->with('error', 'Bạn không có quyền xóa địa chỉ này.');
        }

        $address->delete();

        if ($address->is_default && $user->addresses->isNotEmpty()) {
            $user->addresses->first()->update(['is_default' => true]);
        }

        return redirect()->route('profile')->with('success', 'Địa chỉ đã được xóa thành công.');
    }

    // Đặt địa chỉ mặc định
    public function setDefaultAddress(Address $address)
    {
        $user = Auth::user();
        if ($address->user_id !== $user->id) {
            return redirect()->route('profile')->with('error', 'Bạn không có quyền thực hiện hành động này.');
        }

        Address::where('user_id', $user->id)->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return redirect()->route('profile')->with('success', 'Đã đặt địa chỉ mặc định thành công.');
    }
} 