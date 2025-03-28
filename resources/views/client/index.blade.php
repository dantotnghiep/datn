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
    {{-- Danh Mục Sản Phẩm Trỏ Ra Ở Đây --}}
    <div class="searchbar-area ml-110">
        <div class="container-fluid">
            <form action="#" method="POST" class="main-searchbar-form">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4">
                        <div class="custom-select product-filter-options">
                            <select>
                                <option value="0">Select your choice</option>
                                @isset($categories)
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8">
                        <div class="searchbar-input">
                            <input type="text" placeholder="Search Your Products" />
                            <button type="submit">SEARCH</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- =============== main searchbar end =============== -->

    <!-- ===============  banner md area start =============== -->
    <div class="banner-md-area ml-110">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="banner-md banner-md1 position-relative">
                        <div class="banner-img">
                            <img src="/client/assets/images/banner/banner-md1.png" alt="banner" class="img-fluid" />
                        </div>
                        <div class="banner-md-content position-absolute">
                            <div class="banner-md-content-wrap">
                                <div class="banner-lavel">New Arrivals</div>
                                <h3 class="banner-title">Woman's Winter Sale 2021</h3>
                                <div class="banner-btn">
                                    <a href="product.html">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="banner-md banner-md2 position-relative">
                        <div class="banner-img">
                            <img src="/client/assets/images/banner/banner-md2.png" alt="banner"
                                class="img-fluid" />
                        </div>
                        <div class="banner-md-content position-absolute text-end">
                            <div class="banner-md-content-wrap">
                                <span>Featured Product Shoes</span>
                                <h3 class="banner-title">Ultimate Booster Blows you</h3>
                                <div class="banner-btn">
                                    <a href="product.html">Shop Now</a>
                                </div>
                                <div class="discount-lavel">
                                    <span>
                                        15% <br />
                                        OFF</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="banner-md banner-md3 position-relative">
                        <div class="banner-img">
                            <img src="/client/assets/images/banner/banner-md3.png" alt="banner"
                                class="img-fluid" />
                        </div>
                        <div class="banner-md-content position-absolute">
                            <div class="banner-md-content-wrap">
                                <div class="banner-lavel">New Arrivals</div>
                                <h3 class="banner-title">Men's Casul Summer 2021</h3>
                                <div class="banner-btn">
                                    <a href="product.html">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ===============  banner md area end =============== -->

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
                        <a href="{{ route('client.product.product-details', ['id' => $product->id]) }}" class="d-block">
                            <!-- Ảnh chính: đồng bộ kích thước bằng ratio và object-fit -->
                            <div class="ratio ratio-1x1">
                                <img src="{{ asset($mainImage->url ?? '/client/assets/images/product/default.jpg') }}"
                                    alt="{{ $product->name }}" class="img-fluid w-100 object-fit-cover" style="transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" />
                            </div>
                        </a>
                        <div class="product-lavels position-absolute top-0 start-0">
                            @if ($bestVariation)
                            <span class="badge bg-danger">Giảm
                                {{ number_format($bestVariation->price - $bestVariation->sale_price, 0, ',', '.') }}đ</span>
                            @endif
                        </div>
                        <div class="product-actions position-absolute bottom-0 end-0 p-2">
                            <a href="#" class="text-dark me-2"><i class="flaticon-heart"></i></a>
                            <a href="{{ route('client.product.product-details', ['id' => $product->id]) }}" class="text-dark me-2"><i
                                    class="flaticon-search"></i></a>
                            <a href="{{ route('cart.add', ['id' => $product->id]) }}" class="text-dark"><i
                                    class="flaticon-shopping-cart"></i></a>
                        </div>
                    </div>
                    <div class="product-title text-center py-2">
                        <h3 class="product-title mb-2">
                            <a href="{{ route('client.product.product-details', ['id' => $product->id]) }}"
                                class="text-dark text-decoration-none link-primary">{{ $product->name }}</a>
                        </h3>
                        @if ($bestVariation)
                        <div>
                            <del class="text-muted">{{ number_format($bestVariation->price, 0, ',', '.') }}đ</del>
                            <span class="text-danger ms-2">{{ number_format($bestVariation->sale_price, 0, ',', '.') }}đ</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Thêm một chút inline style tối thiểu để xử lý hover, vì Bootstrap không hỗ trợ trực tiếp -->
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
            <div class="nav flex-column nav-pills category-tabs p-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                @foreach ($categories as $key => $category)
                    <button class="nav-link category-tab {{ $key === 0 ? 'active' : '' }}"
                            id="v-pills-{{ $category->id }}-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#v-pills-{{ $category->id }}"
                            type="button"
                            role="tab"
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
                         id="v-pills-{{ $category->id }}"
                         role="tabpanel"
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
                                                <a href="{{ route('client.product.product-details', ['id' => $product->id]) }}" class="d-block">
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
                                                    <a href="#" class="text-dark me-2"><i class="flaticon-heart"></i></a>
                                                    <a href="{{ route('client.product.product-details', ['id' => $product->id]) }}" class="text-dark me-2"><i class="flaticon-search"></i></a>
                                                    <a href="{{ route('cart.add', ['id' => $product->id]) }}" class="text-dark"><i class="flaticon-shopping-cart"></i></a>
                                                </div>
                                            </div>
                                            <div class="product-title text-center py-2">
                                                <h3 class="product-title mb-2">
                                                    <a href="{{ route('client.product.product-details', ['id' => $product->id]) }}"
                                                       class="text-dark text-decoration-none link-primary">{{ $product->name }}</a>
                                                </h3>
                                                @if ($bestVariation)
                                                    <div>
                                                        <del class="text-muted">{{ number_format($bestVariation->price, 0, ',', '.') }}đ</del>
                                                        <span class="text-danger ms-2">{{ number_format($bestVariation->sale_price, 0, ',', '.') }}đ</span>
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
                                        <a href="{{ route('client.product.product-details', $product->id) }}" class="d-block">
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
                                            <del class="text-muted">{{ number_format($minSalePrice, 0, ',', '.') }} đ</del>
                                            <span class="text-danger">{{ number_format($minPrice, 0, ',', '.') }} đ</span>
                                            @else
                                            <span class="text-primary">{{ number_format($minPrice, 0, ',', '.') }} đ</span>
                                            @endif
                                            @else
                                            <span class="text-muted">Liên hệ</span>
                                            @endif
                                        </p>
                                        <a href="{{ route('cart.add', ['id' => $product->id]) }}" class="btn btn-sm btn-success position-absolute bottom-0 end-0 m-2 d-none hover-show">
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
                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
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




