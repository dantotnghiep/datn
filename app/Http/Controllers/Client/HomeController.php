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

    public function locdanhmuc(Request $request)
    {
        // Lấy slug từ cả GET hoặc POST
        $slug = $request->input('category_slug');

        // Tìm danh mục theo slug
        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            return redirect()->back()->with('error', 'Danh mục không tồn tại');
        }

        // Lấy sản phẩm theo danh mục (giả sử quan hệ: category -> products)
        $products = $category->products()->with(['images', 'variations'])->paginate(1);

        // Duy trì slug trong query string cho phân trang
        $products->appends(['category_slug' => $slug]);

        // Load lại view sản phẩm cùng sidebar
        $categories = Category::all();

        return view('client.product.locdanhmuc', compact('products', 'categories', 'slug'));
    }

    public function category()
    {
        // Hiển thị danh sách danh mục
        return view('client.categories');
    }

    public function loc(Request $request)
    {
        // Lấy giá trị price_range từ cả GET và POST
        $priceRange = $request->input('price_range');

        // Trường hợp chưa chọn khoảng giá
        if (empty($priceRange)) {
            $categories = Category::all();

            return view('client.product.loc', [
                'locs' => collect(),
                'message' => 'Vui lòng chọn khoảng giá để lọc sản phẩm.',
                'priceRange' => null,
                'categories' => $categories,
            ]);
        }

        // Query sản phẩm
        $query = Product::with(['variations', 'images', 'category']);

        $query->whereHas('variations', function ($q) use ($priceRange) {
            $q->where(function ($sub) use ($priceRange) {
                switch ($priceRange) {
                    case 1:
                        $sub->where(function ($s) {
                            $s->whereNotNull('sale_price')->where('sale_price', '<', 100000)
                                ->orWhere(function ($q) {
                                    $q->whereNull('sale_price')->where('price', '<', 100000);
                                });
                        });
                        break;
                    case 2:
                        $sub->where(function ($s) {
                            $s->whereNotNull('sale_price')->whereBetween('sale_price', [100000, 300000])
                                ->orWhere(function ($q) {
                                    $q->whereNull('sale_price')->whereBetween('price', [100000, 300000]);
                                });
                        });
                        break;
                    case 3:
                        $sub->where(function ($s) {
                            $s->whereNotNull('sale_price')->whereBetween('sale_price', [300000, 500000])
                                ->orWhere(function ($q) {
                                    $q->whereNull('sale_price')->whereBetween('price', [300000, 500000]);
                                });
                        });
                        break;
                    case 4:
                        $sub->where(function ($s) {
                            $s->whereNotNull('sale_price')->whereBetween('sale_price', [500000, 1000000])
                                ->orWhere(function ($q) {
                                    $q->whereNull('sale_price')->whereBetween('price', [500000, 1000000]);
                                });
                        });
                        break;
                    case 5:
                        $sub->where(function ($s) {
                            $s->whereNotNull('sale_price')->where('sale_price', '>', 1000000)
                                ->orWhere(function ($q) {
                                    $q->whereNull('sale_price')->where('price', '>', 1000000);
                                });
                        });
                        break;
                }
            });
        });

        // Phân trang và giữ lại price_range trên URL khi chuyển trang
        $locs = $query->paginate(2)->appends(['price_range' => $priceRange]);

        $categories = Category::all();

        return view('client.product.loc', compact('locs', 'priceRange', 'categories'));
    }

    public function timKiem(Request $request)
    {
        $keywords = $request->keywords_submit;
        $categories = Category::all();

        // Nếu không có từ khóa thì gán kết quả tìm kiếm là collection rỗng
        if (empty($keywords)) {
            $search_product = collect(); // Trả về collection rỗng
        } else {
            $search_product = Product::with(['variations', 'images', 'category'])
                ->where('name', 'like', '%' . $keywords . '%')
                ->paginate(2)->appends(['keywords_submit' => $keywords]); // giữ lại từ khóa khi phân trang
        }

        return view('client.product.search', compact('categories', 'search_product', 'keywords'));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->input('query');

        if ($query) {
            $products = Product::where('name', 'LIKE', '%' . $query . '%')->get();

            $output = '<ul class="dropdown-menu" style="display:block; position:relative;">';
            // foreach ($products as $product) {
            //     $output .= '<li class="li_search_ajax dropdown-item">' . $product->name . '</li>';
            // }
            if ($products->count() > 0) {
                foreach ($products as $product) {
                    $output .= '<li class="li_search_ajax dropdown-item">' . $product->name . '</li>';
                }
            } else {
                $output .= '<li class="dropdown-item text-muted">Không có sản phẩm nào phù hợp</li>';
            }
            $output .= '</ul>';

            return response($output);
        }

        return response('');
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
