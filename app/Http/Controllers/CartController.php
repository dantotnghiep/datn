<?php

namespace App\Http\Controllers;

use App\Cart as AppCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\Product;
use PHPUnit\Framework\Constraint\Count;

class CartController extends Controller
{

    // public function AddCart(Request $request,$id) {
    //     $product = DB::table('products')->where('id', $id)->first();

    //     if ($product != null) {
    //         $oldCart = Session()->has('Cart') ? Session()->get('Cart') : null;
    //         $newCart = new Cart($oldCart);
    //         $newCart->AddCart($product, $id);
    //         $request->Session()->put('Cart', $newCart);

    //     }
    //     return view('client.cart.model-cart');
    // }

    // public function DeleteItemCart(Request $request,$id) {

    //         $oldCart = Session()->has('Cart') ? Session()->get('Cart') : null;
    //         $newCart = new Cart($oldCart);
    //         $newCart->DeleteItemCart($id);
    //         if(Count( $newCart->products) > 0){
    //             $request->Session()->put('Cart', $newCart);
    //         }else{
    //             $request->Session()->forget('Cart');

    //         }
    //         return view('client.cart.model-cart');
    // }

    // public function checkout()
    // {
    //     return view('client.cart.checkout');
    // }

    // public function order()
    // {
    //     return view('client.cart.order');
    // }
    public function index()
    {
        $cartItems = Cart::all();
        return view('client.cart.cart', compact('cartItems'));
    }

    public function addToCart(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);

            // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
            $cartItem = Cart::where('product_id', $id)->first();

            if ($cartItem) {
                // Nếu sản phẩm đã tồn tại, tăng số lượng
                $cartItem->quantity += 1;
                $cartItem->save();
            } else {
                // Nếu sản phẩm chưa tồn tại, tạo mới
                Cart::create([
                    'product_id' => $product->id,
                    'category_id' => $product->category_id,
                    'name' => $product->name,
                    'quantity' => 1,
                    'price' => $product->price,
                    'sale_price' => $product->sale_price,
                    'main_image' => $product->mainImage ? $product->mainImage->url : null,
                    'attributes' => json_encode($product->attributes)
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Thêm vào giỏ hàng thành công',
                'cart_count' => Cart::sum('quantity')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi thêm vào giỏ hàng'
            ], 500);
        }
    }

    public function removeFromCart($id)
    {
        try {
            $cartItem = Cart::findOrFail($id);
            $cartItem->delete();

            return redirect()->route('cart.index')
                ->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa sản phẩm');
        }
    }

    public function updateQuantity(Request $request, $id)
    {
        try {
            $cartItem = Cart::findOrFail($id);
            $cartItem->quantity = $request->quantity;
            $cartItem->save();

            // Tính toán tổng tiền mới cho sản phẩm này
            $total = $cartItem->getFinalPriceAttribute() * $cartItem->quantity;

            // Tính tổng tiền của toàn bộ giỏ hàng
            $cartTotal = Cart::all()->sum(function ($item) {
                return $item->getFinalPriceAttribute() * $item->quantity;
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật số lượng thành công',
                'cart_count' => Cart::sum('quantity'),
                'total' => $total,
                'cart_total' => $cartTotal
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi cập nhật số lượng'
            ], 500);
        }
    }
}
