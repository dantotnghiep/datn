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
            
            // Lấy ID của các trạng thái quan trọng
            $completedStatusId = \App\Models\Order_status::where('status_name', 'Completed')->value('id');
            $cancelledStatusId = \App\Models\Order_status::where('status_name', 'Cancelled')->value('id');
            
            // Tính tổng giá trị tất cả đơn hàng (không bao gồm đơn hủy)
            $totalOrderValue = \App\Models\Order::where(function($query) use ($cancelledStatusId) {
                   $query->where('status_id', '!=', $cancelledStatusId)
                         ->orWhereNull('status_id');
               })
               ->sum('total_amount');
            
            // Tính doanh thu thực tế (chỉ tính các đơn đã hoàn thành và thanh toán)
            $totalRevenue = \App\Models\Order::where('payment_status', 'completed')
                        ->where('status_id', $completedStatusId)
                        ->sum('total_amount');
            
            // Tính tổng số tiền hoàn trả (đơn đã thanh toán nhưng sau đó hoàn tiền)
            $totalRefunds = \App\Models\Order::where('payment_status', 'refunded')
                        ->sum('total_amount');
            
            // Tính tổng giá trị đơn bị hủy
            $totalCancelled = \App\Models\Order::where('status_id', $cancelledStatusId)
                        ->sum('total_amount');
            
            // Tính doanh thu ròng (doanh thu thực - refunds)
            $netRevenue = max(0, $totalRevenue - $totalRefunds);
            
            // Doanh số chưa thu (đơn chưa hoàn thành, chưa thanh toán hoặc đang xử lý, và không phải đơn hủy)
            $pendingRevenue = \App\Models\Order::where(function($query) {
                    // Chưa thanh toán hoặc đang xử lý thanh toán
                    $query->where('payment_status', 'pending')
                          ->orWhere('payment_status', 'processing');
                })
                ->where(function($query) use ($cancelledStatusId, $completedStatusId) {
                    // Không phải đơn đã hủy và chưa hoàn thành
                    $query->where(function($q) use ($cancelledStatusId) {
                            $q->where('status_id', '!=', $cancelledStatusId)
                              ->orWhereNull('status_id');
                        })
                        ->where(function($q) use ($completedStatusId) {
                            $q->where('status_id', '!=', $completedStatusId)
                              ->orWhereNull('status_id');
                        });
                })
                ->sum('total_amount');
            
            // Get expenses (40% of revenue for demo)
            $totalExpenses = $netRevenue * 0.4;
            
            // Calculate profit
            $totalProfit = $netRevenue - $totalExpenses;
            
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
            // Lấy tháng hiện tại và tháng trước
            $currentMonth = now()->format('Y-m');
            $lastMonth = now()->subMonth()->format('Y-m');
            
            // Đếm đơn hàng theo tháng
            $ordersByMonth = \App\Models\Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
                                    ->groupBy('month')
                                    ->pluck('count', 'month')
                                    ->toArray();
            
            // Lấy doanh thu theo tháng (chỉ đơn hàng đã hoàn thành và thanh toán)
            $revenueByMonth = \App\Models\Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as total')
                                    ->where('payment_status', 'completed')
                                    ->where('status_id', $completedStatusId)
                                    ->groupBy('month')
                                    ->pluck('total', 'month')
                                    ->toArray();
            
            // Lấy hoàn tiền theo tháng
            $refundsByMonth = \App\Models\Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as total')
                                    ->where('payment_status', 'refunded')
                                    ->groupBy('month')
                                    ->pluck('total', 'month')
                                    ->toArray();
            
            // Tính đơn hàng tháng này và tháng trước
            $currentMonthOrders = $ordersByMonth[$currentMonth] ?? 0;
            $lastMonthOrders = $ordersByMonth[$lastMonth] ?? 0;
            
            // Tính doanh thu tháng này và tháng trước
            $currentMonthRevenue = $revenueByMonth[$currentMonth] ?? 0;
            $lastMonthRevenue = $revenueByMonth[$lastMonth] ?? 0;
            
            // Tính hoàn tiền tháng này và tháng trước
            $currentMonthRefunds = $refundsByMonth[$currentMonth] ?? 0;
            $lastMonthRefunds = $refundsByMonth[$lastMonth] ?? 0;
            
            // Tính doanh thu ròng
            $currentMonthNetRevenue = max(0, $currentMonthRevenue - $currentMonthRefunds);
            $lastMonthNetRevenue = max(0, $lastMonthRevenue - $lastMonthRefunds);
            
            // Tính % tăng trưởng
            $orderGrowth = $lastMonthOrders > 0 
                            ? round((($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100, 2)
                            : ($currentMonthOrders > 0 ? 100 : 0);
            
            $revenueGrowth = $lastMonthNetRevenue > 0 
                            ? round((($currentMonthNetRevenue - $lastMonthNetRevenue) / $lastMonthNetRevenue) * 100, 2)
                            : ($currentMonthNetRevenue > 0 ? 100 : 0);
            
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
            
            // Khởi tạo mảng dữ liệu với giá trị 0 cho mỗi tháng
            foreach ($months as $index => $month) {
                // Doanh thu gộp của tháng - tạo dữ liệu mẫu nếu không có dữ liệu thực
                $monthlyRevenue = isset($revenueByMonth[$month]) && $revenueByMonth[$month] > 0 
                                 ? $revenueByMonth[$month] 
                                 : rand(50000, 200000);
                
                // Hoàn tiền của tháng (nếu có)
                $monthlyRefunds = isset($refundsByMonth[$month]) ? $refundsByMonth[$month] : ($monthlyRevenue * 0.05);
                
                // Doanh thu ròng = doanh thu gộp - hoàn tiền (không để âm)
                $monthlyNetRevenue = max(0, $monthlyRevenue - $monthlyRefunds);
                
                // Tính chi phí hàng tháng (40% doanh thu ròng)
                $monthlyExpenses = $monthlyNetRevenue * 0.4;
                
                // Tính lợi nhuận hàng tháng
                $monthlyProfit = $monthlyNetRevenue - $monthlyExpenses;
                
                // Đếm số đơn hàng của tháng
                $monthlyOrderCount = isset($ordersByMonth[$month]) && $ordersByMonth[$month] > 0
                                   ? $ordersByMonth[$month]
                                   : rand(5, 30);
                
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
            $startDate = now()->subDays(6)->startOfDay();
            
            // Lấy doanh thu từng ngày trong tuần vừa qua
            $dailyRevenueQuery = \App\Models\Order::where('payment_status', 'completed')
                ->where('status_id', $completedStatusId)
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
            if (array_sum($dailyRevenue) == 0) {
                $dailyRevenue = [5000, 6200, 3800, 7500, 9200, 8400, 6700];
                $dailyOrders = [15, 18, 12, 22, 27, 25, 20];
            }
            
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
                'dayNames'
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


    public function store(StoreProductRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // Tạo sản phẩm
                $product = Product::create($request->validated());
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
                if ($request->has('variations')) {
                    foreach ($request->variations as $variationData) {
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
                                    }
                                }
                            }
                        }
                    }
                }
            });

            return redirect()->route('admin.product.product-list')
                ->with('success', 'Sản phẩm và biến thể đã được thêm thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())
                ->withInput();
        }
    }


    public function showVariations($id)
    {
        $product = Product::with(['variations.attributeValues'])->findOrFail($id);
        return view('admin.variation.variation-list-of-product', compact('product'));
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
}
