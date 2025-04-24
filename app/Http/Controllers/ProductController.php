<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProductRequest;
use App\Models\AttributeValue;
use App\Models\Product_image;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ProductController extends Controller
{

    public function dashboard()
    {
        try {
            // Lấy dữ liệu 6 tháng gần nhất cho biểu đồ
            $endDate = now();
            $startDate = now()->subMonths(5)->startOfMonth();

            // Tạo mảng các tháng để đảm bảo có đủ 6 tháng dữ liệu
            $months = [];
            $chartLabels = [];
            $currentDate = clone $startDate;
            
            while ($currentDate <= $endDate) {
                $months[] = $currentDate->format('Y-m');
                $chartLabels[] = $currentDate->format('M');
                $currentDate->addMonth();
            }

            // Get count of users
            $totalCustomers = \App\Models\User::where('role', 'user')->count();
            
            // Get count of orders
            $totalOrders = \App\Models\Order::count();
            
            // Lấy tất cả trạng thái đơn hàng - đã biết trước ID cố định
            $orderStatuses = \App\Models\Order_status::all();
            
            // Đặt ID của các trạng thái quan trọng theo cấu trúc database mới
            $pendingStatusId = 1;    // Chờ xử lý - ID 1
            $shippingStatusId = 2;   // Đang vận chuyển - ID 2
            $completedStatusId = 3;  // Thành công - ID 3
            $cancelledStatusId = 4;  // Đã hủy - ID 4
            
            // Tính doanh số theo từng trạng thái
            $revenueByStatus = [];
            
            foreach ($orderStatuses as $status) {
                $revenueByStatus[$status->id] = [
                    'name' => $status->status_name,
                    'amount' => \App\Models\Order::where('status_id', $status->id)->sum('total_amount'),
                    'count' => \App\Models\Order::where('status_id', $status->id)->count()
                ];
            }
            
            // Tính tổng giá trị tất cả đơn hàng (bao gồm tất cả trạng thái)
            $totalOrderValue = \App\Models\Order::sum('total_amount');
            
            // Doanh thu thực tế (chỉ tính các đơn đã hoàn thành)
            $totalRevenue = \App\Models\Order::where('status_id', $completedStatusId)->sum('total_amount');
            
            // Tổng số tiền từ đơn đã hủy
            $totalCancelled = \App\Models\Order::where('status_id', $cancelledStatusId)->sum('total_amount');
            
            // Doanh thu chờ xử lý (đơn đang chờ xử lý và đang vận chuyển)
            $pendingRevenue = \App\Models\Order::whereIn('status_id', [$pendingStatusId, $shippingStatusId])->sum('total_amount');
            
            // Hoàn tiền (trong trường hợp này, cũng chính là giá trị đơn đã hủy)
            $totalRefunds = $totalCancelled;
            
            // Doanh thu ròng (doanh thu thực tế)
            $netRevenue = $totalRevenue;
            
            // Tính chi phí (40% của doanh thu thực tế cho demo)
            $totalExpenses = $totalRevenue * 0.4;
            
            // Tính lợi nhuận
            $totalProfit = $totalRevenue - $totalExpenses;
            
            // Get top selling products
            $topProducts = \App\Models\Product::with(['images', 'category'])
                        ->withCount(['variations as ordered_count' => function($query) {
                            $query->whereHas('orderItems', function($q) {
                                // Only include if relationship exists
                                $q->whereNotNull('order_id');
                            });
                        }])
                        ->orderBy('ordered_count', 'desc')
                        ->take(6)
                        ->get();
            
            // Get recent orders
            $recentOrders = \App\Models\Order::with(['items.variation.product.images', 'user', 'status'])
                        ->orderBy('created_at', 'desc')
                        ->take(10)
                        ->get();
            
            // Get best sellers
            $bestSellers = \App\Models\Category::withCount(['products as sales_count' => function($query) {
                                $query->whereHas('variations', function($q) {
                                    $q->whereHas('orderItems', function($oiq) {
                                        $oiq->whereNotNull('order_id');
                                    });
                                });
                            }])
                            ->orderBy('sales_count', 'desc')
                            ->take(6)
                            ->get();
            
            // Calculate monthly statistics
            // Lấy tháng hiện tại và tháng trước - FIX: Using clone to avoid modifying now()
            $now = now();
            $currentMonth = $now->format('Y-m');
            $lastMonth = (clone $now)->subMonth()->format('Y-m');
            
            // Đếm đơn hàng theo tháng
            $ordersByMonth = \App\Models\Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
                                    ->groupBy('month')
                                    ->pluck('count', 'month')
                                    ->toArray();
            
            // Lấy doanh thu theo tháng (chỉ đơn hàng đã hoàn thành)
            $revenueByMonth = \App\Models\Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as total')
                                    ->where('status_id', $completedStatusId)
                                    ->groupBy('month')
                                    ->pluck('total', 'month')
                                    ->toArray();
            
            // Lấy doanh thu đơn hủy theo tháng
            $refundsByMonth = \App\Models\Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as total')
                                    ->where('status_id', $cancelledStatusId)
                                    ->groupBy('month')
                                    ->pluck('total', 'month')
                                    ->toArray();
            
            // Tính đơn hàng tháng này và tháng trước
            $currentMonthOrders = $ordersByMonth[$currentMonth] ?? 0;
            $lastMonthOrders = $ordersByMonth[$lastMonth] ?? 0;
            
            // Tính doanh thu tháng này và tháng trước
            $currentMonthRevenue = $revenueByMonth[$currentMonth] ?? 0;
            $lastMonthRevenue = $revenueByMonth[$lastMonth] ?? 0;
            
            // Tính % tăng trưởng
            $orderGrowth = $lastMonthOrders > 0 
                            ? round((($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100, 2)
                            : ($currentMonthOrders > 0 ? 100 : 0);
            
            $revenueGrowth = $lastMonthRevenue > 0 
                            ? round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 2)
                            : ($currentMonthRevenue > 0 ? 100 : 0);
            
            // For the Revenue Chart - Prepare data for each month
            $revenueChartData = [];
            $expenseChartData = [];
            $profitChartData = [];
            $orderCountChartData = [];
            
            // Đảm bảo có ít nhất dữ liệu mẫu cho biểu đồ
            if (empty($months)) {
                $months = ['Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr'];
                $chartLabels = $months;
            }
            
            // FIX: Khởi tạo mảng dữ liệu cho mỗi tháng - Cải thiện logic tìm kiếm dữ liệu thực tế
            foreach ($months as $index => $monthKey) {
                // Tìm tháng trong dữ liệu thực tế - Cần xử lý format tháng cho đúng
                // $monthKey là 'Y-m' format, nhưng chúng ta chỉ hiển thị 'M' format trong chart
                
                // Doanh thu gộp của tháng - chỉ sử dụng dữ liệu mẫu khi không có dữ liệu thực
                $monthlyRevenue = $revenueByMonth[$monthKey] ?? 0;
                
                // Chỉ dùng dữ liệu mẫu nếu thực sự không có dữ liệu thực
                if ($monthlyRevenue == 0 && empty($revenueByMonth)) {
                    $monthlyRevenue = rand(50000, 200000);
                }
                
                // Tính chi phí hàng tháng (40% doanh thu thực tế)
                $monthlyExpenses = $monthlyRevenue * 0.4;
                
                // Tính lợi nhuận hàng tháng
                $monthlyProfit = $monthlyRevenue - $monthlyExpenses;
                
                // Đếm số đơn hàng của tháng
                $monthlyOrderCount = $ordersByMonth[$monthKey] ?? 0;
                
                // Chỉ dùng dữ liệu mẫu nếu thực sự không có dữ liệu thực
                if ($monthlyOrderCount == 0 && empty($ordersByMonth)) {
                    $monthlyOrderCount = rand(5, 30);
                }
                
                // Thêm dữ liệu vào mảng kết quả (chuyển đổi sang đơn vị nghìn để dễ đọc)
                $revenueChartData[] = round($monthlyRevenue / 1000, 1);
                $expenseChartData[] = round($monthlyExpenses / 1000, 1);
                $profitChartData[] = round($monthlyProfit / 1000, 1);
                $orderCountChartData[] = $monthlyOrderCount;
            }
            
            // Daily revenue data for last 7 days
            $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            
            // Lấy dữ liệu doanh thu 7 ngày gần nhất
            $endDate = now();
            $startDate = (clone $endDate)->subDays(6)->startOfDay(); // FIX: Using clone
            
            // Lấy doanh thu từng ngày trong tuần vừa qua (chỉ đơn hàng thành công)
            $dailyRevenueQuery = \App\Models\Order::where('status_id', $completedStatusId)
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->selectRaw('DAYOFWEEK(created_at) as day_of_week, SUM(total_amount) as daily_revenue')
                ->groupBy('day_of_week')
                ->pluck('daily_revenue', 'day_of_week')
                ->toArray();
            
            // Lấy số lượng đơn hàng theo ngày
            $dailyOrdersQuery = \App\Models\Order::where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->selectRaw('DAYOFWEEK(created_at) as day_of_week, COUNT(*) as daily_orders')
                ->groupBy('day_of_week')
                ->pluck('daily_orders', 'day_of_week')
                ->toArray();
            
            // MySQL trả về DAYOFWEEK từ 1 (Chủ nhật) đến 7 (Thứ bảy)
            // Khởi tạo mảng dữ liệu cho 7 ngày
            $dailyRevenue = [];
            $dailyOrders = [];
            
            // Điền dữ liệu thực tế vào mảng
            for ($i = 0; $i < 7; $i++) {
                // DAYOFWEEK trong MySQL: 1 = Chủ Nhật, 2 = Thứ Hai, ..., 7 = Thứ Bảy
                $dayIndex = ($i + 1); // 1-7
                
                // Lấy doanh thu và số lượng đơn hàng, nếu không có thì gán 0
                $dailyRevenue[] = $dailyRevenueQuery[$dayIndex] ?? 0;
                $dailyOrders[] = $dailyOrdersQuery[$dayIndex] ?? 0;
            }
            
            // Nếu không có dữ liệu thực, tạo dữ liệu mẫu để kiểm tra giao diện
            // FIX: Chỉ dùng dữ liệu mẫu khi thực sự không có dữ liệu thực
            if (array_sum($dailyRevenue) == 0 && empty($dailyRevenueQuery)) {
                $dailyRevenue = [5000, 6200, 3800, 7500, 9200, 8400, 6700];
                $dailyOrders = [15, 18, 12, 22, 27, 25, 20];
            }
            
            // FIX: Thêm flag để đánh dấu dữ liệu có phải dữ liệu mẫu
            $usingDemoData = (empty($revenueByMonth) || array_sum($dailyRevenue) == 0);
            
            // Campaign data
            $campaignSources = ['Direct', 'Social', 'Email', 'Referral', 'Organic'];
            $campaignData = [];
            
            foreach ($campaignSources as $source) {
                $campaignData[] = rand(100, 1000);
            }
            
            return view('admin.dashboard', compact(
                'totalCustomers', 
                'totalOrders', 
                'totalOrderValue',
                'totalRevenue',
                'totalRefunds',
                'totalCancelled',
                'pendingRevenue',
                'netRevenue', 
                'totalExpenses',
                'totalProfit',
                'topProducts',
                'recentOrders',
                'bestSellers',
                'orderGrowth',
                'revenueGrowth',
                'chartLabels',
                'revenueChartData',
                'expenseChartData',
                'profitChartData',
                'orderCountChartData',
                'campaignSources',
                'campaignData',
                'dailyRevenue',
                'dailyOrders',
                'dayNames',
                'revenueByStatus',
                'orderStatuses',
                'usingDemoData'
            ));
        } catch (\Exception $e) {
            // Log the error and return a simple dashboard with error message
            \Illuminate\Support\Facades\Log::error('Dashboard error: ' . $e->getMessage());
            
            return view('admin.dashboard', [
                'totalCustomers' => 0,
                'totalOrders' => 0,
                'totalOrderValue' => 0,
                'totalRevenue' => 0,
                'totalRefunds' => 0,
                'totalCancelled' => 0,
                'pendingRevenue' => 0,
                'netRevenue' => 0,
                'totalExpenses' => 0,
                'totalProfit' => 0,
                'topProducts' => collect(),
                'recentOrders' => collect(),
                'bestSellers' => collect(),
                'orderGrowth' => 0,
                'revenueGrowth' => 0,
                'chartLabels' => [],
                'revenueChartData' => [],
                'expenseChartData' => [],
                'profitChartData' => [],
                'orderCountChartData' => [],
                'campaignSources' => [],
                'campaignData' => [],
                'dailyRevenue' => [],
                'dailyOrders' => [],
                'dayNames' => [],
                'revenueByStatus' => [],
                'orderStatuses' => collect(),
                'error' => 'There was an error loading the dashboard statistics: ' . $e->getMessage()
            ]);
        }
    }

    public function listproduct()
    {
        return view('client.product.list-product');
    }
    public function show($id)
    {
        $product = Product::with([
            'category',
            'images',
            'variations.attributeValues.attribute',
        ])->findOrFail($id);
    
        // Lấy ra tất cả value của từng attribute
        $attributeValues = $product->variations->flatMap(function ($variation) {
            return $variation->attributeValues;
        });
    
        $colorValues = $attributeValues
            ->where('attribute_id', 2)
            ->unique('value')
            ->values();
    
        $sizeValues = $attributeValues
            ->where('attribute_id', 1)
            ->unique('value')
            ->values();
    
        return view('client.product.product-details', compact('product', 'colorValues', 'sizeValues'));
    }

    public function index()
    {
        $products = Product::with(['variations', 'images', 'category'])->orderBy('created_at', 'desc')->get();
        return view('admin.product.product-list', compact('products'));
    }


    public function create()
    {
        $categories = Category::all();
        $attributes = Attribute::with('values')->get();

        return view('admin.product.add-product', compact('categories', 'attributes'));
    }


    public function store(Request $request)
    {
        // Xóa dd() ở đây để cho phép request tiếp tục xử lý
        Log::info('Product store request received', ['request_data' => $request->except(['_token', 'main_image', 'additional_images'])]);
        
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'main_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'variations' => 'required|array',
            'variations.*.sku' => 'required|string|unique:variations,sku',
            'variations.*.price' => 'required|numeric|min:0',
            'variations.*.stock' => 'required|integer|min:0',
        ], [
            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'slug.required' => 'Slug là bắt buộc.',
            'slug.unique' => 'Slug đã tồn tại, vui lòng chọn slug khác.',
            'category_id.required' => 'Danh mục là bắt buộc.',
            'main_image.required' => 'Hình ảnh chính là bắt buộc.',
            'main_image.image' => 'File phải là hình ảnh.',
            'main_image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg.',
            'main_image.max' => 'Hình ảnh không được vượt quá 2MB.',
            'variations.required' => 'Phải có ít nhất một biến thể sản phẩm.',
            'variations.*.sku.required' => 'SKU là bắt buộc cho mỗi biến thể.',
            'variations.*.sku.unique' => 'SKU của biến thể đã tồn tại.',
            'variations.*.price.required' => 'Giá là bắt buộc cho mỗi biến thể.',
            'variations.*.price.numeric' => 'Giá phải là số.',
            'variations.*.price.min' => 'Giá phải lớn hơn hoặc bằng 0.',
            'variations.*.stock.required' => 'Số lượng là bắt buộc cho mỗi biến thể.',
            'variations.*.stock.integer' => 'Số lượng phải là số nguyên.',
            'variations.*.stock.min' => 'Số lượng phải lớn hơn hoặc bằng 0.',
        ]);

        if ($validator->fails()) {
            Log::info('Validation errors:', $validator->errors()->toArray());
            
            // Không hiển thị thông báo lỗi chung
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                // Tạo sản phẩm
                $product = Product::create([
                    'name' => $request->name,
                    'slug' => $request->slug,
                    'category_id' => $request->category_id,
                    'description' => $request->description,
                    'status' => $request->status
                ]);

                // Xử lý upload hình ảnh chính
                if ($request->hasFile('main_image')) {
                    $mainImage = new ProductImage();
                    $mainImage->product_id = $product->id; // Liên kết với sản phẩm
                    $mainImage->url = $request->file('main_image')->store('products');
                    $mainImage->is_main = true; // Đánh dấu là hình chính
                    $mainImage->save();
                }

                // Xử lý ảnh phụ
                if ($request->hasFile('additional_images')) {
                    foreach ($request->file('additional_images') as $image) {
                        $additionalImage = new ProductImage();
                        $additionalImage->product_id = $product->id;
                        $additionalImage->url = $image->store('products');
                        $additionalImage->is_main = false; // Đánh dấu là hình phụ
                        $additionalImage->save();
                    }
                }

                // Xử lý biến thể
                if ($request->has('variations') && is_array($request->variations)) {
                    foreach ($request->variations as $index => $variationData) {
                        try {
                            $variation = Variation::create([
                                'product_id' => $product->id,
                                'sku' => $variationData['sku'],
                                'price' => $variationData['price'],
                                'stock' => $variationData['stock'],
                                'sale_price' => !empty($variationData['sale_price']) ? $variationData['sale_price'] : null,
                                'sale_start' => !empty($variationData['sale_start']) ? date('Y-m-d H:i:s', strtotime($variationData['sale_start'])) : null,
                                'sale_end' => !empty($variationData['sale_end']) ? date('Y-m-d H:i:s', strtotime($variationData['sale_end'])) : null,
                            ]);
                            
                            if (isset($variationData['attribute_values']) && is_array($variationData['attribute_values'])) {
                                $validIds = AttributeValue::whereIn('id', $variationData['attribute_values'])->pluck('id')->toArray();
                                foreach ($variationData['attribute_values'] as $attributeValueId) {
                                    if (in_array($attributeValueId, $validIds)) {
                                        try {
                                            $variation->attributeValues()->attach($attributeValueId);
                                        } catch (\Exception $e) {
                                            Log::error('Error attaching attribute value:', ['error' => $e->getMessage()]);
                                            throw new \Exception('Lỗi khi gắn thuộc tính cho biến thể ' . ($index + 1) . ': ' . $e->getMessage());
                                        }
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                            Log::error('Error creating variation:', ['index' => $index, 'error' => $e->getMessage()]);
                            throw new \Exception('Lỗi khi tạo biến thể ' . ($index + 1) . ': ' . $e->getMessage());
                        }
                    }
                } else {
                    throw new \Exception('Không có biến thể nào được gửi lên. Vui lòng tạo ít nhất một biến thể.');
                }
            });

            return redirect()->route('admin.product.product-list')
                ->with('success', 'Sản phẩm và biến thể đã được thêm thành công!');
        } catch (\Exception $e) {
            Log::error('Product creation error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->except(['_token', 'main_image', 'additional_images']),
            ]);
            
            // Hiển thị thông báo lỗi ngắn gọn
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }


    public function showVariations($id)
    {
        $product = Product::with(['variations.attributeValues'])->findOrFail($id);
        return view('admin.variation.variation-list-of-product', compact('product'));
    }

    public function storeVariation(Request $request, $productId)
    {
        $validator = \Validator::make($request->all(), [
            'variations' => 'required|array',
            'variations.*.sku' => 'required|string|unique:variations,sku',
            'variations.*.price' => 'required|numeric|min:0',
            'variations.*.sale_price' => 'nullable|numeric|min:0',
            'variations.*.stock' => 'required|integer|min:0',
            'variations.*.sale_start' => 'nullable|date',
            'variations.*.sale_end' => 'nullable|date',
            'variations.*.attribute_values' => 'required|array',
            'variations.*.attribute_values.*' => 'exists:attribute_values,id',
        ], [
            'variations.required' => 'Phải có ít nhất một biến thể.',
            'variations.*.sku.required' => 'SKU là bắt buộc.',
            'variations.*.sku.unique' => 'SKU đã tồn tại.',
            'variations.*.price.required' => 'Giá là bắt buộc.',
            'variations.*.price.numeric' => 'Giá phải là số.',
            'variations.*.price.min' => 'Giá phải lớn hơn hoặc bằng 0.',
            'variations.*.sale_price.numeric' => 'Giá khuyến mãi phải là số.',
            'variations.*.sale_price.min' => 'Giá khuyến mãi phải lớn hơn hoặc bằng 0.',
            'variations.*.stock.required' => 'Số lượng là bắt buộc.',
            'variations.*.stock.integer' => 'Số lượng phải là số nguyên.',
            'variations.*.stock.min' => 'Số lượng phải lớn hơn hoặc bằng 0.',
            'variations.*.sale_start.date' => 'Ngày bắt đầu khuyến mãi không hợp lệ.',
            'variations.*.sale_end.date' => 'Ngày kết thúc khuyến mãi không hợp lệ.',
            'variations.*.attribute_values.required' => 'Phải chọn ít nhất một thuộc tính.',
            'variations.*.attribute_values.*.exists' => 'Giá trị thuộc tính không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $productId) {
                foreach ($request->variations as $variationData) {
                    $variation = Variation::create([
                        'product_id' => $productId,
                        'sku' => $variationData['sku'],
                        'price' => $variationData['price'],
                        'sale_price' => $variationData['sale_price'] ?? null,
                        'stock' => $variationData['stock'],
                        'sale_start' => $variationData['sale_start'] ?? null,
                        'sale_end' => $variationData['sale_end'] ?? null,
                    ]);

                    // Attach attribute values
                    if (isset($variationData['attribute_values'])) {
                        $variation->attributeValues()->attach($variationData['attribute_values']);
                    }
                }
            });

            return redirect()->back()->with('success', 'Các biến thể đã được thêm thành công!');
        } catch (\Exception $e) {
            Log::error('Error creating variations: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi tạo biến thể: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $attributes = Attribute::with('values')->get(); // Lấy tất cả attributes và values
        $productAttributes = $product->productAttributes()
            ->with('attribute', 'attributeValues')
            ->get();
        return view('admin.product.edit-product', compact('product', 'categories', 'productAttributes', 'attributes'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categphpories,id',
            'description' => 'nullable|string',
            'sale_start' => 'nullable|date',
            'sale_end' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'attributes' => 'nullable|array',
            'attributes.*.attribute_id' => 'nullable|exists:attributes,id',
            'attributes.*.value_ids' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            // Update product details
            $product = Product::findOrFail($id);
            $product->update([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'price' => $validated['price'],
                'sale_price' => $validated['sale_price'],
                'quantity' => $validated['quantity'],
                'category_id' => $validated['category_id'],
                'description' => $validated['description'],
                'sale_start' => $validated['sale_start'],
                'sale_end' => $validated['sale_end'],
                'status' => $validated['status'],
            ]);

            // Update main image if provided
            if ($request->hasFile('main_image')) {
                if ($product->main_image) {
                    Storage::delete($product->main_image);
                }
                $product->main_image = $request->file('main_image')->store('products');
                $product->save();
            }

            // Update additional images if provided
            if ($request->hasFile('additional_images')) {
                foreach ($product->additionalImages as $image) {
                    Storage::delete($image->url);
                    $image->delete();
                }

                foreach ($request->file('additional_images') as $additionalImage) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'url' => $additionalImage->store('products'),
                    ]);
                }
            }

            // Handle attributes
            if (isset($validated['attributes'])) {
                // Delete old attributes and values
                $product->productAttributes()->delete();

                // Add new attributes and values
            }

            DB::commit();
            return redirect()->route('admin.product.product-list')->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);


        $hasVariations = $product->variations()->exists();

        if ($hasVariations) {

            return redirect()->back()->with('error', 'Sản phẩm có biến thể. Không thể xóa trực tiếp! Cần Xóa Từ Các Biến Thể Trong Sản Phẩm');
        }

        try {
            $product->delete();
            return redirect()->back()->with('success', 'Sản phẩm đã được xóa thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Cung cấp dữ liệu dashboard cho các khoảng thời gian khác nhau
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboardData(Request $request)
    {
        try {
            $range = $request->input('range', 'today');
            
            // Xác định khoảng thời gian dựa trên tham số
            $endDate = now();
            $startDate = now(); // Mặc định là ngày hôm nay
            
            // Nếu là khoảng thời gian tùy chỉnh
            if ($range === 'customRange') {
                if ($request->has('startDate') && $request->has('endDate')) {
                    $startDate = \Carbon\Carbon::parse($request->input('startDate'))->startOfDay();
                    $endDate = \Carbon\Carbon::parse($request->input('endDate'))->endOfDay();
                } else {
                    return response()->json([
                        'error' => 'Thiếu tham số startDate hoặc endDate cho khoảng thời gian tùy chỉnh'
                    ], 400);
                }
            } else if ($range === 'all') {
                // Không giới hạn thời gian - lấy từ đơn hàng đầu tiên
                $firstOrder = \App\Models\Order::orderBy('created_at', 'asc')->first();
                if ($firstOrder) {
                    $startDate = Carbon::parse($firstOrder->created_at)->startOfDay();
                } else {
                    // Nếu không có đơn hàng nào, lấy từ đầu tháng trước
                    $startDate = now()->subMonth()->startOfMonth();
                }
            } else {
                switch ($range) {
                    case 'yesterday':
                        $startDate = now()->subDay()->startOfDay();
                        $endDate = now()->subDay()->endOfDay();
                        break;
                    case 'last7days':
                        $startDate = now()->subDays(6)->startOfDay();
                        break;
                    case 'last30days':
                        $startDate = now()->subDays(29)->startOfDay();
                        break;
                    case 'thisMonth':
                        $startDate = now()->startOfMonth();
                        break;
                    case 'lastMonth':
                        $startDate = now()->subMonth()->startOfMonth();
                        $endDate = now()->subMonth()->endOfMonth();
                        break;
                    case 'today':
                    default:
                        $startDate = now()->startOfDay();
                        $endDate = now()->endOfDay();
                        break;
                }
            }
            
            // Đặt ID của các trạng thái quan trọng theo cấu trúc database
            $pendingStatusId = 1;    // Chờ xử lý - ID 1
            $shippingStatusId = 2;   // Đang vận chuyển - ID 2
            $completedStatusId = 3;  // Thành công - ID 3
            $cancelledStatusId = 4;  // Đã hủy - ID 4
            
            // Lấy số lượng khách hàng trong khoảng thời gian
            $totalCustomers = \App\Models\User::where('role', 'user')
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->count();
            
            // Lấy số lượng đơn hàng trong khoảng thời gian
            $totalOrders = \App\Models\Order::whereBetween('created_at', [$startDate, $endDate])
                                ->count();
            
            // Doanh thu thực tế (chỉ tính các đơn đã hoàn thành) trong khoảng thời gian
            $totalRevenue = \App\Models\Order::where('status_id', $completedStatusId)
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->sum('total_amount');
            
            // Tổng số tiền từ đơn đã hủy trong khoảng thời gian
            $totalCancelled = \App\Models\Order::where('status_id', $cancelledStatusId)
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->sum('total_amount');
            
            // Doanh thu chờ xử lý (đơn đang chờ xử lý và đang vận chuyển) trong khoảng thời gian
            $pendingRevenue = \App\Models\Order::whereIn('status_id', [$pendingStatusId, $shippingStatusId])
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->sum('total_amount');
            
            // Hoàn tiền trong khoảng thời gian (trong trường hợp này, cũng chính là giá trị đơn đã hủy)
            $totalRefunds = $totalCancelled;
            
            // Doanh thu ròng (doanh thu thực tế) trong khoảng thời gian
            $netRevenue = $totalRevenue;
            
            // Tính chi phí (40% của doanh thu thực tế cho demo)
            $totalExpenses = $totalRevenue * 0.4;
            
            // Tính lợi nhuận
            $totalProfit = $totalRevenue - $totalExpenses;
            
            // Dữ liệu trả về
            return response()->json([
                'totalCustomers' => $totalCustomers,
                'totalOrders' => $totalOrders,
                'totalRevenue' => $totalRevenue,
                'totalRefunds' => $totalRefunds,
                'totalCancelled' => $totalCancelled,
                'pendingRevenue' => $pendingRevenue,
                'netRevenue' => $netRevenue,
                'totalExpenses' => $totalExpenses,
                'totalProfit' => $totalProfit,
                'range' => $range,
                'timeRange' => [
                    'startDate' => $startDate->format('Y-m-d H:i:s'),
                    'endDate' => $endDate->format('Y-m-d H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}