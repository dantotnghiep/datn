<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem danh sách yêu thích');
        }

        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with(['productVariation', 'productVariation.product'])
            ->get();

        return view('client.wishlist.index', compact('wishlists'));
    }

    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập để thêm sản phẩm vào danh sách yêu thích',
            ], 401);
        }

        $variationId = $request->input('variation_id');
        $userId = Auth::id();

        $wishlist = Wishlist::where('user_id', $userId)
            ->where('product_variation_id', $variationId)
            ->first();

        if ($wishlist) {
            // Đã có trong wishlist, xóa đi
            $wishlist->delete();
            return response()->json([
                'status' => 'success',
                'action' => 'removed',
                'message' => 'Đã xóa sản phẩm khỏi danh sách yêu thích'
            ]);
        } else {
            // Chưa có trong wishlist, thêm vào
            Wishlist::create([
                'user_id' => $userId,
                'product_variation_id' => $variationId
            ]);
            return response()->json([
                'status' => 'success',
                'action' => 'added',
                'message' => 'Đã thêm sản phẩm vào danh sách yêu thích'
            ]);
        }
    }

    public function remove($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $wishlist = Wishlist::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích');
        }

        return redirect()->back()->with('error', 'Không tìm thấy sản phẩm trong danh sách yêu thích');
    }
} 