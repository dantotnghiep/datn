<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'user')->get()->map(function ($user) {
            $user->completed_orders = Order::where('user_id', $user->id)
                ->where('status_id', 4) // Completed
                ->count();
            $user->total_spent = Order::where('user_id', $user->id)
                ->where('payment_status', 'completed')
                ->sum('total_amount');
            return $user;
        });

        return view('admin.users.clients.index', compact('customers'));
    }

    public function lock($id)
    {
        $customer = User::where('role', 'user')->findOrFail($id);
        $customer->update(['status' => 'inactive']);
        return redirect()->route('admin.users.clients.index')->with('success', 'Khóa tài khoản thành công!');
    }

    public function unlock($id)
    {
        $customer = User::where('role', 'user')->findOrFail($id);
        $customer->update(['status' => 'active']);
        return redirect()->route('admin.users.clients.index')->with('success', 'Mở khóa tài khoản thành công!');
    }
}