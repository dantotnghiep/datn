<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        // Lấy danh sách wishlist của người dùng với phân trang
        $wishlistItems = Wishlist::where('user_id', $user->id)
            ->with([
                'product.images' => function ($query) {
                    $query->where('is_main', true); // Lấy ảnh chính
                },
                'product.defaultVariation'
            ])
            ->paginate(12); // 12 sản phẩm mỗi trang

        return view('client.wishlist.index', compact('wishlistItems'));
    }

    public function toggle(Request $request, $productId)
    {
        try {
            $user = auth()->user();

            // Debug: Kiểm tra $productId
            \Log::info('Product ID received in controller: ' . $productId);

            // Kiểm tra xem $productId có giá trị không
            if (!$productId) {
                return response()->json(['status' => 'error', 'message' => 'Product ID không hợp lệ'], 400);
            }

            // Kiểm tra xem sản phẩm đã có trong wishlist chưa
            $wishlistItem = Wishlist::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if ($wishlistItem) {
                // Nếu đã có, xóa khỏi wishlist
                $wishlistItem->delete();
                return response()->json(['status' => 'removed', 'message' => 'Đã xóa khỏi danh sách yêu thích']);
            } else {
                // Nếu chưa có, thêm vào wishlist
                $wishlist = Wishlist::create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                ]);

                // Debug: Kiểm tra bản ghi vừa tạo
                \Log::info('Wishlist created: ' . json_encode($wishlist));

                return response()->json(['status' => 'added', 'message' => 'Đã thêm vào danh sách yêu thích']);
            }
        } catch (\Exception $e) {
            // Ghi log lỗi chi tiết
            \Log::error('Error in WishlistController::toggle: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Đã có lỗi xảy ra: ' . $e->getMessage()], 500);
        }

    }
}