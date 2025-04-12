@extends('client.layouts.master')
@section('content')
    <div class="body_content">
        <!-- ===============Hero area start =============== -->
        <div class="hero-area ml-110">
            <div class="row">
                <div class="col-xxl-10 col-xl-9 col-lg-9 p-0">
                    <div class="row">
                        <div class="swiper-container hero-swiper-container">
                            <!-- hero slider slides -->
                            <div class="swiper-wrapper">
                                <div
                                    class="swiper-slide hero-slider-item slider-item1 d-flex justify-content-center align-items-center position-relative">
                                    <div class="slider-image-layer"></div>
                                    <div class="slider-content position-relative text-center">
                                        <h5 class="slider-sub-title">Trending Product</h5>
                                        <h2 class="slider-main-title">
                                            Awesome Collection for your Fashion
                                        </h2>
                                        <div class="banner-btn">
                                            <a href="product.html" class="eg-btn-xl">
                                                View All Collection</a>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="swiper-slide hero-slider-item slider-item2 d-flex justify-content-center align-items-center position-relative">
                                    <div class="slider-image-layer"></div>
                                    <div class="slider-content position-relative text-center">
                                        <h5 class="slider-sub-title">Trending Product</h5>
                                        <h2 class="slider-main-title">
                                            Awesome Collection for your Fashion
                                        </h2>
                                        <div class="banner-btn">
                                            <a href="product.html" class="eg-btn-xl-v2">
                                                View All Collection</a>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="swiper-slide hero-slider-item slider-item3 d-flex justify-content-center align-items-center position-relative">
                                    <div class="slider-image-layer"></div>
                                    <div class="slider-content position-relative text-center">
                                        <h5 class="slider-sub-title">Trending Product</h5>
                                        <h2 class="slider-main-title">
                                            Awesome Collection for your Fashion
                                        </h2>
                                        <div class="banner-btn">
                                            <a href="product.html" class="eg-btn-xl-v2">
                                                View All Collection</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- !swiper slides -->

                            <!-- next / prev arrows -->
                            <div class="swiper-button-next">
                                <i class="flaticon-arrow-pointing-to-right"></i>
                            </div>
                            <div class="swiper-button-prev">
                                <i class="flaticon-arrow-pointing-to-left"></i>
                            </div>
                            <!-- !next / prev arrows -->

                            <!-- pagination dots -->
                            <!-- <div class="swiper-pagination hero-banner-pagination"></div> -->
                            <!-- !pagination dots -->
                        </div>
                    </div>
                </div>
                <div class="col-xxl-2 col-xl-3 col-lg-3 p-0 d-flex justify-content-between feature-banner-col">
                    <div class="banner-feature-item position-relative">
                        <div class="b-feature-shape">
                            <img src="/client/assets/images/shapes/banner-feature-shape.png" alt=""
                                class="position-absolute" />
                        </div>
                        <div class="feature-head d-flex align-items-center position-relative">
                            <div class="feature-icon">
                                <i class="flaticon-shipping"></i>
                            </div>
                            <p>Our Quality</p>
                        </div>
                        <h5>Most Advanced Features</h5>
                    </div>
                    <div class="banner-feature-item position-relative">
                        <div class="b-feature-shape">
                            <img src="/client/assets/images/shapes/banner-feature-shape.png" alt=""
                                class="position-absolute" />
                        </div>
                        <div class="feature-head d-flex align-items-center position-relative">
                            <div class="feature-icon">
                                <i class="flaticon-price-tag"></i>
                            </div>
                            <p>Price System</p>
                        </div>
                        <h5>Very Reasonable Price</h5>
                    </div>
                    <div class="banner-feature-item position-relative">
                        <div class="b-feature-shape">
                            <img src="/client/assets/images/shapes/banner-feature-shape.png" alt=""
                                class="position-absolute" />
                        </div>
                        <div class="feature-head d-flex align-items-center position-relative">
                            <div class="feature-icon">
                                <i class="flaticon-puzzle"></i>
                            </div>
                            <p>Delivery System</p>
                        </div>
                        <h5>Product Frist Delivery</h5>
                    </div>
                    <div class="banner-feature-item position-relative">
                        <div class="b-feature-shape">
                            <img src="/client/assets/images/shapes/banner-feature-shape.png" alt=""
                                class="position-absolute" />
                        </div>
                        <div class="feature-head d-flex align-items-center position-relative">
                            <div class="feature-icon">
                                <i class="flaticon-headphones"></i>
                            </div>
                            <p>Customer Support</p>
                        </div>
                        <h5>24/7 Live Support</h5>
                    </div>
                </div>
            </div>
        </div>
        <!-- ===============Hero area end =============== -->

        <!-- =============== main searchbar start =============== -->

        <div class="container mt-5">
            <div class="col-12 mb-5">
                <div class="section-head text-center">
                    <h2 class="section-title">Sản phẩm giảm giá nhiều nhất</h2>
                </div>
            </div>
            <div class="row">
                @foreach ($discountedProducts as $product)
                    @php
                        $mainImage = $product->images->first(); // Ảnh chính
                        $bestVariation = $product->variations->first(); // Variation có giảm giá mạnh nhất
                    @endphp

                    <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-4 col-sm-6 mb-4">
                        <div class="product-card-l">
                            <div class="product-img position-relative">
                                <a href="{{ route('client.product.product-details', ['id' => $product->id]) }}"
                                    class="d-block">
                                    <!-- Ảnh chính: đồng bộ kích thước bằng ratio và object-fit -->
                                    <div class="ratio ratio-1x1">
                                        <img src="{{ asset($mainImage->url ?? '/client/assets/images/product/default.jpg') }}"
                                            alt="{{ $product->name }}" class="img-fluid w-100 object-fit-cover"
                                            style="transition: transform 0.3s ease;"
                                            onmouseover="this.style.transform='scale(1.1)'"
                                            onmouseout="this.style.transform='scale(1)'" />
                                    </div>
                                </a>
                                <div class="product-lavels position-absolute top-0 start-0">
                                    @if ($bestVariation)
                                        <span class="badge bg-danger">Giảm
                                            {{ number_format($bestVariation->price - $bestVariation->sale_price, 0, ',', '.') }}đ</span>
                                    @endif
                                </div>
                                <div class="product-actions position-absolute bottom-0 end-0 p-2">
                                    <!-- Icon tim -->
                                    <button class="btn p-0 border-0 shadow-none bg-transparent wishlist-toggle mb-1"
                                        data-product-id="{{ $product->id }}">
                                        <i
                                            class="bi bi-heart{{ auth()->check() &&$product->wishlists()->where('user_id', auth()->id())->exists()? '-fill text-danger': '' }}"></i>
                                    </button>
                                    <a href="{{ route('client.product.product-details', ['id' => $product->id]) }}"
                                        class="text-dark me-2"><i class="flaticon-search"></i></a>

                                </div>
                            </div>
                            <div class="product-title text-center py-2">
                                <h3 class="product-title mb-2">
                                    <a href="{{ route('client.product.product-details', ['id' => $product->id]) }}"
                                        class="text-dark text-decoration-none link-primary">{{ $product->name }}</a>
                                </h3>
                                @if ($bestVariation)
                                    <div>
                                        <del
                                            class="text-muted">{{ number_format($bestVariation->price, 0, ',', '.') }}đ</del>
                                        <span
                                            class="text-danger ms-2">{{ number_format($bestVariation->sale_price, 0, ',', '.') }}đ</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <style>
            .product-img:hover .hover-img {
                display: block !important;
            }
        </style>
    </div>

    <div class="recent-product-wrapper ml-110 mt-100">
        <div class="container-fluid">
            <div class="row">
                <!-- Phần tab danh mục -->
                <div class="col-xxl-3 col-xl-3 col-lg-4">
                    <div class="nav flex-column nav-pills category-tabs p-3" id="v-pills-tab" role="tablist"
                        aria-orientation="vertical">
                        @foreach ($categories as $key => $category)
                            <button class="nav-link category-tab {{ $key === 0 ? 'active' : '' }}"
                                id="v-pills-{{ $category->id }}-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-{{ $category->id }}" type="button" role="tab"
                                aria-controls="v-pills-{{ $category->id }}"
                                aria-selected="{{ $key === 0 ? 'true' : 'false' }}">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Phần nội dung tab -->
                <div class="col-xxl-9 col-xl-9 col-lg-8">
                    <div class="tab-content" id="v-pills-tabContent">
                        @foreach ($categories as $key => $category)
                            <div class="tab-pane fade {{ $key === 0 ? 'show active' : '' }}"
                                id="v-pills-{{ $category->id }}" role="tabpanel"
                                aria-labelledby="v-pills-{{ $category->id }}-tab">
                                <div class="row">
                                    @if ($category->products->count() > 0)
                                        @foreach ($category->products as $product)
                                            @php
                                                $mainImage = $product->images->first(); // Ảnh chính
                                                $bestVariation = $product->variations->first(); // Variation có giảm giá mạnh nhất
                                            @endphp
                                            <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-4 col-sm-6 mb-4">
                                                <div class="product-card-l">
                                                    <div class="product-img position-relative overflow-hidden">
                                                        <a href="{{ route('client.product.product-details', ['id' => $product->id]) }}"
                                                            class="d-block">
                                                            <!-- Ảnh chính: đồng bộ kích thước bằng ratio và object-fit -->
                                                            <div class="ratio ratio-1x1">
                                                                <img src="{{ asset($mainImage->url ?? '/client/assets/images/product/default.jpg') }}"
                                                                    alt="{{ $product->name }}"
                                                                    class="img-fluid w-100 object-fit-cover"
                                                                    style="transition: transform 0.3s ease;"
                                                                    onmouseover="this.style.transform='scale(1.1)'"
                                                                    onmouseout="this.style.transform='scale(1)'" />
                                                            </div>
                                                        </a>
                                                        <div class="product-lavels position-absolute top-0 start-0">
                                                            @if ($bestVariation)
                                                                <span class="badge bg-danger">Giảm
                                                                    {{ number_format($bestVariation->price - $bestVariation->sale_price, 0, ',', '.') }}đ</span>
                                                            @endif
                                                        </div>
                                                        <div class="product-actions position-absolute bottom-0 end-0 p-2">
                                                            <!-- Icon tim -->
                                                            <button
                                                                class="btn p-0 border-0 shadow-none bg-transparent wishlist-toggle mb-1"
                                                                data-product-id="{{ $product->id }}">
                                                                <i
                                                                    class="bi bi-heart{{ auth()->check() &&$product->wishlists()->where('user_id', auth()->id())->exists()? '-fill text-danger': '' }}"></i>
                                                            </button>
                                                            <a href="{{ route('client.product.product-details', ['id' => $product->id]) }}"
                                                                class="text-dark me-2"><i class="flaticon-search"></i></a>

                                                        </div>
                                                    </div>
                                                    <div class="product-title text-center py-2">
                                                        <h3 class="product-title mb-2">
                                                            <a href="{{ route('client.product.product-details', ['id' => $product->id]) }}"
                                                                class="text-dark text-decoration-none link-primary">{{ $product->name }}</a>
                                                        </h3>
                                                        @if ($bestVariation)
                                                            <div>
                                                                <del
                                                                    class="text-muted">{{ number_format($bestVariation->price, 0, ',', '.') }}đ</del>
                                                                <span
                                                                    class="text-danger ms-2">{{ number_format($bestVariation->sale_price, 0, ',', '.') }}đ</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p>Không có sản phẩm nào trong danh mục này.</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Đảm bảo Bootstrap JS được tải để tab hoạt động -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Đảm bảo Bootstrap JS được tải để tab hoạt động -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </div>

    <div class="container mt-5">
        <div class="col-12 mb-5">
            <div class="section-head text-center">
                <h2 class="section-title">Sản Phẩm Mới</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="carousel slide" id="productCarousel" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @php
                            $productsChunks = $products->chunk(4); // Chia sản phẩm thành từng nhóm 4
                            $active = true;
                        @endphp
                        @foreach ($productsChunks as $chunk)
                            <div class="carousel-item {{ $active ? 'active' : '' }}">
                                <div class="row g-4 justify-content-center">
                                    @foreach ($chunk as $product)
                                        @php
                                            $image = $product->images->first();
                                            $imageSrc = $image
                                                ? (Str::startsWith($image->url, 'http')
                                                    ? $image->url
                                                    : asset('storage/' . $image->url))
                                                : 'https://via.placeholder.com/640x480.png?text=No+Image';
                                        @endphp
                                        <div class="col-12 col-md-3 mb-4">
                                            <div class="card h-100 position-relative">
                                                <div class="card-img-top overflow-hidden">
                                                    <a href="{{ route('client.product.product-details', $product->id) }}"
                                                        class="d-block">
                                                        <img src="{{ $imageSrc }}" alt="{{ $product->name }}"
                                                            class="img-fluid w-100 object-fit-cover product-image"
                                                            style="max-height: 300px;" />
                                                    </a>
                                                </div>
                                                <div class="card-body text-center">
                                                    <h5 class="card-title">{{ $product->name }}</h5>
                                                    <p class="card-text">
                                                        @if ($product->variations->count() > 0)
                                                            @php
                                                                $minPrice = $product->variations->min('price');
                                                                $minSalePrice = $product->variations->min('sale_price');
                                                            @endphp
                                                            @if ($minSalePrice)
                                                                <del class="text-muted">{{ number_format($minSalePrice, 0, ',', '.') }}
                                                                    đ</del>
                                                                <span
                                                                    class="text-danger">{{ number_format($minPrice, 0, ',', '.') }}
                                                                    đ</span>
                                                            @else
                                                                <span
                                                                    class="text-primary">{{ number_format($minPrice, 0, ',', '.') }}
                                                                    đ</span>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">Liên hệ</span>
                                                        @endif
                                                    </p>
                                                    <a href="{{ route('cart.add', ['id' => $product->id]) }}"
                                                        class="btn btn-sm btn-success position-absolute bottom-0 end-0 m-2 d-none hover-show">
                                                        <i class="flaticon-shopping-cart"></i>
                                                    </a>
                                                    <a href="{{ route('client.product.product-details', $product->id) }}"
                                                        class="btn btn-sm btn-primary">Xem chi tiết</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @php
                                $active = false;
                            @endphp
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>

                </div>
            </div>
        </div>
    </div>

    <!-- Đảm bảo Bootstrap JS được tải -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.querySelectorAll('.card').forEach(card => {
            const img = card.querySelector('.product-image');
            const cartBtn = card.querySelector('.hover-show');

            card.addEventListener('mouseover', () => {
                img.style.transform = 'scale(1.1)';
                card.style.zIndex = '1';
                card.style.boxShadow = '0 4px 8px rgba(36, 36, 36, 0.2)';
                cartBtn.style.opacity = '1';
                cartBtn.style.display = 'block';
            });

            card.addEventListener('mouseout', () => {
                img.style.transform = 'scale(1)';
                card.style.zIndex = '0';
                card.style.boxShadow = 'none';
                cartBtn.style.opacity = '0';
                cartBtn.style.display = 'none';
            });
        });
    </script>


    </div>
    </div>
    </div>
    </div>


    <!-- =============== banner xl area start =============== -->
    <div class="banner-xl-area ml-110 mt-100">

        <!-- =============== banner xl area start =============== -->
        <div class="banner-xl-area mt-100">
            <div class="container p-0">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="banner-xl-bg d-flex align-items-center position-relative">
                            <div class="banner-shapes">
                                <img src="/client/assets/images/shapes/b-xl-right.png" alt=""
                                    class="position-absolute top-0 end-0" />
                                <img src="/client/assets/images/shapes/b-xl-left.png" alt=""
                                    class="position-absolute top0 bottom-0" />
                            </div>
                            <div class="banner-content-wrap">
                                <h5 class="banner-xl-subtitle">Today Top Offer</h5>
                                <h2 class="banner-xl-title">
                                    Lining Casual Winter Sale Only 250$
                                </h2>
                                <p>
                                    Lorem ipsum dolor sit amet consectetur adipiscing elitsed do
                                    eiusmod tempor incididunt utlabore et dolore magna aliqua.
                                    Utenim ad minim veniam quis nostrud exercitation ullamco
                                    laboris nisi ut aliquip ex ea commodo consequat.
                                </p>
                                <div class="banner-xl-btns">
                                    <a href="product.html" class="eg-btn-md">Shop Now</a>
                                    <a href="product-details.html" class="eg-btn-md v2">About Product</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- =============== banner xl area end =============== -->

        <div class="tranding-product-wrapper mt-70 position-relative">
            <div class="container-fluid">
                <div class="container mt-5">
                    <div class="row">
                        <div class="col-12 mb-5">
                            <div class="section-head text-center">
                                <h2 class="section-title">Sản Phẩm Hot</h2>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @foreach ($hotProducts as $hotProduct)
                            @php
                                $product = $hotProduct->product;
                                $image = $product->images->first()->url ?? '/client/assets/images/default.png';
                                $variation = $product->variations->first();
                                $originalPrice = $variation->price ?? 0;
                                $salePrice = $variation->sale_price ?? $originalPrice;
                                $discount =
                                    $originalPrice > $salePrice
                                        ? round((($originalPrice - $salePrice) / $originalPrice) * 100)
                                        : null;
                            @endphp

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4"> <!-- 4 sản phẩm trên 1 hàng -->
                                <div class="product-card-l">
                                    <div class="product-img position-relative overflow-hidden">
                                        <a href="{{ route('client.product.product-details', $product->id) }}"
                                            class="d-block">
                                            <!-- Ảnh chính: đồng bộ kích thước bằng ratio và object-fit -->
                                            <div class="ratio ratio-1x1">
                                                <img src="{{ asset($image) }}" alt="{{ $product->name }}"
                                                    class="img-fluid w-100 object-fit-cover"
                                                    style="transition: transform 0.3s ease;"
                                                    onmouseover="this.style.transform='scale(1.1)'"
                                                    onmouseout="this.style.transform='scale(1)'" />
                                            </div>
                                        </a>
                                        <div class="product-lavels position-absolute top-0 start-0">
                                            @if ($discount)
                                                <span class="badge bg-danger">Giảm {{ $discount }}%</span>
                                            @endif
                                        </div>
                                        <div class="product-actions position-absolute bottom-0 end-0 p-2">
                                            <!-- Icon tim -->
                                            <button
                                                class="btn p-0 border-0 shadow-none bg-transparent wishlist-toggle mb-1"
                                                data-product-id="{{ $product->id }}">
                                                <i
                                                    class="bi bi-heart{{ auth()->check() &&$product->wishlists()->where('user_id', auth()->id())->exists()? '-fill text-danger': '' }}"></i>
                                            </button>
                                            <a href="{{ route('client.product.product-details', $product->id) }}"
                                                class="text-dark me-2"><i class="flaticon-search"></i></a>

                                        </div>
                                    </div>
                                    <div class="product-title text-center py-2">
                                        <h3 class="product-title mb-2">
                                            <a href="{{ route('client.product.product-details', $product->id) }}"
                                                class="text-dark text-decoration-none link-primary">{{ $product->name }}</a>
                                        </h3>
                                        <div>
                                            @if ($originalPrice > $salePrice)
                                                <del
                                                    class="text-muted">{{ number_format($originalPrice, 0, ',', '.') }}đ</del>
                                                <span
                                                    class="text-danger ms-2">{{ number_format($salePrice, 0, ',', '.') }}đ</span>
                                            @else
                                                <span
                                                    class="text-danger">{{ number_format($originalPrice, 0, ',', '.') }}đ</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
            @push('scripts')
                <script>
                    function addToCart(productId) {
                        $.ajax({
                            url: `/cart/add/${productId}`,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    // Cập nhật số lượng giỏ hàng trên UI
                                    updateCartCount(response.cart_count);
                                    // Hiển thị thông báo thành công
                                    toastr.success(response.message);
                                }
                            },
                            error: function(xhr) {
                                toastr.error('Có lỗi xảy ra khi thêm vào giỏ hàng');
                            }
                        });
                    }

                    function updateCartCount(count) {
                        // Cập nhật số lượng hiển thị trên icon giỏ hàng
                        $('.cart-count').text(count);
                    }

                    $(document).ready(function() {
                        $('.wishlist-toggle').on('click', function() {
                            let button = $(this);
                            let productId = button.data('product-id');

                            // Debug: Kiểm tra productId
                            console.log('Product ID:', productId);

                            $.ajax({
                                url: `/wishlist/toggle/${productId}`,
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    console.log('Response:', response); // Debug response
                                    if (response.status === 'added') {
                                        button.find('i').addClass('bi-heart-fill text-danger').removeClass(
                                            'bi-heart');
                                        alert(response.message);
                                    } else {
                                        button.find('i').addClass('bi-heart').removeClass(
                                            'bi-heart-fill text-danger');
                                        alert(response.message);
                                    }
                                },
                                error: function(xhr) {
                                    console.log('Error:', xhr.responseText); // Debug error
                                    alert('Đã có lỗi xảy ra: ' + xhr.responseText);
                                }
                            });
                        });
                    });
                </script>
            @endpush
        @endsection
