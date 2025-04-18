<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\HomeCustomization;
use App\Models\HotProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function dashboard(Request $request)
    {
        $categories = Category::all(); // Lấy tất cả danh mục

        // Lấy 8 sản phẩm có lượt mua nhiều nhất
        $hotProducts = Product::with([
            'images' => function ($query) {
                $query->where('is_main', true);
            },
            'variations'
        ])
            ->join('variations', 'products.id', '=', 'variations.product_id')
            ->join('order_items', 'variations.id', '=', 'order_items.variation_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'completed') // Chỉ tính đơn hàng đã hoàn thành
            ->groupBy('products.id')
            ->orderByRaw('SUM(order_items.quantity) DESC')
            ->select('products.*')
            ->take(8)
            ->get();

        // Lấy 8 sản phẩm mới nhất
        $products = Product::with([
            'images' => function ($query) {
                $query->where('is_main', true);
            }
        ])->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Lấy 8 sản phẩm giảm giá nhiều nhất từ bảng variations
        $discountedProducts = Product::with([
            'images' => function ($query) {
                $query->where('is_main', true);
            },
            'variations' => function ($query) {
                $query->whereColumn('sale_price', '<', 'price') // Chỉ lấy variation có giảm giá
                    ->orderByRaw('(price - sale_price) DESC'); // Sắp xếp theo số tiền giảm cao nhất
            }
        ])->whereHas('variations', function ($query) {
            $query->whereColumn('sale_price', '<', 'price'); // Chỉ lấy sản phẩm có variation giảm giá
        })->take(8)->get();

        // Lấy sản phẩm theo danh mục (nếu có chọn)
        $selectedCategory = $request->category_id;
        $products = Product::with(['images', 'variations'])
            ->whereHas('category', function ($query) use ($selectedCategory) {
                if ($selectedCategory) {
                    $query->where('category.id', $selectedCategory);
                }
            })
            ->take(8)
            ->get();

        return view('client.index', compact('categories', 'hotProducts', 'products', 'discountedProducts', 'selectedCategory'));
    }

    public function category()
    {
        // Hiển thị danh sách danh mục
        return view('client.categories');
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        $categoryId = $request->input('category_id');

        $productsQuery = Product::with([
            'images' => function ($q) {
                $q->where('is_main', true);
            },
            'variations'
        ])
            ->where('status', 'active');

        // Apply category filter if selected
        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }

        // Apply search query if provided
        if ($query) {
            $productsQuery->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->orWhereHas('category', function ($q) use ($query) {
                        $q->where('name', 'LIKE', "%{$query}%");
                    });
            });
        }

        $products = $productsQuery->take(20)->get();
        $categories = Category::where('status', 'active')->get();

        return view('client.home.search', compact('products', 'query', 'categoryId', 'categories'));
    }

    public function searchSuggestions(Request $request)
    {
        $query = $request->input('query');
        $suggestions = [];

        if (strlen($query) >= 2) {
            // Product suggestions
            $productSuggestions = Product::where('name', 'LIKE', "%{$query}%")
                ->where('status', 'active')
                ->take(3)
                ->pluck('name')
                ->map(function ($name) {
                    return ['type' => 'product', 'value' => $name];
                });

            // Category suggestions
            $categorySuggestions = Category::where('name', 'LIKE', "%{$query}%")
                ->where('status', 'active')
                ->take(2)
                ->pluck('name')
                ->map(function ($name) {
                    return ['type' => 'category', 'value' => $name];
                });

            $suggestions = $productSuggestions->merge($categorySuggestions)->take(5)->toArray();
        }

        return response()->json($suggestions);
    }

    public function contact()
    {
        return view('client.home.contact');
    }

    public function about()
    {
        return view('client.home.about');
    }
}
