<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        return view('client.auth.profile');
    }

    public function update(UpdateProfileRequest $request)
    {
        try {
            $validated = $request->validated();
            
            User::where('id', Auth::id())->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'address' => $validated['address']
            ]);

            return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật thông tin.')
                ->withInput();
        }
    }
} 