<?php

namespace App\Http\Controllers\Admin;

use App\Events\UserLocked;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminCustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'user')->get()->map(function ($user) {
            $user->completed_orders = Order::where('user_id', $user->id)
                ->where('status_id', 4)
                ->count();
            $user->total_spent = Order::where('user_id', $user->id)
                ->where('status_id', 4)
                ->sum('total_amount');
            return $user;
        });

        return view('admin.users.clients.index', compact('customers'));
    }

    public function lock(Request $request, $id)
    {
        $customer = User::where('role', 'user')->findOrFail($id);
        $customer->update(['status' => 'inactive']);
        $customer->save();
        
        // Xóa session error
        session()->forget('error');
        return redirect()->route('admin.users.clients.index')->with('success', 'Khóa tài khoản thành công!');
    }

    public function unlock($id)
    {
        $customer = User::where('role', 'user')->findOrFail($id);
        $customer->update(['status' => 'active']);
        $customer->save();
        session()->forget('error');
        return redirect()->route('admin.users.clients.index')->with('success', 'Mở khóa tài khoản thành công!');
    }

    public function show(Request $request, $id)
    {
        $customer = User::with(['orders.status'])->where('role', 'user')->findOrFail($id);

        // Lấy filter thời gian từ request (mặc định là 'all')
        $filter = $request->input('filter', 'all');
        $query = $customer->orders()->join('order_statuses', 'orders.status_id', '=', 'order_statuses.id');

        // Lọc theo thời gian
        if ($filter === 'week') {
            $query->where('orders.created_at', '>=', now()->subWeek());
        } elseif ($filter === 'month') {
            $query->where('orders.created_at', '>=', now()->subMonth());
        } elseif ($filter === 'year') {
            $query->where('orders.created_at', '>=', now()->subYear());
        }

        // Tổng số đơn hàng theo trạng thái
        $orderStats = $query->selectRaw('order_statuses.status_name, COUNT(*) as count')
            ->groupBy('order_statuses.status_name')
            ->pluck('count', 'order_statuses.status_name')
            ->toArray();

        $statuses = ['Completed', 'Cancelled', 'Failed', 'Pending'];
        $chartData = [];
        $totalOrders = [];
        foreach ($statuses as $status) {
            $chartData[$status] = $orderStats[$status] ?? 0;
            $totalOrders[$status] = $orderStats[$status] ?? 0;
        }

        // Tổng số tiền thanh toán cho đơn hàng thành công
        $totalPaid = $customer->orders()
            ->where('status_id', 4)
            ->sum('total_amount');

        return view('admin.users.clients.detail', compact('customer', 'chartData', 'totalOrders', 'totalPaid', 'filter'));
    }
}
