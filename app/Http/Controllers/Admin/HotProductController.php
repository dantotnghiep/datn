<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HotProduct;
use App\Models\Product;

class HotProductController extends Controller
{
    // Hiển thị danh sách sản phẩm hot trong HomeSetting
    public function index()
    {
        // Lấy danh sách sản phẩm hot
    $hotProducts = HotProduct::with('product')->get();

    // Lấy danh sách sản phẩm chưa có trong danh sách hot
    $hotProductIds = HotProduct::pluck('product_id');
    $products = Product::whereNotIn('id', $hotProductIds)->get();
 
        return view('admin.homesetting.hot_products', compact('hotProducts', 'products'));
    }



    // Thêm sản phẩm hot
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        // Kiểm tra xem sản phẩm đã có trong danh sách hot chưa
        if (HotProduct::where('product_id', $request->product_id)->exists()) {
            return redirect()->back()->with('error', 'Sản phẩm này đã có trong danh sách hot!');
        }

        // Nếu chưa có, thêm vào
        HotProduct::create(['product_id' => $request->product_id]);
        return redirect()->back()->with('success', 'Thêm sản phẩm hot thành công!');
    }

    // Xóa sản phẩm hot
    public function destroy($id)
    {
        HotProduct::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Xóa sản phẩm hot thành công!');
    }
}
