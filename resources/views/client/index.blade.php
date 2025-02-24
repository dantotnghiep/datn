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
                                    <h3 class="banner-title">Woman’s Winter Sale 2021</h3>
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
                                    <h3 class="banner-title">Men’s Casul Summer 2021</h3>
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

        <!-- ===============  top products area start =============== -->
        <div class="top-product-wrapper ml-110 mt-100">
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
                    <div class="col-xxl-3 col-xl-3 col-lg-4">
                        <div class="nav flex-column nav-pills category-tabs" id="v-pills-tab2" role="tablist"
                            aria-orientation="vertical">
                            @foreach ($categories as $key => $category)
                                <button class="nav-link {{ $key === 0 ? 'active' : '' }}"
                                    id="v-pills-{{ $category->id }}-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-{{ $category->id }}" type="button" role="tab"
                                    aria-controls="v-pills-{{ $category->id }}"
                                    aria-selected="{{ $key === 0 ? 'true' : 'false' }}">
                                    {{ $category->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-xxl-9 col-xl-9 col-lg-8">
                        <div class="tab-content" id="v-pills-tabContent">
                            @foreach ($categories as $key => $category)
                                <div class="tab-pane fade {{ $key === 0 ? 'show active' : '' }}"
                                    id="v-pills-{{ $category->id }}" role="tabpanel"
                                    aria-labelledby="v-pills-{{ $category->id }}-tab">
                                    <div class="row">
                                        @if ($category->products->count() > 0)
                                            @foreach ($category->products as $product)
                                                <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-4 col-sm-6">
                                                    <div class="product-card-m d-flex align-items-center">
                                                        <div class="product-img-m">
                                                            <a href="product-details.html">
                                                                <img src="{{ $product->mainImage ? asset('storage/' . $product->mainImage->url) : asset('storage/products/default.jpg') }}"
                                                                    alt="{{ $product->name }}">
                                                            </a>
                                                            <div class="product-cart-icon">
                                                                <button type="submit"><i class="flaticon-shopping-cart"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="product-details-m">
                                                            <a class="product-title-m"
                                                                href="product-details.html">{{ $product->name }}</a>
                                                            <div class="product-price">
                                                                <del class="old-price">${{ $product->sale_price }}</del>
                                                                <ins class="new-price">${{ $product->price }}</ins>
                                                            </div>
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
        </div>
    </div>
    <!-- ===============  top products area end =============== -->

    <!-- ===============  tranding product area start =============== -->

    {{-- Sản Phẩm Bán Chạy Theeo Tuần Owe Đây Làm Theo Model Trên Csdl --}}
    <div class="tranding-product-wrapper ml-110 mt-70 position-relative">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 mb-50">
                    <div class="section-head">
                        <h2 class="section-title">Our Trending Product</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="swiper-tranding-container overflow-hidden pb-30">
                  <!-- hero slider slides -->
                  <div class="swiper-wrapper">
                    @foreach ($products as $prd)
                    <div class="">
                        <div class="product-card-xl">
                          <div class="product-img-xl">
                            <a href="product-details.html"><img src="/client/assets/images/product/pxl-1.png" alt=""
                                class="img-fluid" /></a>
                            <div class="product-actions-xl">
                              <a href="#"><i class="flaticon-heart"></i></a>
                              <a href="product-details.html"><i class="flaticon-search"></i></a>
                              <a onclick="AddCart({{$prd->id}})" href="javascript:"><i class="flaticon-shopping-cart"></i></a>
                            </div>
                          </div>
                          <div class="product-content-xl text-center">
                            <ul class="d-flex product-rating-xl">
                              <li><i class="bi bi-star-fill"></i></li>
                              <li><i class="bi bi-star-fill"></i></li>
                              <li><i class="bi bi-star-fill"></i></li>
                              <li><i class="bi bi-star-fill"></i></li>
                              <li><i class="bi bi-star"></i></li>
                            </ul>
                            <a href="product-details.html" class="product-title">{{$prd->name}}</a>
                            <div class="product-price">
                              <del class="old-price">{{number_format($prd->price)}}</del><ins class="new-price">{{number_format($prd->sale_price)}}</ins>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endforeach
                    
                    
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
                </div>
              </div>
        </div>
    </div>
    <!-- ===============  tranding product area end =============== -->

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

    <!-- ===============  recent product start =============== -->
    {{-- Sản Phẩm Còn Hàng --}}
    <div class="recent-product-wrapper ml-110 mt-100">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 mb-25">
                    <div class="section-head">
                        <h2 class="section-title">Recently Stock</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-3 col-xl-3 col-lg-4">
                    <div class="nav flex-column nav-pills category-tabs" id="v-pills-tab" role="tablist"
                        aria-orientation="vertical">
                        <button class="nav-link active category-tab" id="eg-pills-11" data-bs-toggle="pill"
                            data-bs-target="#eg-pills-one1" type="button" role="tab" aria-controls="eg-pills-one1"
                            aria-selected="true">
                            All Collection
                        </button>

                        <button class="nav-link category-tab" id="eg-pills-22" data-bs-toggle="pill"
                            data-bs-target="#eg-pills-two2" type="button" role="tab" aria-controls="eg-pills-two2"
                            aria-selected="false">
                            Mens Collcetion
                        </button>

                        <button class="nav-link category-tab" id="eg-pills-33" data-bs-toggle="pill"
                            data-bs-target="#eg-pills-three3" type="button" role="tab"
                            aria-controls="eg-pills-three3" aria-selected="false">
                            Women Collection
                        </button>

                        <button class="nav-link category-tab" id="eg-pills-44" data-bs-toggle="pill"
                            data-bs-target="#eg-pills-four4" type="button" role="tab"
                            aria-controls="eg-pills-four4" aria-selected="false">
                            Kids Collection
                        </button>

                        <button class="nav-link category-tab" id="eg-pills-55" data-bs-toggle="pill"
                            data-bs-target="#eg-pills-five5" type="button" role="tab"
                            aria-controls="eg-pills-five5" aria-selected="false">
                            Winter Collection
                        </button>

                        <button class="nav-link category-tab" id="eg-pills-66" data-bs-toggle="pill"
                            data-bs-target="#eg-pills-six6" type="button" role="tab" aria-controls="eg-pills-six6"
                            aria-selected="false">
                            Summer Collection
                        </button>
                    </div>
                </div>
                <div class="col-xxl-9 col-xl-9 col-lg-8">
                    <div class="tab-content eg-tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active eg-product-tab-pane" id="eg-pills-one1" role="tabpanel"
                            aria-labelledby="eg-pills-11">
                            <div class="row">
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl1.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl2.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels">
                                                <span class="new">New</span>
                                            </div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Man Yellow Pattern Shirt</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl3.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl4.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Ghost Mannequin Pant</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl5.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl6.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl7.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl8.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels">
                                                <span class="discount">-40%</span>
                                            </div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Men Folded Casual Shirt</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl9.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl10.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Kids Renta Summer Sale</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl11.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl12.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Amazing Overalls Kids Tops</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl17.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl16.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels">
                                                <span class="new">New</span>
                                            </div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Man Yellow Pattern Shirt</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl-1.png" alt="" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade eg-product-tab-pane" id="eg-pills-two2" role="tabpanel"
                            aria-labelledby="eg-pills-22">
                            <div class="row">
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl1.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl2.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels">
                                                <span class="new">New</span>
                                            </div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl3.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl4.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl5.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl6.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl7.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl8.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels">
                                                <span class="discount">-40%</span>
                                            </div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl9.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl10.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl11.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl12.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl17.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl16.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels">
                                                <span class="new">New</span>
                                            </div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl-1.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl11.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade eg-product-tab-pane" id="eg-pills-three3" role="tabpanel"
                            aria-labelledby="eg-pills-33">
                            <div class="row">
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl1.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl2.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels">
                                                <span class="new">New</span>
                                            </div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl3.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl4.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl5.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl6.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl7.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl8.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels">
                                                <span class="discount">-40%</span>
                                            </div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl9.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl10.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl11.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl12.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl17.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl16.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels">
                                                <span class="new">New</span>
                                            </div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl-1.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl15.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade eg-product-tab-pane" id="eg-pills-four4" role="tabpanel"
                            aria-labelledby="eg-pills-44">
                            <div class="row">
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl1.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl2.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels">
                                                <span class="new">New</span>
                                            </div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl3.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl4.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl5.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl6.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl7.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl8.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels">
                                                <span class="discount">-40%</span>
                                            </div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl9.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl10.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl11.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl12.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl17.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl16.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels">
                                                <span class="new">New</span>
                                            </div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl-1.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl6.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade eg-product-tab-pane" id="eg-pills-five5" role="tabpanel"
                            aria-labelledby="eg-pills-55">
                            <div class="row">
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl1.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl2.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels">
                                                <span class="new">New</span>
                                            </div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl3.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl4.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl5.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl6.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl7.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl8.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels">
                                                <span class="discount">-40%</span>
                                            </div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl9.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl10.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl11.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl12.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl17.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl16.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels">
                                                <span class="new">New</span>
                                            </div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl-1.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl11.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade eg-product-tab-pane" id="eg-pills-six6" role="tabpanel"
                            aria-labelledby="eg-pills-66">
                            <div class="row">
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl1.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl2.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels">
                                                <span class="new">New</span>
                                            </div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl3.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl4.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl5.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl6.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl7.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl8.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels">
                                                <span class="discount">-40%</span>
                                            </div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl9.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl10.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl11.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl12.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl17.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl16.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels">
                                                <span class="new">New</span>
                                            </div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 xol-xl-3 col-lg-4 col-md-4 col-sm-6">
                                    <div class="product-card-l">
                                        <div class="product-img">
                                            <a href="product-details.html">
                                                <img src="/client/assets/images/product/p-dbl-1.png" alt="" />
                                                <img src="/client/assets/images/product/p-dbl10.png" alt=""
                                                    class="hover-img" />
                                            </a>
                                            <div class="product-lavels"></div>
                                            <div class="product-actions">
                                                <a href="#"><i class="flaticon-heart"></i></a>
                                                <a href="product-details.html"><i class="flaticon-search"></i></a>
                                                <a href="cart.html"><i class="flaticon-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <ul class="d-flex product-rating">
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star-fill"></i></li>
                                                <li><i class="bi bi-star"></i></li>
                                                <li>(<span>8</span> Review)</li>
                                            </ul>
                                            <h3 class="product-title">
                                                <a href="product-details.html">Women Renta Silk Dress</a>
                                            </h3>
                                            <div class="product-price">
                                                <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ===============  recent product end =============== -->

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
                                            <img src="/client/assets/images/blog/blog-author1.png" alt=""
                                                class="author-img" />
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
                                            <img src="/client/assets/images/blog/blog-author1.png" alt=""
                                                class="author-img" />
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
                                            <img src="/client/assets/images/blog/blog-author1.png" alt=""
                                                class="author-img" />
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
                                            <img src="/client/assets/images/blog/blog-author1.png" alt=""
                                                class="author-img" />
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
