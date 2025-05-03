<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        // Đếm số đơn hàng mới (status_id = 1 là đơn hàng mới/pending)
        $newOrders = DB::table('orders')
            ->where('status_id', 1)
            ->count();

        // Thống kê đơn hàng theo tháng trong năm hiện tại
        $monthlyStats = DB::table('orders')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(CASE WHEN status_id = 4 THEN 1 ELSE 0 END) as completed_orders'),
                DB::raw('SUM(CASE WHEN status_id = 5 THEN 1 ELSE 0 END) as cancelled_orders'),
                DB::raw('SUM(CASE WHEN status_id = 4 THEN total ELSE 0 END) as total_revenue')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $userOrderStats = User::select(
            'users.id',
            'users.name',
            'users.email',
            DB::raw('COUNT(orders.id) as total_orders'),
            DB::raw('SUM(CASE WHEN orders.status_id = 4 THEN 1 ELSE 0 END) as completed_orders'),
            DB::raw('SUM(CASE WHEN orders.status_id = 5 THEN 1 ELSE 0 END) as cancelled_orders'),
            DB::raw('SUM(CASE WHEN orders.status_id = 4 THEN orders.total ELSE 0 END) as total_spent'),
            DB::raw('MAX(orders.created_at) as last_order_date')
        )
        ->leftJoin('orders', 'users.id', '=', 'orders.user_id')
        ->groupBy('users.id', 'users.name', 'users.email')
        ->orderBy('total_orders', 'desc')
        ->paginate(10);

        return view('admin.components.userstatistics', compact('userOrderStats', 'newOrders', 'monthlyStats'));
    }
}
