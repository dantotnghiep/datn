<div class="cart-sidebar-header d-flex justify-content-between align-items-center p-3 border-bottom">
    <h5 class="mb-0">Giỏ hàng của bạn</h5>
    <button type="button" class="close-cart-sidebar btn btn-link">
        <i class="flaticon-letter-x"></i>
    </button>
</div>

<div class="cart-bottom">
    @if ($cartItems->count() > 0)
        <div class="cart-items">
            @foreach ($cartItems as $item)
                <div class="single-cart-item d-flex justify-content-between align-items-center mb-3">
                    <div class="item-info d-flex align-items-center">
                        <div class="item-image mr-3">
                            @if ($item->main_image)
                                <img src="{{ asset($item->main_image) }}" alt="{{ $item->name }}"
                                    style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <img src="{{ asset('assets/images/no-image.png') }}" alt="No Image"
                                    style="width: 60px; height: 60px; object-fit: cover;">
                            @endif
                        </div>
                        <div class="item-details">
                            <h6 class="mb-1">{{ $item->name }}</h6>
                            <div class="quantity-price">
                                <span class="quantity">{{ $item->quantity }}x</span>
                                @if ($item->sale_price)
                                    <span class="price text-danger">{{ number_format($item->sale_price) }}VND</span>
                                    <span class="original-price text-muted text-decoration-line-through">
                                        {{ number_format($item->price) }}VND
                                    </span>
                                @else
                                    <span class="price">{{ number_format($item->price) }}VND</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="item-remove">
                        <i class="flaticon-letter-x" data-id="{{ $item->id }}" style="cursor: pointer;"></i>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="cart-total d-flex justify-content-between align-items-center py-3">
            <span class="font-weight-bold">Tổng tiền:</span>
            <span class="total-amount">{{ number_format($cartTotal) }}VND</span>
        </div>

        <div class="cart-btns">
            <a href="{{ route('cart.index') }}" class="cart-btn cart">XEM GIỎ HÀNG</a>
            <a href="{{ route('checkout') }}" class="cart-btn checkout">THANH TOÁN</a>
        </div>
    @else
        <div class="empty-cart text-center py-4">
            <i class="flaticon-shopping-cart mb-3" style="font-size: 2rem;"></i>
            <p class="mb-3">Giỏ hàng của bạn đang trống</p>
            <a href="{{ route('client.product.list-product') }}" class="btn btn-primary">
                Tiếp tục mua sắm
            </a>
        </div>
    @endif

    <p class="cart-shipping-text mt-3">
        <strong>MIỄN PHÍ VẬN CHUYỂN</strong> cho đơn hàng từ 1.000.000 VND
    </p>
</div>
