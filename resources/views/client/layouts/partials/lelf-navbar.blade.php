<!-- ===============sidebar area start=============== -->
<div class="main-sidebar">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-1 col-1">
                <div class="sidebar-wrap d-flex justify-content-between flex-column">
                    <div class="sidebar-top d-flex flex-column align-items-center">
                        <div class="category-icon">
                            <i class="flaticon-menu"></i>
                        </div>
                    </div>
                    <div class="sidebar-bottom">
                        <ul class="sidebar-icons">
                            <li class="user-menu-wrapper">
                                <a href="#"><i class="flaticon-user"></i></a>
                                <div class="submenu-right">
                                    @if (Auth::check())
                                        <a href="{{ route('profile') }}" class="submenu-item">
                                            <i class="bi bi-person me-2"></i>Profile</a>
                                        <a href="{{ route('profile') }}" class="submenu-item">
                                            <i class="bi bi-bag me-2"></i>Orders</a>
                                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="submenu-item border-0 bg-transparent w-100 text-start">
                                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="submenu-item">
                                            <i class="bi bi-box-arrow-in-right me-2"></i>Login</a>
                                        <a href="{{ route('register') }}" class="submenu-item">
                                            <i class="bi bi-person-plus me-2"></i>Register</a>
                                    @endif
                                </div>
                            </li>
                            <li><a href="product.html"><i class="flaticon-heart"></i></a></li>
                            <li class="cart-icon">
                                <i class="flaticon-shopping-cart"></i>
                                {{-- @if($cartCount > 0)
                                    <div class="cart-count"><span>{{ $cartCount }}</span></div>
                                @endif --}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mobil-sidebar">
    <ul class="mobil-sidebar-icons">
        <li class="category-icon"><a href="#"><i class="flaticon-menu"></i></a></li>
        <li><a href="dashboard.html"><i class="flaticon-user"></i></a></li>
        <li><a href="#"><i class="flaticon-heart"></i></a></li>

        <li class="cart-icon">
            <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
            <div class="cart-count"><span>10</span></div>
        </li>
    </ul>
</div>
<!-- ===============sidebar area end=============== -->

<!-- =============== cart sidebar start=============== -->
<div class="cart-sidebar-wrappper">
    <div class="main-cart-sidebar">
        <div class="cart-top">
            <div class="cart-close-icon">
                <i class="flaticon-letter-x"></i>
            </div>
            <ul class="cart-product-grid">
                @if(auth()->check() && isset($cartItems) && $cartItems->count() > 0)
                    @php $total = 0; @endphp
                    @foreach($cartItems as $item)
                        @php
                            $variation = $item->variation;
                            $product = $variation->product;
                            $mainImage = $product->images()->where('is_main', 1)->first();
                            $subtotal = $item->price * $item->quantity;
                            $total += $subtotal;
                        @endphp
                        <li class="single-cart-product">
                            <div class="cart-product-info d-flex align-items-center">
                                <div class="product-img">
                                    <img src="{{ $mainImage ? $mainImage->url : asset('default-image.jpg') }}"
                                         alt="{{ $item->product_name }}"
                                         class="img-fluid">
                                </div>
                                <div class="product-info">
                                    <a href="{{ route('client.product.product-details', $product->id) }}">
                                        <h5 class="product-title">{{ $item->product_name }}</h5>
                                    </a>
                                    <div class="product-variant">
                                        <span>{{ $item->color }} / {{ $item->size }}</span>
                                    </div>
                                    <p class="product-price">
                                        <span>{{ $item->quantity }}</span>x
                                        <span class="p-price">{{ number_format($item->price) }} VND</span>
                                    </p>
                                </div>
                            </div>
                            <div class="cart-product-delete-btn">
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="border-0 bg-transparent">
                                        <i class="flaticon-letter-x"></i>
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                @else
                    <li class="text-center py-3">
                        <p>Your cart is empty</p>
                    </li>
                @endif
            </ul>
        </div>
        @if(auth()->check() && isset($cartItems) && $cartItems->count() > 0)
        <div class="cart-bottom">
            <div class="cart-total d-flex justify-content-between">
                <label>Subtotal :</label>
                <span>{{ number_format($total) }} VND</span>
            </div>
            <div class="cart-btns">
                <a href="" class="cart-btn checkout">CHECKOUT</a>
                <a href="{{ route('cart.index') }}" class="cart-btn cart">VIEW CART</a>
            </div>

            @if($total < 1000000) {{-- Giả sử free ship cho đơn > 1 triệu --}}
                <p class="cart-shipping-text">
                    <strong>FREE SHIPPING:</strong>
                    Spend {{ number_format(1000000 - $total) }} VND more to qualify for free shipping
                </p>
            @else
                <p class="cart-shipping-text text-success">
                    <strong>CONGRATULATIONS!</strong>
                    Your order qualifies for free shipping
                </p>
            @endif
        </div>
        @endif
    </div>
</div>
<!-- =============== cart sidebar end=============== -->

<!-- =============== category wrapper start=============== -->

<div class="category-wrapper">
    <div class="category-bar">
        <h5 class="cb-title text-uppercase category-icon">
            All Catagory
            <i class="flaticon-arrow-pointing-to-left"></i>
        </h5>
        <ul class="cb-category-list">
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-man"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title"> Men's <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-woman"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title">Women stuffs <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-children"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title"> Kid Collection <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-sun-glasses"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title"> Accessorice <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-formal"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title"> Sleepwear <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-shoes"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title"> Shoe Collection <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-watch"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title"> Watches <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-necklace"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title"> Jewellery <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-diamond"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title"> Diamond <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-baby-boy"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title"> Baby Clothing <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-fashion"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title">Seasonal Wear <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-sports"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title"> Sports <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- =============== category wrapper end=============== -->

<style>
    .user-menu-wrapper {
        position: relative;
    }

    .submenu-right {
        display: none;
        position: absolute;
        left: 85px;
        top: -10px;
        background: white;
        min-width: 200px;
        border-radius: 12px;
        padding: 15px 0;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    .user-menu-wrapper:hover .submenu-right {
        display: block;
    }

    .submenu-item {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: #333;
        text-decoration: none;
        transition: background 0.3s;
        font-size: 14px;
    }

    .submenu-item i {
        margin-right: 12px;
        width: 20px;
        text-align: center;
    }

    .submenu-item:hover {
        background: #f5f5f5;
    }
</style>
