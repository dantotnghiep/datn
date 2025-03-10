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

        $hotProducts = HotProduct::with(['product.images', 'product.variations'])
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

        return view('client.index', compact('categories','hotProducts', 'products', 'discountedProducts', 'selectedCategory'));
    }

    public function category()
    {
        // Hiển thị danh sách danh mục 
        return view('client.categories');
    }

}
