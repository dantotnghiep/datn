<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminEmployeeController extends Controller
{
    public function index()
    {
        $employees = User::whereIn('role', ['admin', 'staff'])->get();
        return view('admin.users.staffs.index', compact('employees'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|string|max:15', 
        'role' => 'required|in:admin,staff',
        'password' => 'required|string|min:8|confirmed',
    ]);

    User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'], // Lưu phone
        'role' => $validated['role'],
        'password' => Hash::make($validated['password']),
        'status' => 'active',
    ]);

    return redirect()->route('admin.users.staffs.index')->with('success', 'Thêm nhân viên thành công!');
}

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'phone' => 'required|string|max:15',
        'role' => 'required|in:admin,staff',
    ]);

    $employee = User::findOrFail($id);
    $employee->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'role' => $validated['role'],
    ]);

    return redirect()->route('admin.users.staffs.index')->with('success', 'Cập nhật nhân viên thành công!');
}

    public function destroy($id)
    {
        $employee = User::whereIn('role', ['admin', 'staff'])->findOrFail($id);
        $employee->delete();
        return redirect()->route('admin.users.staffs.index')->with('success', 'Xóa nhân viên thành công!');
    }

    public function lock($id)
    {
        $employee = User::whereIn('role', ['admin', 'staff'])->findOrFail($id);
        $employee->update(['status' => 'inactive']);
        return redirect()->route('admin.users.staffs.index')->with('success', 'Khóa tài khoản thành công!');
    }

    public function unlock($id)
    {
        $employee = User::whereIn('role', ['admin', 'staff'])->findOrFail($id);
        $employee->update(['status' => 'active']);
        return redirect()->route('admin.users.staffs.index')->with('success', 'Mở khóa tài khoản thành công!');
    }
}