<!-- ===============  top products area start =============== -->
{{-- <div class="top-product-wrapper ml-110 mt-100">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 mb-25">
                        <div class="section-head">
                            <h2 class="section-title">Top Sales This Week</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    {{-- Danh Mục Sản Phẩm Ở Đây --}}

</div>
</div>
</div>
</div>


<!-- =============== banner xl area start =============== -->
<div class="banner-xl-area ml-110 mt-100">
  
 <!-- =============== banner xl area start =============== -->
 <div class="banner-xl-area ml-110 mt-100">
        <div class="container-fluid p-0">
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
   
<div class="tranding-product-wrapper ml-110 mt-70 position-relative">
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
                $discount = $originalPrice > $salePrice ? round((($originalPrice - $salePrice) / $originalPrice) * 100) : null;
            @endphp

            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4"> <!-- 4 sản phẩm trên 1 hàng -->
                <div class="product-card-l">
                    <div class="product-img position-relative overflow-hidden">
                        <a href="{{ route('client.product.product-details', $product->id) }}" class="d-block">
                            <!-- Ảnh chính: đồng bộ kích thước bằng ratio và object-fit -->
                            <div class="ratio ratio-1x1">
                                <img src="{{ asset($image) }}"
                                     alt="{{ $product->name }}"
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
                            <a href="#" class="text-dark me-2"><i class="flaticon-heart"></i></a>
                            <a href="{{ route('client.product.product-details', $product->id) }}" class="text-dark me-2"><i class="flaticon-search"></i></a>
                            <a href="{{ route('cart.add', ['id' => $product->id]) }}" class="text-dark"><i class="flaticon-shopping-cart"></i></a>
                        </div>
                    </div>
                    <div class="product-title text-center py-2">
                        <h3 class="product-title mb-2">
                            <a href="{{ route('client.product.product-details', $product->id) }}"
                               class="text-dark text-decoration-none link-primary">{{ $product->name }}</a>
                        </h3>
                        <div>
                            @if ($originalPrice > $salePrice)
                                <del class="text-muted">{{ number_format($originalPrice, 0, ',', '.') }}đ</del>
                                <span class="text-danger ms-2">{{ number_format($salePrice, 0, ',', '.') }}đ</span>
                            @else
                                <span class="text-danger">{{ number_format($originalPrice, 0, ',', '.') }}đ</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


        <!-- ===============  blog area start =============== -->
        {{-- Blog Viết Ở Đây --}}
        <div class="blog-area ml-110 mt-100 position-relative">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 mb-25">
                        <div class="section-head">
                            <h2 class="section-title">Our Latest Blog</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="swiper-blog-container overflow-hidden">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="blog-card-m">
                                    <div class="blog-img-m">
                                        <a href="blog-details.html"><img src="/client/assets/images/blog/bm-1.png"
                                                alt="" /></a>
                                        <div class="blog-actions">
                                            <a href="#"><i class="flaticon-share"></i></a>
                                        </div>
                                    </div>
                                    <div class="blog-content-m">
                                        <ul class="blog-info d-flex">
                                            <li class="blog-author">
                                                <img src="/client/assets/images/blog/blog-author1.png"
                                                    alt="" class="author-img" />
                                                <a href="#">Alex Avater</a>
                                            </li>
                                            <li class="blog-date">
                                                <i class="flaticon-time"></i>
                                                4th Jan 2021
                                            </li>
                                        </ul>
                                        <div class="blog-bottom">
                                            <h4 class="blog-title">
                                                <a href="blog-details.html">How can have anything you want in life if
                                                    you dress
                                                    for it.</a>
                                            </h4>
                                            <div class="blog-link-btn">
                                                <a href="blog-details.html">View This Story
                                                    <i class="flaticon-arrow-pointing-to-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="blog-card-m">
                                    <div class="blog-img-m">
                                        <a href="blog-details.html"><img src="/client/assets/images/blog/bm-2.png"
                                                alt="" /></a>
                                        <div class="blog-actions">
                                            <a href="#"><i class="flaticon-share"></i></a>
                                        </div>
                                    </div>
                                    <div class="blog-content-m">
                                        <ul class="blog-info d-flex">
                                            <li class="blog-author">
                                                <img src="/client/assets/images/blog/blog-author1.png"
                                                    alt="" class="author-img" />
                                                <a href="#">Alex Avater</a>
                                            </li>
                                            <li class="blog-date">
                                                <i class="flaticon-time"></i>
                                                4th Jan 2021
                                            </li>
                                        </ul>
                                        <div class="blog-bottom">
                                            <h4 class="blog-title">
                                                <a href="blog-details.html">The Coolest Fashion People to Follow in
                                                    Every Age
                                                    Group</a>
                                            </h4>
                                            <div class="blog-link-btn">
                                                <a href="blog-details.html">View This Story
                                                    <i class="flaticon-arrow-pointing-to-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="blog-card-m">
                                    <div class="blog-img-m">
                                        <a href="blog-details.html"><img src="/client/assets/images/blog/bm-3.png"
                                                alt="" /></a>
                                        <div class="blog-actions">
                                            <a href="#"><i class="flaticon-share"></i></a>
                                        </div>
                                    </div>
                                    <div class="blog-content-m">
                                        <ul class="blog-info d-flex">
                                            <li class="blog-author">
                                                <img src="/client/assets/images/blog/blog-author1.png"
                                                    alt="" class="author-img" />
                                                <a href="#">Alex Avater</a>
                                            </li>
                                            <li class="blog-date">
                                                <i class="flaticon-time"></i>
                                                4th Jan 2021
                                            </li>
                                        </ul>
                                        <div class="blog-bottom">
                                            <h4 class="blog-title">
                                                <a href="blog-details.html">Let us know your thoughts in this is
                                                    comments
                                                    below</a>
                                            </h4>
                                            <div class="blog-link-btn">
                                                <a href="blog-details.html">View This Story
                                                    <i class="flaticon-arrow-pointing-to-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="blog-card-m">
                                    <div class="blog-img-m">
                                        <a href="blog-details.html"><img src="/client/assets/images/blog/bm-4.png"
                                                alt="" /></a>
                                        <div class="blog-actions">
                                            <a href="#"><i class="flaticon-share"></i></a>
                                        </div>
                                    </div>
                                    <div class="blog-content-m">
                                        <ul class="blog-info d-flex">
                                            <li class="blog-author">
                                                <img src="/client/assets/images/blog/blog-author1.png"
                                                    alt="" class="author-img" />
                                                <a href="#">Alex Avater</a>
                                            </li>
                                            <li class="blog-date">
                                                <i class="flaticon-time"></i>
                                                4th Jan 2021
                                            </li>
                                        </ul>
                                        <div class="blog-bottom">
                                            <h4 class="blog-title">
                                                <a href="blog-details.html">How to come up with a good name for your
                                                    fashion
                                                    blog?</a>
                                            </h4>
                                            <div class="blog-link-btn">
                                                <a href="blog-details.html">View This Story
                                                    <i class="flaticon-arrow-pointing-to-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- next / prev arrows -->
                        <div class="swiper-button-next">
                            <i class="flaticon-arrow-pointing-to-right"></i>
                        </div>
                        <div class="swiper-button-prev">
                            <i class="flaticon-arrow-pointing-to-left"></i>
                        </div>
                        <!-- !next / prev arrows -->
                    </div>
                </div>
            </div>
        </div>
        <!-- ===============  blog area end =============== -->

        <!-- ===============  newslatter area start  =============== -->
        {{-- Gửi Mail Đánh Giá --}}
        <div class="newslatter-area ml-110 mt-100">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="newslatter-wrap text-center">
                            <h5>Connect To EG</h5>
                            <h2 class="newslatter-title">Join Our Newsletter</h2>
                            <p>
                                Hey you, sign up it only, Get this limited-edition T-shirt
                                Free!
                            </p>

                            <form action="#" method="POST">
                                <div class="newslatter-form">
                                    <input type="text" placeholder="Type Your Email" />
                                    <button type="submit">
                                        Send <i class="bi bi-envelope-fill"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ===============  newslatter area end  =============== -->

        <!-- ===============  footer area start  =============== -->
    </div>
    @endsection
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
    </script>
    @endpush