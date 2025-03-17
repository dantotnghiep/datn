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
                                                <i class="bi bi-person me-2"></i>Profile</a>
                                            <a href="{{ route('profile') }}" class="submenu-item">
                                                <i class="bi bi-bag me-2"></i>Orders</a>
                                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="submenu-item border-0 bg-transparent w-100 text-start">
                                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="submenu-item-wrapper">
                                            <a href="{{ route('login') }}" class="submenu-item">
                                                <i class="bi bi-box-arrow-in-right me-2"></i>Login</a>
                                            <a href="{{ route('register') }}" class="submenu-item">
                                                <i class="bi bi-person-plus me-2"></i>Register</a>
                                        </div>
                                    @endif
                                </div>
                            </li>
                            <li><a href="product.html"><i class="flaticon-heart"></i></a></li>
                            <li class="cart-icon">
                                <i class="flaticon-shopping-cart"></i>
                                <div class="cart-count"><span>10</span></div>
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
                <li class="single-cart-product">
                    <div class="cart-product-info d-flex align-items-center">
                        <div class="product-img"><img src="/client/assets/images/product/cart-p1.png" alt=""
                                class="img-fluid"></div>
                        <div class="product-info">
                            <a href="product-details.html">
                                <h5 class="product-title">Men Casual Summer Sale</h5>
                            </a>
                            <ul class="product-rating d-flex">
                                <li><i class="bi bi-star-fill"></i></li>
                                <li><i class="bi bi-star-fill"></i></li>
                                <li><i class="bi bi-star-fill"></i></li>
                                <li><i class="bi bi-star-fill"></i></li>
                                <li><i class="bi bi-star"></i></li>
                            </ul>
                            <p class="product-price"><span>1</span>x <span class="p-price">$10.32</span>
                            </p>
                        </div>
                    </div>
                    <div class="cart-product-delete-btn">
                        <a href="javascript:void(0)"><i class="flaticon-letter-x"></i></a>
                    </div>

                </li>
                <li class="single-cart-product">
                    <div class="cart-product-info d-flex align-items-center">
                        <div class="product-img"><img src="/client/assets/images/product/cart-p3.png" alt=""
                                class="img-fluid"></div>
                        <div class="product-info">
                            <a href="product-details.html">
                                <h5 class="product-title">Something Yellow Jens</h5>
                            </a>
                            <ul class="product-rating d-flex">
                                <li><i class="bi bi-star-fill"></i></li>
                                <li><i class="bi bi-star-fill"></i></li>
                                <li><i class="bi bi-star-fill"></i></li>
                                <li><i class="bi bi-star-fill"></i></li>
                                <li><i class="bi bi-star"></i></li>
                            </ul>
                            <p class="product-price"><span>1</span>x <span class="p-price">$10.32</span>
                            </p>
                        </div>
                    </div>
                    <div class="cart-product-delete-btn">
                        <a href="javascript:void(0)"><i class="flaticon-letter-x"></i></a>
                    </div>

                </li>
                <li class="single-cart-product">
                    <div class="cart-product-info d-flex align-items-center">
                        <div class="product-img"><img src="/client/assets/images/product/cart-p2.png" alt=""
                                class="img-fluid"></div>
                        <div class="product-info">
                            <a href="product-details.html">
                                <h5 class="product-title">Woman Something Navy Top</h5>
                            </a>
                            <ul class="product-rating d-flex">
                                <li><i class="bi bi-star-fill"></i></li>
                                <li><i class="bi bi-star-fill"></i></li>
                                <li><i class="bi bi-star-fill"></i></li>
                                <li><i class="bi bi-star-fill"></i></li>
                                <li><i class="bi bi-star"></i></li>
                            </ul>
                            <p class="product-price"><span>1</span>x <span class="p-price">$10.32</span>
                            </p>
                        </div>
                    </div>
                    <div class="cart-product-delete-btn">
                        <a href="javascript:void(0)"><i class="flaticon-letter-x"></i></a>
                    </div>

                </li>
            </ul>
        </div>
        <div class="cart-bottom">
            <div class="cart-total d-flex justify-content-between">
                <label>Subtotal :</label>
                <span>$64.08</span>
            </div>
            <div class="cart-btns">
                <a href="checkout.html" class="cart-btn checkout">CHECKOUT</a>
                <a href="cart.html" class="cart-btn cart">VIEW CART</a>
            </div>

            <p class="cart-shipping-text"><strong>SHIPPING:</strong> Continue shopping up to $64.08 and receive free
                shipping. stay with EG </p>
        </div>
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
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
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
