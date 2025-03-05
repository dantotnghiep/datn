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
                            <li><a href="dashboard.html"><i class="flaticon-user"></i></a></li>
                            <li><a href="product.html"><i class="flaticon-heart"></i></a></li>
                            <li class="cart-icon">
                                <i class="flaticon-shopping-cart"></i>
                                <span id="total-quanty-show">{{ \App\Models\Cart::sum('quantity') }}</span>
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
            <div class="cart-count">
                <span>{{ \App\Models\Cart::sum('quantity') }}</span>
            </div>
        </li>
    </ul>
</div>
<!-- ===============sidebar area end=============== -->

<!-- =============== cart sidebar start=============== -->
<div class="cart-sidebar-wrappper">
    <div class="main-cart-sidebar">
        <div id="change-item-cart">
            @if (Session::has('Cart') != null)
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

                </div>
            @endif

        </div>
        <div class="cart-bottom">
            @php
                $cartItems = \App\Models\Cart::all();
                $cartTotal = $cartItems->sum(function ($item) {
                    return $item->getFinalPriceAttribute() * $item->quantity;
                });
            @endphp

            @if($cartItems->count() > 0)
                <div class="cart-items">
                    @foreach($cartItems as $item)
                        <div class="single-cart-item d-flex justify-content-between align-items-center mb-3">
                            <div class="item-info d-flex align-items-center">
                                <div class="item-image mr-3">
                                    @if($item->main_image)
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
                                        @if($item->sale_price)
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
                                <i class="flaticon-letter-x" data-id="{{ $item->id }}"
                                   style="cursor: pointer;"></i>
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
                    <a href="" class="cart-btn checkout">THANH TOÁN</a>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateCartDisplay(data) {
        // Cập nhật số lượng
        const cartCountElements = document.querySelectorAll('#total-quanty-show, .cart-count span');
        cartCountElements.forEach(element => {
            element.textContent = data.cart_count;
        });

        // Cập nhật nội dung giỏ hàng
        const cartSidebar = document.getElementById('change-item-cart');
        if (cartSidebar && data.html) {
            cartSidebar.innerHTML = data.html;
        }
    }

    // Hàm tải lại giỏ hàng
    function refreshCart() {
        fetch('/cart/sidebar', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                updateCartDisplay(data);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Xử lý sự kiện xóa sản phẩm
    document.addEventListener('click', function(e) {
        if (e.target.matches('.cart-product-delete-btn i')) {
            e.preventDefault();
            const productId = e.target.dataset.id;

            fetch(`/cart/remove/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    refreshCart();
                }
            });
        }
    });

    // Xử lý sự kiện thêm vào giỏ hàng
    document.addEventListener('click', function(e) {
        if (e.target.matches('.add-to-cart-btn')) {
            e.preventDefault();
            const productId = e.target.dataset.productId;

            fetch(`/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    refreshCart();
                    // Hiển thị thông báo thành công
                    alert('Thêm vào giỏ hàng thành công!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi thêm vào giỏ hàng');
            });
        }
    });

    // Tải giỏ hàng khi trang được load
    refreshCart();
});
</script>
