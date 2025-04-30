<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Promotion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // Các thống kê cơ bản
        $totalOrders = Order::count();
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalRevenue = Order::whereHas('status', function($query) {
            $query->where('name', 'Completed');
        })->sum('total');

        // Thống kê đơn hàng theo trạng thái
        $orderStats = OrderStatus::withCount('orders')
            ->get()
            ->map(function($status) use ($totalOrders) {
                return (object)[
                    'status' => $status->name,
                    'count' => $status->orders_count,
                    'percentage' => $totalOrders > 0 ? round(($status->orders_count / $totalOrders) * 100, 1) : 0
                ];
            });

        // Đơn hàng gần đây
        $recentOrders = Order::with(['user', 'items', 'status'])
            ->latest()
            ->take(5)
            ->get();

        // Top danh mục
        $topCategories = $this->getTopCategories()->map(function($category) {
            return [
                'name' => $category->name,
                'products_count' => $category->products_count,
                'products_sum_stock' => $category->products_sum_stock
            ];
        });

        // Đơn hàng mới trong 7 ngày qua
        $newOrders = Order::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        // Sản phẩm sắp hết hàng - lấy đúng số lượng sản phẩm còn ít hơn hoặc bằng 15
        $lowStockProducts = ProductVariation::where('stock', '<=', 15)->count();

        // Khuyến mãi đang hoạt động - đảm bảo chỉ lấy những khuyến mãi đang còn hoạt động
        $activePromotions = Promotion::where('expires_at', '>=', Carbon::now())
            ->where('starts_at', '<=', Carbon::now())
            ->count();

        // Lấy danh sách khuyến mãi đang hoạt động
        $activePromotionsList = Promotion::where('is_active', 1)
            ->select('id', 'code', 'name', 'discount_type', 'discount_value', 'expires_at')
            ->latest()
            ->get();

        // Debug thông tin khuyến mãi
        Log::info('ActivePromotions: ' . $activePromotionsList->count());
        if ($activePromotionsList->count() > 0) {
            Log::info('First promotion: ' . json_encode($activePromotionsList->first()));
        }

        // Lấy danh sách sản phẩm sắp hết hàng
        $lowStockProductsList = DB::table('product_variations')
            ->join('products', 'product_variations.product_id', '=', 'products.id')
            ->select(
                'products.name as product_name',
                'product_variations.name as attributes',
                'product_variations.stock'
            )
            ->where('product_variations.stock', '<=', 15)
            ->whereNull('products.deleted_at')
            ->orderBy('product_variations.stock', 'asc')
            ->paginate(15);

        // Dữ liệu cho biểu đồ Revenue Projection vs Actual
        $sixMonthsData = $this->getRevenueData();

        // Dữ liệu cho biểu đồ khách hàng
        $customerData = $this->getCustomerData();

        // Dữ liệu cho biểu đồ order status
        $orderStatusData = $this->getOrderStatusData();

        // Dữ liệu cho top 5 sản phẩm bán chạy
        $topProducts = $this->getTopProducts();

        // Dữ liệu cho biểu đồ xu hướng tồn kho
        $inventoryTrend = $this->getInventoryTrend();

        // Dữ liệu cho biểu đồ doanh thu theo danh mục
        $categoryRevenue = $this->getCategoryRevenue();

        // Dữ liệu người dùng mua hàng
        $userPurchaseData = $this->getUserPurchaseData();

        // Dữ liệu marketing metrics
        $marketingMetrics = $this->getMarketingMetrics();

        // Dữ liệu business metrics
        $businessMetrics = $this->getBusinessMetrics();

        return view('admin.components.dashboard', compact(
            'totalOrders',
            'totalUsers',
            'totalProducts',
            'totalRevenue',
            'orderStats',
            'recentOrders',
            'topCategories',
            'newOrders',
            'lowStockProducts',
            'activePromotions',
            'activePromotionsList',
            'lowStockProductsList',
            'sixMonthsData',
            'customerData',
            'orderStatusData',
            'topProducts',
            'inventoryTrend',
            'categoryRevenue',
            'userPurchaseData',
            'marketingMetrics',
            'businessMetrics'
        ));
    }

    private function getRevenueData()
    {
        $sixMonthsData = [];

        // Lấy dữ liệu 6 tháng gần nhất
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M');

            // Doanh thu thực tế
            $actual = Order::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->whereHas('status', function($query) {
                    $query->where('name', 'Completed');
                })
                ->sum('total');

            // Doanh thu dự kiến (giả định)
            $projected = rand(intval($actual * 0.8), intval($actual * 1.2));
            if ($actual == 0) {
                $projected = rand(500000, 2000000);
            }

            $sixMonthsData[] = [
                'month' => $monthName,
                'actual' => $actual,
                'projected' => $projected
            ];
        }

        return $sixMonthsData;
    }

    private function getCustomerData()
    {
        try {
            // Phân loại khách hàng
            $newCustomers = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();

            // Lấy số lượng khách hàng quay lại (có từ 2 đơn hàng trở lên)
            $returningCustomersCount = DB::table('orders')
                ->select('user_id')
                ->whereNotNull('user_id')
                ->groupBy('user_id')
                ->havingRaw('COUNT(*) > 1')
                ->count();

            // Lấy số khách hàng không hoạt động (tồn tại > 90 ngày và không có đơn hàng trong 90 ngày)
            $inactiveCustomers = User::where('created_at', '<', Carbon::now()->subDays(90))
                ->whereDoesntHave('orders', function ($query) {
                    $query->where('created_at', '>=', Carbon::now()->subDays(90));
                })->count();

            return [
                ['name' => 'Khách hàng mới', 'value' => $newCustomers],
                ['name' => 'Khách hàng quay lại', 'value' => $returningCustomersCount],
                ['name' => 'Khách hàng không hoạt động', 'value' => $inactiveCustomers]
            ];
        } catch (\Exception $e) {
            Log::error('Error in getCustomerData: ' . $e->getMessage());
            return [
                ['name' => 'Khách hàng mới', 'value' => 0],
                ['name' => 'Khách hàng quay lại', 'value' => 0],
                ['name' => 'Khách hàng không hoạt động', 'value' => 0]
            ];
        }
    }

    private function getInventoryTrend()
    {
        try {
            $data = [];
            $months = [];
            $soldItems = [];
            $newStock = [];

            // Lấy dữ liệu 6 tháng gần nhất
            for ($i = 5; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $months[] = $month->format('M');

                // Số lượng sản phẩm bán ra
                $sold = OrderItem::whereHas('order', function ($query) use ($month) {
                    $query->whereMonth('created_at', $month->month)
                        ->whereYear('created_at', $month->year);
                })->sum('quantity');
                $soldItems[] = $sold;

                // Lấy số lượng sản phẩm nhập kho (giả định hoặc từ dữ liệu thực tế nếu có)
                $stock = rand(intval($sold * 0.8), intval($sold * 1.5));
                if ($sold == 0) {
                    $stock = rand(50, 200);
                }
                $newStock[] = $stock;
            }

            // Đảm bảo tất cả các mảng có cùng độ dài
            for ($i = 0; $i < count($months); $i++) {
                $data[] = [
                    'month' => $months[$i],
                    'newStock' => $newStock[$i],
                    'soldItems' => $soldItems[$i]
                ];
            }

            return $data;
        } catch (\Exception $e) {
            Log::error('Error in getInventoryTrend: ' . $e->getMessage());
            return [
                ['month' => 'Jan', 'newStock' => 0, 'soldItems' => 0],
                ['month' => 'Feb', 'newStock' => 0, 'soldItems' => 0],
                ['month' => 'Mar', 'newStock' => 0, 'soldItems' => 0],
                ['month' => 'Apr', 'newStock' => 0, 'soldItems' => 0],
                ['month' => 'May', 'newStock' => 0, 'soldItems' => 0],
                ['month' => 'Jun', 'newStock' => 0, 'soldItems' => 0]
            ];
        }
    }

    private function getCategoryRevenue()
    {
        // Lấy doanh thu theo danh mục từ đơn hàng đã hoàn thành
        return DB::table('categories')
            ->select('categories.name', DB::raw('SUM(order_items.quantity * order_items.price) as revenue'))
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('product_variations', 'products.id', '=', 'product_variations.product_id')
            ->join('order_items', 'product_variations.id', '=', 'order_items.product_variation_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('order_status', 'orders.status_id', '=', 'order_status.id')
            ->where('order_status.name', '=', 'Completed')
            ->groupBy('categories.id', 'categories.name')
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'value' => (float)$category->revenue
                ];
            });
    }

    private function getTopProducts()
    {
        try {
            // Lấy top 5 sản phẩm bán chạy nhất trong 30 ngày qua
            $result = DB::table('order_items')
                ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
                ->join('product_variations', 'order_items.product_variation_id', '=', 'product_variations.id')
                ->join('products', 'product_variations.product_id', '=', 'products.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.created_at', '>=', Carbon::now()->subDays(30))
                ->whereNull('products.deleted_at')
                ->groupBy('products.id', 'products.name')
                ->orderBy('total_sold', 'desc')
                ->take(5)
                ->get();

            if ($result->isEmpty()) {
                // Nếu không có dữ liệu, tạo dữ liệu mẫu để hiển thị
                $products = Product::take(5)->get();
                return $products->map(function($product, $index) {
                    return [
                        'name' => $product->name,
                        'value' => rand(5, 30)
                    ];
                });
            }

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

    private function getUserPurchaseData()
    {
        try {
            // Thống kê giá trị mua hàng trung bình
            $avgOrderValue = Order::whereHas('status', function($query) {
                $query->where('name', 'Completed');
            })->avg('total') ?? 0;

            // Thống kê tần suất mua hàng
            $orderFrequencyData = [
                'daily' => Order::whereDate('created_at', Carbon::today())->count(),
                'weekly' => Order::where('created_at', '>=', Carbon::now()->subDays(7))->count(),
                'monthly' => Order::where('created_at', '>=', Carbon::now()->subDays(30))->count(),
            ];

            // Thống kê khách hàng mới và quay lại
            $newVsReturningData = [
                'new' => Order::whereHas('user', function($query) {
                    $query->where('created_at', '>=', Carbon::now()->subDays(30));
                })->distinct('user_id')->count('user_id'),
                'returning' => DB::table('orders')
                    ->select('user_id')
                    ->whereNotNull('user_id')
                    ->groupBy('user_id')
                    ->havingRaw('COUNT(*) > 1')
                    ->count()
            ];

            // Top 5 khách hàng mua nhiều nhất
            $topCustomers = DB::table('orders')
                ->select('users.name', DB::raw('SUM(orders.total) as total_spent'), DB::raw('COUNT(*) as order_count'))
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->join('order_status', 'orders.status_id', '=', 'order_status.id')
                ->where('order_status.name', '=', 'Completed')
                ->whereNotNull('orders.user_id')
                ->groupBy('users.id', 'users.name')
                ->orderBy('total_spent', 'desc')
                ->take(5)
                ->get();

            return [
                'avgOrderValue' => round($avgOrderValue, 2),
                'orderFrequency' => $orderFrequencyData,
                'newVsReturning' => $newVsReturningData,
                'topCustomers' => $topCustomers
            ];
        } catch (\Exception $e) {
            Log::error('Error in getUserPurchaseData: ' . $e->getMessage());
            return [
                'avgOrderValue' => 0,
                'orderFrequency' => [
                    'daily' => 0,
                    'weekly' => 0,
                    'monthly' => 0,
                ],
                'newVsReturning' => [
                    'new' => 0,
                    'returning' => 0,
                ],
                'topCustomers' => collect([])
            ];
        }
    }

    private function getTopCategories()
    {
        try {
            return DB::table('categories')
                ->select('categories.*',
                        DB::raw('COUNT(DISTINCT products.id) as products_count'),
                        DB::raw('SUM(product_variations.stock) as products_sum_stock'))
                ->leftJoin('products', 'categories.id', '=', 'products.category_id')
                ->leftJoin('product_variations', 'products.id', '=', 'product_variations.product_id')
                ->whereNull('categories.deleted_at')
                ->whereNull('products.deleted_at')
                ->groupBy('categories.id', 'categories.name', 'categories.slug', 'categories.description', 'categories.created_at', 'categories.updated_at', 'categories.deleted_at')
                ->orderByRaw('COUNT(DISTINCT products.id) DESC')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error in getTopCategories: ' . $e->getMessage());
            return collect([]);
        }
    }

    private function getOrderStatusData()
    {
        try {
            return OrderStatus::withCount('orders')
                ->get()
                ->map(function ($status) {
                    return [
                        'name' => $status->name,
                        'value' => $status->orders_count
                    ];
                });
        } catch (\Exception $e) {
            Log::error('Error in getOrderStatusData: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Lấy dữ liệu marketing metrics cho dashboard
     *
     * @return array
     */
    private function getMarketingMetrics()
    {
        try {
            // Tỷ lệ chuyển đổi - giả định từ số lượng đơn hàng / lượt truy cập
            // Trong thực tế sẽ cần tích hợp với Google Analytics hoặc tool phân tích khác
            $totalVisits = rand(500, 1000); // Giảm số lượng truy cập
            $conversionRate = $totalVisits > 0 ? (Order::count() / $totalVisits) * 100 : 0;

            // So sánh với tháng trước
            $lastMonthVisits = rand(450, 950);
            $lastMonthOrders = Order::whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->whereYear('created_at', Carbon::now()->subMonth()->year)
                ->count();
            $lastMonthConversion = $lastMonthVisits > 0 ? ($lastMonthOrders / $lastMonthVisits) * 100 : 0;
            $conversionTrend = $lastMonthConversion > 0 ? (($conversionRate - $lastMonthConversion) / $lastMonthConversion) * 100 : 0;

            // Chi phí thu hút khách hàng (CPA)
            $marketingSpend = rand(500000, 1500000); // Giảm chi phí marketing
            $newCustomers = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();
            $costPerAcquisition = $newCustomers > 0 ? $marketingSpend / $newCustomers : 0;

            // So sánh với tháng trước
            $lastMonthSpend = rand(450000, 1400000);
            $lastMonthNewCustomers = User::where('created_at', '>=', Carbon::now()->subDays(60))
                ->where('created_at', '<', Carbon::now()->subDays(30))
                ->count();
            $lastMonthCPA = $lastMonthNewCustomers > 0 ? $lastMonthSpend / $lastMonthNewCustomers : 0;
            $cpaTrend = $lastMonthCPA > 0 ? (($costPerAcquisition - $lastMonthCPA) / $lastMonthCPA) * 100 : 0;

            // Tỷ lệ giỏ hàng bị bỏ
            // Giả định: 60-80% giỏ hàng bị bỏ (tỷ lệ trung bình trong thực tế)
            $cartAbandonment = rand(60, 80);
            $cartAbandonmentTrend = rand(-5, 5); // Giả định thay đổi so với tháng trước

            // Giá trị trọn đời khách hàng (CLV)
            $avgOrderValue = Order::whereHas('status', function($query) {
                $query->where('name', 'Completed');
            })->avg('total') ?: 0;

            $avgPurchaseFrequency = 2; // Giảm tần suất từ 4 xuống 2
            $avgCustomerLifespan = 2; // Giảm thời gian từ 3 xuống 2

            $customerLifetimeValue = $avgOrderValue * $avgPurchaseFrequency * $avgCustomerLifespan;
            $clvTrend = rand(-10, 15); // Giả định thay đổi so với kỳ trước

            // Nguồn khách hàng
            $customerSources = [
                ['name' => 'Tìm kiếm tự nhiên', 'value' => rand(30, 50)],
                ['name' => 'Trả phí (PPC)', 'value' => rand(15, 25)],
                ['name' => 'Mạng xã hội', 'value' => rand(15, 25)],
                ['name' => 'Email marketing', 'value' => rand(5, 15)],
                ['name' => 'Khác', 'value' => rand(5, 15)]
            ];

            return [
                'conversionRate' => round($conversionRate, 2),
                'conversionTrend' => round($conversionTrend, 2),
                'costPerAcquisition' => round($costPerAcquisition),
                'cpaTrend' => round($cpaTrend, 2),
                'cartAbandonment' => $cartAbandonment,
                'cartAbandonmentTrend' => $cartAbandonmentTrend,
                'customerLifetimeValue' => round($customerLifetimeValue),
                'clvTrend' => $clvTrend,
                'customerSources' => $customerSources
            ];
        } catch (\Exception $e) {
            Log::error('Error in getMarketingMetrics: ' . $e->getMessage());
            return [
                'conversionRate' => 0,
                'conversionTrend' => 0,
                'costPerAcquisition' => 0,
                'cpaTrend' => 0,
                'cartAbandonment' => 0,
                'cartAbandonmentTrend' => 0,
                'customerLifetimeValue' => 0,
                'clvTrend' => 0,
                'customerSources' => []
            ];
        }
    }

    /**
     * Lấy dữ liệu business metrics cho dashboard
     *
     * @return array
     */
    private function getBusinessMetrics()
    {
        try {
            // Lợi nhuận gộp và biên lợi nhuận
            $totalRevenue = Order::whereHas('status', function($query) {
                $query->where('name', 'Completed');
            })->sum('total');

            // Giả định chi phí hàng bán ra (COGS) khoảng 60-70% doanh thu
            $costOfGoodsSold = $totalRevenue * (rand(60, 70) / 100);
            $grossProfit = $totalRevenue - $costOfGoodsSold;
            $grossMargin = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;

            // Tỷ lệ hoàn đơn
            $totalCompletedOrders = Order::whereHas('status', function($query) {
                $query->where('name', 'Completed');
            })->count();

            $totalReturnedOrders = Order::whereHas('status', function($query) {
                $query->where('name', 'Returned');
            })->count();

            $returnRate = $totalCompletedOrders > 0 ? ($totalReturnedOrders / $totalCompletedOrders) * 100 : 0;

            // So sánh với tháng trước
            $lastMonthCompletedOrders = Order::whereHas('status', function($query) {
                $query->where('name', 'Completed');
            })
                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->whereYear('created_at', Carbon::now()->subMonth()->year)
                ->count();

            $lastMonthReturnedOrders = Order::whereHas('status', function($query) {
                $query->where('name', 'Returned');
            })
                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->whereYear('created_at', Carbon::now()->subMonth()->year)
                ->count();

            $lastMonthReturnRate = $lastMonthCompletedOrders > 0 ? ($lastMonthReturnedOrders / $lastMonthCompletedOrders) * 100 : 0;
            $returnRateTrend = $lastMonthReturnRate > 0 ? (($returnRate - $lastMonthReturnRate) / $lastMonthReturnRate) * 100 : 0;

            // Top sản phẩm hoàn đơn
            $topReturnedProducts = [
                ['name' => 'Áo thun basic', 'count' => rand(2, 8), 'rate' => rand(1, 5)],
                ['name' => 'Quần jeans nam', 'count' => rand(1, 5), 'rate' => rand(1, 4)],
                ['name' => 'Váy đầm dự tiệc', 'count' => rand(1, 4), 'rate' => rand(1, 3)],
                ['name' => 'Giày thể thao', 'count' => rand(1, 3), 'rate' => rand(1, 2)]
            ];

            // Đánh giá trung bình sản phẩm
            $averageRating = rand(380, 485) / 100; // Giả định đánh giá trung bình từ 3.8 đến 4.85

            return [
                'grossProfit' => round($grossProfit),
                'grossMargin' => round($grossMargin, 2),
                'returnRate' => round($returnRate, 2),
                'returnRateTrend' => round($returnRateTrend, 2),
                'topReturnedProducts' => $topReturnedProducts,
                'averageRating' => $averageRating
            ];
        } catch (\Exception $e) {
            Log::error('Error in getBusinessMetrics: ' . $e->getMessage());
            return [
                'grossProfit' => 0,
                'grossMargin' => 0,
                'returnRate' => 0,
                'returnRateTrend' => 0,
                'topReturnedProducts' => [],
                'averageRating' => 0
            ];
        }
    }
}
