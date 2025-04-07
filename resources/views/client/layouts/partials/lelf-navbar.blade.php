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
                            <li class="user-menu-wrapper position-relative">
                                <a href="#"><i class="flaticon-user"></i></a>
                                <div class="submenu-right position-absolute" style="left: 70px;">
                                    @if (Auth::check())
                                        <div class="submenu-item-wrapper">
                                            <a href="{{ route('profile') }}" class="submenu-item">
                                                <i class="bi bi-person me-2"></i>Thông tin cá nhân</a>
                                            <a href="{{ route('orders.index') }}" class="submenu-item">
                                                <i class="bi bi-bag me-2"></i>Đơn hàng</a>
                                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="submenu-item border-0 bg-transparent w-100 text-start">
                                                    <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="submenu-item-wrapper">
                                            <a href="{{ route('login') }}" class="submenu-item">
                                                <i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập</a>
                                            <a href="{{ route('register') }}" class="submenu-item">
                                                <i class="bi bi-person-plus me-2"></i>Đăng ký</a>
                                        </div>
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
                                    <img src="{{ $mainImage ? $mainImage->url : asset('default-image.jpg') }} "
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
                        <p>Giỏ hàng của bạn hiện tại không có sản phẩm</p>
                    </li>
                @endif
            </ul>
        </div>
        @if(auth()->check() && isset($cartItems) && $cartItems->count() > 0)
        <div class="cart-bottom">
            <div class="cart-total d-flex justify-content-between">
                <label>Tổng cộng :</label>
                <span>{{ number_format($total) }} VND</span>
            </div>
            <div class="cart-btns">

                <a href="" class="cart-btn checkout">THANH TOÁN</a>
                <a href="{{ route('cart.index') }}" class="cart-btn cart">XEM GIỎ HÀNG</a>

            </div>

            @if($total < 1000000) {{-- Giả sử free ship cho đơn > 1 triệu --}}
                <p class="cart-shipping-text">
                    <strong>MIỄN PHÍ VẬN CHUYỂN:</strong>
                    Chi tiêu thêm {{ number_format(1000000 - $total) }} VND để đủ điều kiện miễn phí vận chuyển
                </p>
            @else
                <p class="cart-shipping-text text-success">
                    <strong>CHÚC MỪNG!</strong>
                    Đơn hàng của bạn đủ điều kiện miễn phí vận chuyển
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
            Tất cả danh mục
            <i class="flaticon-arrow-pointing-to-left"></i>
        </h5>
        <ul class="cb-category-list">
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-man"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title">Nam <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-woman"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title">Phụ nữ <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-children"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title">Bộ sưu tập trẻ em <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-sun-glasses"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title">Phụ kiện <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-formal"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title">Đồ ngủ <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-shoes"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title">Giày dép <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-watch"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title">Đồng hồ <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-necklace"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title">Trang sức <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-diamond"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title">Kim cương <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-baby-boy"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title">Quần áo trẻ em <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-fashion"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title">Trang phục theo mùa <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
            <li class="cb-single-category">
                <div class="cb-category-icon">
                    <i class="flaticon-sports"></i>
                </div>
                <a href="product.html">
                    <h5 class="cb-category-title">Thể thao <i class="bi bi-arrow-right"></i></h5>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- =
