<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Lấy danh mục nếu có filter
        $category = null;
        $categoryId = $request->input('category');
        if ($categoryId) {
            $category = Category::findOrFail($categoryId);
        }

        // Query cơ bản - sử dụng whereNull('deleted_at') thay vì where('is_active', 1)
        $query = Product::whereNull('deleted_at')
            ->with(['category', 'images', 'variations' => function ($query) {
                $query->select('id', 'product_id', 'price', 'sale_price', 'stock')
                    ->whereNotNull('price')
                    ->where('price', '>', 0);
            }])
            ->withCount('variations');

        // Filter theo danh mục nếu có
        if ($category) {
            $query->where('category_id', $category->id);
        }

        // Filter theo tìm kiếm
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter theo is_hot
        if ($request->has('featured')) {
            $query->where('is_hot', 1);
        }

        // Sort theo giá hoặc thời gian
        if ($request->has('sort')) {
            $sort = $request->input('sort');
            if ($sort == 'price_asc') {
                $query->orderByRaw('(SELECT MIN(price) FROM product_variations WHERE product_variations.product_id = products.id) ASC');
            } elseif ($sort == 'price_desc') {
                $query->orderByRaw('(SELECT MIN(price) FROM product_variations WHERE product_variations.product_id = products.id) DESC');
            } elseif ($sort == 'latest') {
                $query->latest();
            } elseif ($sort == 'oldest') {
                $query->oldest();
            }
        } else {
            // Mặc định sắp xếp theo thời gian tạo giảm dần
            $query->latest();
        }

        // Lấy các danh mục để hiển thị ở sidebar filter
        $categories = Category::all();

        // Phân trang sản phẩm
        $products = $query->paginate(12);

        // Tính toán giá thấp nhất và cao nhất cho mỗi sản phẩm
        foreach ($products as $product) {
            if ($product->variations_count > 0) {
                $product->min_price = $product->variations->min('price');
                $product->max_price = $product->variations->max('price');
            } else {
                $product->min_price = $product->price ?? 0;
                $product->max_price = $product->price ?? 0;
            }
        }

        return view('client.product.index', compact('products', 'categories', 'category'));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'images', 'variations'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Lấy các sản phẩm tương tự (cùng danh mục)
        $relatedProducts = Product::with(['category', 'images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->whereNull('deleted_at')
            ->take(6)
            ->get();

        return view('client.product.detail', compact('product', 'relatedProducts'));
    }
}
