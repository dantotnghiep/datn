@if(Session::has("Cart") != null)
    <div class="cart-top">
        
        <ul class="cart-product-grid">
            @foreach (Session::get('Cart')->products as $item)
                <li class="single-cart-product">
                    <div class="cart-product-info d-flex align-items-center">
                        <div class="product-img"><img src="assets/images/product/cart-p1.png" alt=""
                                class="img-fluid"></div>
                        <div class="product-info">
                            <a href="product-details.html">
                                <h5 class="product-title">{{ $item['productInfo']->name }}</h5>
                            </a>
                            <ul class="product-rating d-flex">
                                <li><i class="bi bi-star-fill"></i></li>
                                <li><i class="bi bi-star-fill"></i></li>
                                <li><i class="bi bi-star-fill"></i></li>
                                <li><i class="bi bi-star-fill"></i></li>
                                <li><i class="bi bi-star"></i></li>
                            </ul>
                            <p class="product-price"><span>{{ $item['quanty'] }}</span>x <span
                                    class="p-price">{{ number_format($item['productInfo']->price) }}VND</span>
                            </p>
                        </div>
                    </div>
                    <div class="cart-product-delete-btn">
                        <i class="flaticon-letter-x" data-id="{{ $item['productInfo']->id }}"></i>
                    </div>

                </li>
            @endforeach

        </ul>
    </div>
    <div class="cart-total d-flex justify-content-between">
        <label>Subtotal :</label>
        <span>{{ number_format(Session::get('Cart')->totalPrice) }}VND</span>
        <input hidden type="number" id="total-quanty-cart" value="{{Session::get('Cart')->totalQuanty}}">
    </div>
    
@endif
