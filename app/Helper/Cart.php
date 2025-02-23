<?php
namespace App\Helper;
class Cart
{
    private $items = [];
    private $total_quantity = 0;
    private $total_price = 0;

    public function __construct()
    {
        $this->items = session('cart') ? session('cart') : [];
    }
    public function list() {
        return $this->items;
    }
    //Thêm mới sản phẩm vào giỏ hàng
    public function add($product, $quantity = 1){
        $item = [
            'productId'=>$product->id,
            'name'=>$product->name,
            
            'price'=>$product->sale_price > 0 ? $product->sale_price : $product->price,
            'image'=>$product->image,
            'quantity'=>$quantity
        ];
        $this->items[$product->id] = $item;

        session(['cart'=>$this->items]);

    }

}