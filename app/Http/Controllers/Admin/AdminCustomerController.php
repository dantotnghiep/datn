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

    public function show($id)
    {
        $customer = User::with(['orders.status'])->where('role', 'user')->findOrFail($id);

        // Dữ liệu cho biểu đồ: Số lượng đơn hàng theo trạng thái
        $chartData = $customer->orders()
            ->join('order_statuses', 'orders.status_id', '=', 'order_statuses.id')
            ->selectRaw('order_statuses.status_name, COUNT(*) as count')
            ->groupBy('order_statuses.status_name')
            ->pluck('count', 'order_statuses.status_name')
            ->toArray();

        // Đảm bảo có dữ liệu cho các trạng thái chính
        $statuses = ['Completed', 'Cancelled', 'Failed'];
        $chartDataFinal = [];
        foreach ($statuses as $status) {
            $chartDataFinal[$status] = $chartData[$status] ?? 0;
        }

        return view('admin.users.clients.detail', compact('customer', 'chartDataFinal'));
    }
}