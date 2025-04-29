<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy sản phẩm hot với giá cao nhất và thấp nhất từ variations
        $hotProducts = Product::where('is_hot', 1)
            ->whereNull('deleted_at')
            ->with(['variations' => function($query) {
                $query->select('product_id', 'price')
                    ->whereNotNull('price')
                    ->where('price', '>', 0);
            }])
            ->withCount('variations')
            ->take(6)
            ->orderBy('created_at', 'desc')
            ->get();
        // Tính toán giá thấp nhất và cao nhất cho mỗi sản phẩm hot
        foreach ($hotProducts as $product) {
            if ($product->variations_count > 0) {
                $product->min_price = $product->variations->min('price');
                $product->max_price = $product->variations->max('price');
            } else {
                $product->min_price = $product->price;
                $product->max_price = $product->price;
            }
        }
        // Lấy sản phẩm không hot với giá cao nhất và thấp nhất từ variations
        $normalProducts = Product::where('is_hot', 0)
            ->whereNull('deleted_at')
            ->with(['variations' => function($query) {
                $query->select('product_id', 'price')
                    ->whereNotNull('price')
                    ->where('price', '>', 0);
            }])
            ->withCount('variations')
            ->take(6)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Tính toán giá thấp nhất và cao nhất cho mỗi sản phẩm thường
        foreach ($normalProducts as $product) {
            if ($product->variations_count > 0) {
                $product->min_price = $product->variations->min('price');
                $product->max_price = $product->variations->max('price');
            } else {
                $product->min_price = $product->price;
                $product->max_price = $product->price;
            }
        }
        
        return view('client.home.index', compact('hotProducts', 'normalProducts'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
        return view('client.product.detail');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
