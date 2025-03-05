<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Cart extends Model {
    // public $products = [];
    // public $totalPrice = 0;
    // public $totalQuanty = 0;

    // public function __construct($oldCart = null) {
    //     if ($oldCart) {
    //         $this->products = $oldCart->products;
    //         $this->totalPrice = $oldCart->totalPrice;
    //         $this->totalQuanty = $oldCart->totalQuanty;
    //     }
    // }

    // public function AddCart($product, $id) {
    //     $newProduct = [
    //         'quanty' => 0,
    //         'price' => $product->price,
    //         'productInfo' => $product
    //     ];

    //     if ($this->products) {
    //         if (isset($this->products[$id])) {
    //             $newProduct = $this->products[$id];
    //         }
    //     }

    //     $newProduct['quanty']++;
    //     $newProduct['price'] = $newProduct['quanty'] * $product->price;
    //     $this->products[$id] = $newProduct;
    //     $this->totalPrice += $product->price;
    //     $this->totalQuanty++;
    // }

    // public function DeleteItemCart($id){
    //     $this->totalQuanty -= $this->products[$id]['quanty'];
    //     $this->totalPrice -= $this->products[$id]['price'];
    //     unset($this->products[$id]);

    // }
    use HasFactory;

    protected $fillable = [
        'product_id',
        'category_id',
        'name',
        'quantity',
        'price',
        'sale_price',
        'main_image',
        'description',
        'attributes'
    ];

    protected $casts = [
        'attributes' => 'array',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2'
    ];

    // Relationship với Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship với Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Tính giá cuối cùng (có tính đến giá khuyến mãi)
    public function getFinalPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    // Tính tổng tiền cho item này
    public function getTotalAttribute()
    {
        return $this->getFinalPriceAttribute() * $this->quantity;
    }

    // Kiểm tra xem sản phẩm có đang giảm giá không
    public function getIsOnSaleAttribute()
    {
        return !is_null($this->sale_price) && $this->sale_price < $this->price;
    }

    // Tính % giảm giá
    public function getDiscountPercentAttribute()
    {
        if ($this->is_on_sale) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }
}
?>
