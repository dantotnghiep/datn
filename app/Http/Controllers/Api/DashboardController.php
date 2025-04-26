<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getDataByRange(Request $request)
    {
        try {
            \Log::info('DashboardController::getDataByRange - Request received', [
                'params' => $request->all()
            ]);
            
            // Validate the request
            $request->validate([
                'range' => 'required|string|in:today,yesterday,last7days,last30days,thismonth,lastmonth,custom',
                'start_date' => 'required_if:range,custom|date',
                'end_date' => 'required_if:range,custom|date|after_or_equal:start_date',
            ]);

            $range = $request->range;
            $now = Carbon::now();
            
            \Log::info('DashboardController::getDataByRange - Processing range', [
                'range' => $range,
                'now' => $now->toDateTimeString()
            ]);

            // Set start and end dates based on the requested range
            switch ($range) {
                case 'today':
                    $startDate = $now->copy()->startOfDay();
                    $endDate = $now->copy()->endOfDay();
                    break;
                    
                case 'yesterday':
                    $startDate = $now->copy()->subDay()->startOfDay();
                    $endDate = $now->copy()->subDay()->endOfDay();
                    break;
                    
                case 'last7days':
                    $startDate = $now->copy()->subDays(6)->startOfDay();
                    $endDate = $now->copy()->endOfDay();
                    break;
                    
                case 'last30days':
                    $startDate = $now->copy()->subDays(29)->startOfDay();
                    $endDate = $now->copy()->endOfDay();
                    break;
                    
                case 'thismonth':
                    $startDate = $now->copy()->startOfMonth();
                    $endDate = $now->copy()->endOfMonth();
                    break;
                    
                case 'lastmonth':
                    $startDate = $now->copy()->subMonth()->startOfMonth();
                    $endDate = $now->copy()->subMonth()->endOfMonth();
                    break;
                    
                case 'custom':
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                    break;
                    
                default:
                    // Default to today if no valid range is provided
                    $startDate = $now->copy()->startOfDay();
                    $endDate = $now->copy()->endOfDay();
            }
            
            \Log::info('DashboardController::getDataByRange - Date range calculated', [
                'startDate' => $startDate->toDateTimeString(),
                'endDate' => $endDate->toDateTimeString()
            ]);

            // Create a new request with the calculated date parameters
            $dateRequest = new Request([
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
            ]);
            
            \Log::info('DashboardController::getDataByRange - Calling getData method', [
                'dateRequest' => $dateRequest->all()
            ]);

            // Call the existing getData method with our date parameters
            return $this->getData($dateRequest);

        } catch (\Exception $e) {
            \Log::error('DashboardController::getDataByRange - Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'error' => 'Error processing date range: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getData(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date'
            ]);

            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            // Get count of users in date range
            $totalCustomers = User::where('role', 'user')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
            
            // Get count of orders in date range
            $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])
                ->count();
            
            // Get order statuses
            $orderStatuses = \App\Models\Order_status::all();
            
            // Define status IDs
            $pendingStatusId = 1;    // Chờ xử lý
            $shippingStatusId = 2;   // Đang vận chuyển
            $completedStatusId = 3;  // Thành công
            $cancelledStatusId = 4;  // Đã hủy
            
            // Calculate revenue by status
            $revenueByStatus = [];
            foreach ($orderStatuses as $status) {
                $revenueByStatus[$status->id] = [
                    'name' => $status->status_name,
                    'amount' => Order::where('status_id', $status->id)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->sum('total_amount'),
                    'count' => Order::where('status_id', $status->id)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->count()
                ];
            }
            
            // Calculate various revenue metrics
            $totalOrderValue = Order::whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_amount');
            
            $totalRevenue = Order::where('status_id', $completedStatusId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_amount');
            
            $totalCancelled = Order::where('status_id', $cancelledStatusId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_amount');
            
            $pendingRevenue = Order::whereIn('status_id', [$pendingStatusId, $shippingStatusId])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_amount');
            
            $totalRefunds = $totalCancelled;
            $netRevenue = $totalRevenue;
            $totalExpenses = $totalRevenue * 0.4; // 40% of revenue for demo
            $totalProfit = $totalRevenue - $totalExpenses;
            
            // Get chart data for date range
            $chartData = $this->getChartData($startDate, $endDate);
            
            // Get daily data
            $dailyData = $this->getDailyData($startDate, $endDate);
            
            // Check if we're using demo data
            $isDemoData = ($totalOrderValue == 0 || $totalOrders == 0);

            // Prepare response data
            $responseData = [
                'metrics' => [
                    'totalCustomers' => $totalCustomers,
                    'totalOrders' => $totalOrders,
                    'totalOrderValue' => $totalOrderValue,
                    'totalRevenue' => $totalRevenue,
                    'totalRefunds' => $totalRefunds,
                    'totalCancelled' => $totalCancelled,
                    'pendingRevenue' => $pendingRevenue,
                    'netRevenue' => $netRevenue,
                    'totalExpenses' => $totalExpenses,
                    'totalProfit' => $totalProfit
                ],
                'revenueByStatus' => $revenueByStatus,
                'chartData' => $chartData,
                'dailyData' => $dailyData,
                'is_demo_data' => $isDemoData,
                'date_range' => [
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d'),
                ]
            ];

            return response()->json($responseData);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error fetching dashboard data: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getChartData($startDate, $endDate)
    {
        // Calculate date range diff in months
        $diffInMonths = $startDate->diffInMonths($endDate) + 1;
        $months = [];
        $labels = [];
        
        // Get monthly data for the chart (up to 6 months)
        $monthCount = min($diffInMonths, 6);
        $currentDate = clone $startDate;
        
        for ($i = 0; $i < $monthCount; $i++) {
            $monthKey = $currentDate->format('Y-m');
            $months[] = $monthKey;
            $labels[] = $currentDate->format('M');
            $currentDate->addMonth();
        }
        
        // Get completed orders for revenue
        $completedStatusId = 3;
        
        // Get revenue by month
        $revenueByMonth = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status_id', $completedStatusId)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();
        
        // Get orders by month
        $ordersByMonth = Order::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();
        
        // Prepare chart data
        $revenue = [];
        $orders = [];
        $netRevenue = [];
        
        foreach ($months as $monthKey) {
            $monthlyRevenue = $revenueByMonth[$monthKey] ?? 0;
            $monthlyOrders = $ordersByMonth[$monthKey] ?? 0;
            
            // Use sample data if no real data
            if ($monthlyRevenue == 0 && empty($revenueByMonth)) {
                $monthlyRevenue = rand(50000, 200000);
            }
            
            if ($monthlyOrders == 0 && empty($ordersByMonth)) {
                $monthlyOrders = rand(5, 30);
            }
            
            // Calculate net revenue (60% of total)
            $monthlyNetRevenue = $monthlyRevenue * 0.6;
            
            // Add to chart data arrays (convert to thousands for better display)
            $revenue[] = round($monthlyRevenue / 1000, 1);
            $netRevenue[] = round($monthlyNetRevenue / 1000, 1);
            $orders[] = $monthlyOrders;
        }
        
        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'netRevenue' => $netRevenue,
            'orders' => $orders
        ];
    }
    
    private function getDailyData($startDate, $endDate)
    {
        // For short time ranges, use daily data
        // For longer ranges, use weekly data
        $diffInDays = $startDate->diffInDays($endDate);
        
        if ($diffInDays <= 7) {
            // Daily data for up to 7 days
            return $this->getDailyStats($startDate, $endDate);
        } else {
            // Use daily data of most recent 7 days
            $newStartDate = (clone $endDate)->subDays(6)->startOfDay();
            return $this->getDailyStats($newStartDate, $endDate);
        }
    }
    
    private function getDailyStats($startDate, $endDate)
    {
        $completedStatusId = 3;
        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        
        // Get revenue by day of week
        $dailyRevenueQuery = Order::where('status_id', $completedStatusId)
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->selectRaw('DAYOFWEEK(created_at) as day_of_week, SUM(total_amount) as daily_revenue')
            ->groupBy('day_of_week')
            ->pluck('daily_revenue', 'day_of_week')
            ->toArray();
        
        // Get orders by day of week
        $dailyOrdersQuery = Order::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->selectRaw('DAYOFWEEK(created_at) as day_of_week, COUNT(*) as daily_orders')
            ->groupBy('day_of_week')
            ->pluck('daily_orders', 'day_of_week')
            ->toArray();
        
        // Prepare results arrays
        $dailyRevenue = [];
        $dailyOrders = [];
        
        // Fill data (MySQL DAYOFWEEK: 1=Sunday, 2=Monday, ..., 7=Saturday)
        for ($i = 0; $i < 7; $i++) {
            $dayIndex = ($i + 1); // 1-7
            $dailyRevenue[] = $dailyRevenueQuery[$dayIndex] ?? 0;
            $dailyOrders[] = $dailyOrdersQuery[$dayIndex] ?? 0;
        }
        
        // Use sample data if no real data
        if (array_sum($dailyRevenue) == 0) {
            $dailyRevenue = [5000, 6200, 3800, 7500, 9200, 8400, 6700];
            $dailyOrders = [15, 18, 12, 22, 27, 25, 20];
        }
        
        return [
            'labels' => $dayNames,
            'revenue' => $dailyRevenue,
            'orders' => $dailyOrders
        ];
    }
} 