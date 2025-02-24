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
    
    public function AddCart(Request $request,$id) {
        $product = DB::table('products')->where('id', $id)->first();
    
        if ($product != null) {
            $oldCart = Session()->has('Cart') ? Session()->get('Cart') : null;
            $newCart = new Cart($oldCart);
            $newCart->AddCart($product, $id);
            $request->Session()->put('Cart', $newCart);
            
        }
        return view('client.cart.model-cart');
    }

    public function DeleteItemCart(Request $request,$id) {
        
            $oldCart = Session()->has('Cart') ? Session()->get('Cart') : null;
            $newCart = new Cart($oldCart);
            $newCart->DeleteItemCart($id);
            if(Count( $newCart->products) > 0){
                $request->Session()->put('Cart', $newCart);
            }else{
                $request->Session()->forget('Cart');
                
            }
            return view('client.cart.model-cart');
    }

    public function checkout()
    {
        return view('client.cart.checkout');
    }

    public function order()
    {
        return view('client.cart.order');
    }
}
