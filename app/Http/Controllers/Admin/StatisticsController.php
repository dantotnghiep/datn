<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        // Mặc định sẽ là thời gian từ tháng trước đến hiện tại
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->subMonth()->startOfDay();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();
        
        // Chuyển đổi ngày thành định dạng hiển thị
        $formattedStartDate = $startDate->format('d/m/Y');
        $formattedEndDate = $endDate->format('d/m/Y');
        $periodTitle = "{$formattedStartDate} - {$formattedEndDate}";
        
        // Đếm số đơn hàng mới (status_id = 1 là đơn hàng mới/pending)
        $newOrders = DB::table('orders')
            ->where('status_id', 1)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Thống kê cơ bản
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('status', function($query) {
                $query->where('name', 'Completed');
            })->sum('total');
        
        // Thống kê đơn hàng theo trạng thái
        $orderStats = $this->getOrderStatusStats($startDate, $endDate);
        
        // Thống kê đơn hàng theo ngày trong khoảng thời gian
        $dailyStats = $this->getDailyOrderStats($startDate, $endDate);
        
        // Thống kê top sản phẩm bán chạy trong khoảng thời gian
        $topProducts = $this->getTopProducts($startDate, $endDate);
        
        // Thống kê doanh thu theo danh mục
        $categoryRevenue = $this->getCategoryRevenue($startDate, $endDate);
        
        // Thống kê khách hàng
        $customerStats = $this->getCustomerStats($startDate, $endDate);
        
        // Thống kê khách hàng theo đơn hàng
        $userOrderStats = $this->getUserOrderStats($startDate, $endDate);

        if ($request->ajax()) {
            return response()->json([
                'orderStats' => $orderStats,
                'dailyStats' => $dailyStats,
                'totalOrders' => $totalOrders,
                'newOrders' => $newOrders,
                'totalRevenue' => $totalRevenue,
                'periodTitle' => $periodTitle,
                'topProducts' => $topProducts,
                'categoryRevenue' => $categoryRevenue,
                'customerStats' => $customerStats,
                'userOrderStatsHtml' => view('admin.components.user_statistics_table', ['userOrderStats' => $userOrderStats])->render(),
                'dailyStatsHtml' => view('admin.components.time_stats_table', ['dailyStats' => $dailyStats])->render(),
            ]);
        }

        return view('admin.components.userstatistics', compact(
            'userOrderStats', 
            'newOrders', 
            'totalOrders',
            'totalRevenue',
            'dailyStats',
            'orderStats',
            'startDate',
            'endDate',
            'periodTitle',
            'topProducts',
            'categoryRevenue',
            'customerStats'
        ));
    }

    private function getOrderStatusStats($startDate, $endDate)
    {
        return OrderStatus::withCount(['orders' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->get()
            ->map(function($status) {
                return [
                    'name' => $status->name,
                    'value' => $status->orders_count
                ];
            });
    }

    private function getDailyOrderStats($startDate, $endDate)
    {
        // Nếu khoảng thời gian > 60 ngày, thì nhóm theo tuần
        $diffDays = $startDate->diffInDays($endDate);
        
        if ($diffDays > 60) {
            return $this->getMonthlyOrderStats($startDate, $endDate);
        } elseif ($diffDays > 31) {
            return $this->getWeeklyOrderStats($startDate, $endDate);
        } else {
            return $this->getDailyStats($startDate, $endDate);
        }
    }
    
    private function getDailyStats($startDate, $endDate)
    {
        return DB::table('orders')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(CASE WHEN status_id = 4 THEN 1 ELSE 0 END) as completed_orders'),
                DB::raw('SUM(CASE WHEN status_id = 5 THEN 1 ELSE 0 END) as cancelled_orders'),
                DB::raw('SUM(CASE WHEN status_id = 4 THEN total ELSE 0 END) as total_revenue')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function($item) {
                $date = Carbon::parse($item->date);
                return (object)[
                    'label' => $date->format('d/m/Y'),
                    'time_unit' => $date->format('Y-m-d'),
                    'total_orders' => $item->total_orders,
                    'completed_orders' => $item->completed_orders,
                    'cancelled_orders' => $item->cancelled_orders,
                    'total_revenue' => $item->total_revenue
                ];
            });
    }
    
    private function getWeeklyOrderStats($startDate, $endDate)
    {
        return DB::table('orders')
            ->select(
                DB::raw('YEARWEEK(created_at) as yearweek'),
                DB::raw('MIN(created_at) as week_start'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(CASE WHEN status_id = 4 THEN 1 ELSE 0 END) as completed_orders'),
                DB::raw('SUM(CASE WHEN status_id = 5 THEN 1 ELSE 0 END) as cancelled_orders'),
                DB::raw('SUM(CASE WHEN status_id = 4 THEN total ELSE 0 END) as total_revenue')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('yearweek')
            ->orderBy('yearweek')
            ->get()
            ->map(function($item) {
                $weekStart = Carbon::parse($item->week_start)->startOfWeek();
                $weekEnd = (clone $weekStart)->endOfWeek();
                return (object)[
                    'label' => $weekStart->format('d/m') . ' - ' . $weekEnd->format('d/m/Y'),
                    'time_unit' => $item->yearweek,
                    'total_orders' => $item->total_orders,
                    'completed_orders' => $item->completed_orders,
                    'cancelled_orders' => $item->cancelled_orders,
                    'total_revenue' => $item->total_revenue
                ];
            });
    }
    
    private function getMonthlyOrderStats($startDate, $endDate)
    {
        return DB::table('orders')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(CASE WHEN status_id = 4 THEN 1 ELSE 0 END) as completed_orders'),
                DB::raw('SUM(CASE WHEN status_id = 5 THEN 1 ELSE 0 END) as cancelled_orders'),
                DB::raw('SUM(CASE WHEN status_id = 4 THEN total ELSE 0 END) as total_revenue')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                $date = Carbon::createFromDate($item->year, $item->month, 1);
                return (object)[
                    'label' => $date->format('m/Y'),
                    'time_unit' => $item->year . '-' . $item->month,
                    'total_orders' => $item->total_orders,
                    'completed_orders' => $item->completed_orders,
                    'cancelled_orders' => $item->cancelled_orders,
                    'total_revenue' => $item->total_revenue
                ];
            });
    }
    
    private function getTopProducts($startDate, $endDate, $limit = 5)
    {
        try {
            $result = DB::table('order_items')
                ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
                ->join('product_variations', 'order_items.product_variation_id', '=', 'product_variations.id')
                ->join('products', 'product_variations.product_id', '=', 'products.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->whereNull('products.deleted_at')
                ->groupBy('products.id', 'products.name')
                ->orderBy('total_sold', 'desc')
                ->take($limit)
                ->get();

            return $result->map(function ($product) {
                return [
                    'name' => $product->name,
                    'value' => (int)$product->total_sold
                ];
            });
        } catch (\Exception $e) {
            Log::error('Error in getTopProducts: ' . $e->getMessage());
            return collect([]);
        }
    }
    
    private function getCategoryRevenue($startDate, $endDate)
    {
        try {
            return DB::table('categories')
                ->select('categories.name', DB::raw('SUM(order_items.quantity * order_items.price) as revenue'))
                ->join('products', 'categories.id', '=', 'products.category_id')
                ->join('product_variations', 'products.id', '=', 'product_variations.product_id')
                ->join('order_items', 'product_variations.id', '=', 'order_items.product_variation_id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('order_status', 'orders.status_id', '=', 'order_status.id')
                ->where('order_status.name', '=', 'Completed')
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->groupBy('categories.id', 'categories.name')
                ->get()
                ->map(function ($category) {
                    return [
                        'name' => $category->name,
                        'value' => (float)$category->revenue
                    ];
                });
        } catch (\Exception $e) {
            Log::error('Error in getCategoryRevenue: ' . $e->getMessage());
            return collect([]);
        }
    }
    
    private function getCustomerStats($startDate, $endDate)
    {
        try {
            // Tổng số khách hàng mới trong khoảng thời gian
            $newCustomers = User::whereBetween('created_at', [$startDate, $endDate])->count();
            
            // Khách hàng có đơn hàng trong khoảng thời gian
            $activeCustomers = DB::table('orders')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereNotNull('user_id')
                ->distinct('user_id')
                ->count('user_id');
                
            // Khách hàng quay lại (có từ 2 đơn hàng trở lên trong khoảng thời gian)
            $returningCustomers = DB::table('orders')
                ->select('user_id', DB::raw('COUNT(*) as order_count'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereNotNull('user_id')
                ->groupBy('user_id')
                ->having('order_count', '>', 1)
                ->count();
                
            return [
                'newCustomers' => $newCustomers,
                'activeCustomers' => $activeCustomers,
                'returningCustomers' => $returningCustomers,
                'chartData' => [
                    ['name' => 'Khách hàng mới', 'value' => $newCustomers],
                    ['name' => 'Khách hàng hoạt động', 'value' => $activeCustomers],
                    ['name' => 'Khách hàng quay lại', 'value' => $returningCustomers]
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error in getCustomerStats: ' . $e->getMessage());
            return [
                'newCustomers' => 0,
                'activeCustomers' => 0,
                'returningCustomers' => 0,
                'chartData' => []
            ];
        }
    }
    
    private function getUserOrderStats($startDate, $endDate)
    {
        return User::select(
            'users.id',
            'users.name',
            'users.email',
            DB::raw('COUNT(orders.id) as total_orders'),
            DB::raw('SUM(CASE WHEN orders.status_id = 4 THEN 1 ELSE 0 END) as completed_orders'),
            DB::raw('SUM(CASE WHEN orders.status_id = 5 THEN 1 ELSE 0 END) as cancelled_orders'),
            DB::raw('SUM(CASE WHEN orders.status_id = 4 THEN orders.total ELSE 0 END) as total_spent'),
            DB::raw('MAX(orders.created_at) as last_order_date')
        )
        ->leftJoin('orders', function($join) use ($startDate, $endDate) {
            $join->on('users.id', '=', 'orders.user_id')
                 ->whereBetween('orders.created_at', [$startDate, $endDate]);
        })
        ->groupBy('users.id', 'users.name', 'users.email')
        ->orderBy('total_orders', 'desc')
        ->paginate(10);
    }
}
