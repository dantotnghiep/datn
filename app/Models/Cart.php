<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Cart {
    public $products = [];
    public $totalPrice = 0;
    public $totalQuanty = 0;

    public function __construct($oldCart = null) {
        if ($oldCart) {
            $this->products = $oldCart->products;
            $this->totalPrice = $oldCart->totalPrice;
            $this->totalQuanty = $oldCart->totalQuanty;
        }
    }

    public function AddCart($product, $id) {
        $newProduct = [
            'quanty' => 0,
            'price' => $product->price,
            'productInfo' => $product
        ];

        if ($this->products) {
            if (isset($this->products[$id])) {
                $newProduct = $this->products[$id];
            }
        }

        $newProduct['quanty']++;
        $newProduct['price'] = $newProduct['quanty'] * $product->price;
        $this->products[$id] = $newProduct;
        $this->totalPrice += $product->price;
        $this->totalQuanty++;
    }

    public function DeleteItemCart($id){
        $this->totalQuanty -= $this->products[$id]['quanty'];
        $this->totalPrice -= $this->products[$id]['price'];
        unset($this->products[$id]);
        
    }
} 
?>