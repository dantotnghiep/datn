@extends('client.layouts.master')
@section('content')
    @include('client.layouts.partials.lelf-navbar')

    <!-- ===============peoduct area start=============== -->

    <div class="product-area ml-110 mt-100">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xxl-3 col-xl-4 col-lg-4">
                    <div class="product-sidebar">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="sidebar-searchbar-wrap">
                                    <form action="#" method="POST" class="sidebar-searchbar-form">
                                        <input type="text" name="sidebar-search-input" id="sidebar-search-input"
                                            placeholder="What are you loking for ?">
                                        <button type="submit"><i class="bi bi-search"></i></button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="sidebar-category">
                                    <h5 class="sb-title">CHỌN THEO DANH MỤC</h5>

                                    <div class="sb-category-dropdown">
                                        <div class="accordion" id="accordionExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                        aria-expanded="true" aria-controls="collapseOne">
                                                        Bộ Sưu Tập Nữ
                                                    </button>
                                                </h2>
                                                <div id="collapseOne" class="accordion-collapse collapse show"
                                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <ul class="sb-category-list">
                                                            <li><a href="product.html">Áo sơ mi lụa nam</a> <span
                                                                    class="product-amount">(10)</span></li>
                                                            <li><a href="product.html">Quần short cam nam</a> <span
                                                                    class="product-amount">(22)</span></li>
                                                            <li><a href="product.html">Váy dạ hội</a> <span
                                                                    class="product-amount">(08)</span></li>
                                                            <li><a href="#">Áo thun</a> <span
                                                                    class="product-amount">(41)</span></li>
                                                            <li><a href="product.html">Áo hoodie đen Ghost Mannequin</a>
                                                                <span class="product-amount">(15)</span></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwo">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                        aria-expanded="false" aria-controls="collapseTwo">
                                                        Bộ Sưu Tập Trẻ Em
                                                    </button>
                                                </h2>
                                                <div id="collapseTwo" class="accordion-collapse collapse"
                                                    aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <ul class="sb-category-list">
                                                            <li><a href="#">Áo sơ mi lụa nam</a> <span class="product-amount">(10)</span></li>
                                                            <li><a href="#">Quần short cam nam</a> <span class="product-amount">(22)</span></li>
                                                            <li><a href="#">Váy dạ hội</a> <span class="product-amount">(08)</span></li>
                                                            <li><a href="#">Áo thun</a> <span class="product-amount">(41)</span></li>
                                                            <li><a href="#">Áo hoodie đen Ghost Mannequin</a> <span class="product-amount">(15)</span></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingThree">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                                        aria-expanded="false" aria-controls="collapseThree">
                                                        Bộ Sưu Tập Nam
                                                    </button>
                                                </h2>
                                                <div id="collapseThree" class="accordion-collapse collapse"
                                                    aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <ul class="sb-category-list">
                                                            <li><a href="#">Áo sơ mi lụa nam</a> <span class="product-amount">(10)</span></li>
                                                            <li><a href="#">Quần short cam nam</a> <span class="product-amount">(22)</span></li>
                                                            <li><a href="#">Váy dự tiệc</a> <span class="product-amount">(08)</span></li>
                                                            <li><a href="#">Áo thun</a> <span class="product-amount">(41)</span></li>
                                                            <li><a href="#">Áo hoodie đen Ghost Mannequin</a> <span class="product-amount">(15)</span></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingFour">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                                        aria-expanded="false" aria-controls="collapseFour">
                                                        Sức khỏe & Sắc đẹp
                                                    </button>
                                                </h2>
                                                <div id="collapseFour" class="accordion-collapse collapse"
                                                    aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <ul class="sb-category-list">
                                                            <li><a href="#">Man Casual Silk Shirt</a> <span
                                                                    class="product-amount">(10)</span></li>
                                                            <li><a href="#">Man Orange Shorts</a> <span
                                                                    class="product-amount">(22)</span></li>
                                                            <li><a href="#">Party Dress</a> <span
                                                                    class="product-amount">(08)</span></li>
                                                            <li><a href="#">T-Shirt</a> <span
                                                                    class="product-amount">(41)</span></li>
                                                            <li><a href="#">Ghost Mannequin Black Hoodie</a> <span
                                                                    class="product-amount">(15)</span></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingFive">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseFive"
                                                        aria-expanded="false" aria-controls="collapseFive">
                                                        Jewellery & Daimond
                                                    </button>
                                                </h2>
                                                <div id="collapseFive" class="accordion-collapse collapse"
                                                    aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <ul class="sb-category-list">
                                                            <li><a href="#">Man Casual Silk Shirt</a> <span
                                                                    class="product-amount">(10)</span></li>
                                                            <li><a href="#">Man Orange Shorts</a> <span
                                                                    class="product-amount">(22)</span></li>
                                                            <li><a href="#">Party Dress</a> <span
                                                                    class="product-amount">(08)</span></li>
                                                            <li><a href="#">T-Shirt</a> <span
                                                                    class="product-amount">(41)</span></li>
                                                            <li><a href="#">Ghost Mannequin Black Hoodie</a> <span
                                                                    class="product-amount">(15)</span></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 ">
                                <div class="sb-pricing-range">
                                    <h5 class="sb-title">PRICE</h5>
                                    <form action="#" method="POST">
                                        <div class="price-range-slider">
                                            <div id="slider-range" class="range-bar"></div>
                                            <div
                                                class="pricing-range-buttom d-flex align-items-center justify-content-between">
                                                <div class="price-filter-btn">
                                                    <button type="submit">Filter</button>
                                                </div>
                                                <div class="pricing-value">
                                                    <span>Price : </span> <input type="text" id="amount" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="top-sell-cards">
                                    <h5 class="sb-title">TOP SALE THIS WEEK</h5>

                                    <div class="row">
                                        <div class="co-lg-12">
                                            <div class="product-card-m d-flex align-content-center">
                                                <div class="product-img-m">
                                                    <a href="product-details.html"><img
                                                            src="/client/assets/images/product/pm1.png"
                                                            alt=""></a>
                                                    <div class="product-cart-icon"><a href="#"><i
                                                                class="flaticon-shopping-cart"></i></a></div>
                                                </div>
                                                <div class="product-details-m">
                                                    <a class="product-title-m" href="product-details.html">Men Casual
                                                        Summer Sale</a>
                                                    <ul class="d-flex product-rating-m">
                                                        <li><i class="bi bi-star-fill"></i></li>
                                                        <li><i class="bi bi-star-fill"></i></li>
                                                        <li><i class="bi bi-star-fill"></i></li>
                                                        <li><i class="bi bi-star-fill"></i></li>
                                                        <li><i class="bi bi-star"></i></li>
                                                    </ul>
                                                    <div class="product-price">
                                                        <del class="old-price">$302.74</del><ins
                                                            class="new-price">$290.05</ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="product-card-m d-flex align-items-center">
                                                <div class="product-img-m">
                                                    <a href="product-details.html"><img
                                                            src="/client/assets/images/product/pm-4.png"
                                                            alt=""></a>
                                                    <div class="product-cart-icon"><a href="#"><i
                                                                class="flaticon-shopping-cart"></i></a></div>
                                                </div>
                                                <div class="product-details-m">
                                                    <a class="product-title-m" href="product-details.html">Men Casual
                                                        Summer Sale</a>
                                                    <ul class="d-flex product-rating-m">
                                                        <li><i class="bi bi-star-fill"></i></li>
                                                        <li><i class="bi bi-star-fill"></i></li>
                                                        <li><i class="bi bi-star-fill"></i></li>
                                                        <li><i class="bi bi-star-fill"></i></li>
                                                        <li><i class="bi bi-star"></i></li>
                                                    </ul>
                                                    <div class="product-price">
                                                        <del class="old-price">$302.74</del><ins
                                                            class="new-price">$290.05</ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="product-card-m d-flex align-items-center">
                                                <div class="product-img-m">
                                                    <a href="product-details.html"><img
                                                            src="/client/assets/images/product/pm-5.png"
                                                            alt=""></a>
                                                    <div class="product-cart-icon"><a href="#"><i
                                                                class="flaticon-shopping-cart"></i></a></div>
                                                </div>
                                                <div class="product-details-m">
                                                    <a class="product-title-m" href="product-details.html">Men Casual
                                                        Summer Sale</a>
                                                    <ul class="d-flex product-rating-m">
                                                        <li><i class="bi bi-star-fill"></i></li>
                                                        <li><i class="bi bi-star-fill"></i></li>
                                                        <li><i class="bi bi-star-fill"></i></li>
                                                        <li><i class="bi bi-star-fill"></i></li>
                                                        <li><i class="bi bi-star"></i></li>
                                                    </ul>
                                                    <div class="product-price">
                                                        <del class="old-price">$302.74</del><ins
                                                            class="new-price">$290.05</ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="product-card-m d-flex align-items-center">
                                                <div class="product-img-m">
                                                    <a href="product-details.html"><img
                                                            src="/client/assets/images/product/pm-6.png"
                                                            alt=""></a>
                                                    <div class="product-cart-icon"><a href="#"><i
                                                                class="flaticon-shopping-cart"></i></a></div>
                                                </div>
                                                <div class="product-details-m">
                                                    <a class="product-title-m" href="product-details.html">Men Casual
                                                        Summer Sale</a>
                                                    <ul class="d-flex product-rating-m">
                                                        <li><i class="bi bi-star-fill"></i></li>
                                                        <li><i class="bi bi-star-fill"></i></li>
                                                        <li><i class="bi bi-star-fill"></i></li>
                                                        <li><i class="bi bi-star-fill"></i></li>
                                                        <li><i class="bi bi-star"></i></li>
                                                    </ul>
                                                    <div class="product-price">
                                                        <del class="old-price">$302.74</del><ins
                                                            class="new-price">$290.05</ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-12">
                                <div class="sb-tags">
                                    <h5 class="sb-title">PRODUCT TAG</h5>

                                    <ul class="sb-tag-list">
                                        <li><a href="#">Casual</a></li>
                                        <li><a href="#">Kurtas & Kurtis</a></li>
                                        <li><a href="#">Summer</a></li>
                                        <li><a href="#">Spring</a></li>
                                        <li><a href="#">Winter</a></li>
                                        <li><a href="#">Baby</a></li>
                                        <li><a href="#">Man</a></li>
                                        <li><a href="#">Coot</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-sidebar-banner">
                        <a href="#"><img src="/client/assets/images/banner/sb-banner1.png" alt=""></a>
                    </div>
                </div>
                <div class="col-xxl-9 col-xl-8 col-lg-8">
                    <div class="product-sorting d-flex justify-content-between align-items-center">
                        <div class="show-text"><span>Showing 1-9 of 18 Result</span></div>
                        <div class="category-sort">
                            <select name="category-sort" id="category-sort">
                                <option selected>Default Soprting</option>
                                <option value="1">Sort by Size</option>
                                <option value="2">Sort by Price</option>
                                <option value="3">Sort by Color</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-sm-4">
                            <div class="product-card-l">
                                <div class="product-img">
                                    <a href="product-details.html">
                                        <img src="/client/assets/images/product/p-dbl1.png" alt="">
                                        <img src="/client/assets/images/product/p-dbl2.png" alt=""
                                            class="hover-img">
                                    </a>
                                    <div class="product-lavels">

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
                                    <h3 class="product-title"><a href="product-details.html">Ghost Mannequin Pant</a>
                                    </h3>
                                    <div class="product-price">
                                        <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-sm-4">
                            <div class="product-card-l">
                                <div class="product-img">
                                    <a href="product-details.html">
                                        <img src="/client/assets/images/product/p-dbl4.png" alt="">
                                        <img src="/client/assets/images/product/p-dbl3.png" alt=""
                                            class="hover-img">
                                    </a>
                                    <div class="product-lavels">
                                        <span class="stock-out">-20%</span>
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
                                    <h3 class="product-title"><a href="product-details.html">Women Mannequin Tops</a>
                                    </h3>
                                    <div class="product-price">
                                        <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-sm-4">
                            <div class="product-card-l">
                                <div class="product-img">
                                    <a href="product-details.html">
                                        <img src="/client/assets/images/product/p-dbl5.png" alt="">
                                        <img src="/client/assets/images/product/p-dbl4.png" alt=""
                                            class="hover-img">
                                    </a>
                                    <div class="product-lavels">
                                        <span class="stock-out">Sold Out</span>
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
                                    <h3 class="product-title"><a href="product-details.html">Super drass full</a></h3>
                                    <div class="product-price">
                                        <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-sm-4">
                            <div class="product-card-l">
                                <div class="product-img">
                                    <a href="product-details.html">
                                        <img src="/client/assets/images/product/p-dbl7.png" alt="">
                                        <img src="/client/assets/images/product/p-dbl8.png" alt=""
                                            class="hover-img">
                                    </a>
                                    <div class="product-lavels">

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
                                    <h3 class="product-title"><a href="product-details.html">Women Casual Dress</a></h3>
                                    <div class="product-price">
                                        <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-sm-4">
                            <div class="product-card-l">
                                <div class="product-img">
                                    <a href="product-details.html">
                                        <img src="/client/assets/images/product/p-dbl10.png" alt="">
                                        <img src="/client/assets/images/product/p-dbl9.png" alt=""
                                            class="hover-img">
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
                                    <h3 class="product-title"><a href="product-details.html">Women Renta Silk Dress</a>
                                    </h3>
                                    <div class="product-price">
                                        <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-sm-4">
                            <div class="product-card-l">
                                <div class="product-img">
                                    <a href="product-details.html">
                                        <img src="/client/assets/images/product/p-dbl12.png" alt="">
                                        <img src="/client/assets/images/product/p-dbl11.png" alt=""
                                            class="hover-img">
                                    </a>
                                    <div class="product-lavels">

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
                                    <h3 class="product-title"><a href="product-details.html">Men Folded Casual Shirt</a>
                                    </h3>
                                    <div class="product-price">
                                        <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-sm-4">
                            <div class="product-card-l">
                                <div class="product-img">
                                    <a href="product-details.html">
                                        <img src="/client/assets/images/product/p-dbl12.png" alt="">
                                        <img src="/client/assets/images/product/p-dbl1.png" alt=""
                                            class="hover-img">
                                    </a>
                                    <div class="product-lavels">

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
                                    <h3 class="product-title"><a href="product-details.html">Man Yellow Pattern
                                            Shirt</a></h3>
                                    <div class="product-price">
                                        <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-sm-4">
                            <div class="product-card-l">
                                <div class="product-img">
                                    <a href="product-details.html">
                                        <img src="/client/assets/images/product/p-dbl13.png" alt="">
                                        <img src="/client/assets/images/product/p-dbl4.png" alt=""
                                            class="hover-img">
                                    </a>
                                    <div class="product-lavels">

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
                                    <h3 class="product-title"><a href="product-details.html">Women Renta Silk Dress</a>
                                    </h3>
                                    <div class="product-price">
                                        <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-sm-4">
                            <div class="product-card-l">
                                <div class="product-img">
                                    <a href="product-details.html">
                                        <img src="/client/assets/images/product/p-dbl14.png" alt="">
                                        <img src="/client/assets/images/product/p-dbl3.png" alt=""
                                            class="hover-img">
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
                                    <h3 class="product-title"><a href="product-details.html">Amazing Overalls Kids Tops
                                        </a></h3>
                                    <div class="product-price">
                                        <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-sm-4">
                            <div class="product-card-l">
                                <div class="product-img">
                                    <a href="product-details.html">
                                        <img src="/client/assets/images/product/p-dbl15.png" alt="">
                                        <img src="/client/assets/images/product/p-dbl6.png" alt=""
                                            class="hover-img">
                                    </a>
                                    <div class="product-lavels">

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
                                    <h3 class="product-title"><a href="product-details.html">Women full jeans</a></h3>
                                    <div class="product-price">
                                        <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-sm-4">
                            <div class="product-card-l">
                                <div class="product-img">
                                    <a href="product-details.html">
                                        <img src="/client/assets/images/product/p-dbl1.png" alt="">
                                        <img src="/client/assets/images/product/p-dbl2.png" alt=""
                                            class="hover-img">
                                    </a>
                                    <div class="product-lavels">

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
                                    <h3 class="product-title"><a href="product-details.html">Amazing Overalls Kids Tops
                                        </a></h3>
                                    <div class="product-price">
                                        <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-sm-4">
                            <div class="product-card-l">
                                <div class="product-img">
                                    <a href="product-details.html">
                                        <img src="/client/assets/images/product/p-dbl4.png" alt="">
                                        <img src="/client/assets/images/product/p-dbl3.png" alt=""
                                            class="hover-img">
                                    </a>
                                    <div class="product-lavels">
                                        <span class="stock-out">-20%</span>
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
                                    <h3 class="product-title"><a href="product-details.html">Women Renta Silk Dress</a>
                                    </h3>
                                    <div class="product-price">
                                        <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-sm-4">
                            <div class="product-card-l">
                                <div class="product-img">
                                    <a href="product-details.html">
                                        <img src="/client/assets/images/product/p-dbl5.png" alt="">
                                        <img src="/client/assets/images/product/p-dbl4.png" alt=""
                                            class="hover-img">
                                    </a>
                                    <div class="product-lavels">
                                        <span class="stock-out">Sold Out</span>
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
                                    <h3 class="product-title"><a href="product-details.html">Women Renta Silk Dress</a>
                                    </h3>
                                    <div class="product-price">
                                        <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-sm-4">
                            <div class="product-card-l">
                                <div class="product-img">
                                    <a href="product-details.html">
                                        <img src="/client/assets/images/product/p-dbl7.png" alt="">
                                        <img src="/client/assets/images/product/p-dbl8.png" alt=""
                                            class="hover-img">
                                    </a>
                                    <div class="product-lavels">

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
                                    <h3 class="product-title"><a href="product-details.html">Women Renta Silk Dress</a>
                                    </h3>
                                    <div class="product-price">
                                        <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-sm-4">
                            <div class="product-card-l">
                                <div class="product-img">
                                    <a href="product-details.html">
                                        <img src="/client/assets/images/product/p-dbl10.png" alt="">
                                        <img src="/client/assets/images/product/p-dbl1.png" alt=""
                                            class="hover-img">
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
                                    <h3 class="product-title"><a href="product-details.html">Overalls Kids Tops </a>
                                    </h3>
                                    <div class="product-price">
                                        <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-sm-4">
                            <div class="product-card-l">
                                <div class="product-img">
                                    <a href="product-details.html">
                                        <img src="/client/assets/images/product/p-dbl12.png" alt="">
                                        <img src="/client/assets/images/product/p-dbl11.png" alt=""
                                            class="hover-img">
                                    </a>
                                    <div class="product-lavels">

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
                                    <h3 class="product-title"><a href="product-details.html">Renta Silk Dress</a></h3>
                                    <div class="product-price">
                                        <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-sm-4">
                            <div class="product-card-l">
                                <div class="product-img">
                                    <a href="product-details.html">
                                        <img src="/client/assets/images/product/p-dbl16.png" alt="">
                                        <img src="/client/assets/images/product/p-dbl1.png" alt=""
                                            class="hover-img">
                                    </a>
                                    <div class="product-lavels">

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
                                    <h3 class="product-title"><a href="product-details.html">Yellow Silk Dress</a></h3>
                                    <div class="product-price">
                                        <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-sm-4">
                            <div class="product-card-l">
                                <div class="product-img">
                                    <a href="product-details.html">
                                        <img src="/client/assets/images/product/p-dbl13.png" alt="">
                                        <img src="/client/assets/images/product/p-dbl4.png" alt=""
                                            class="hover-img">
                                    </a>
                                    <div class="product-lavels">

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
                                    <h3 class="product-title"><a href="product-details.html">Women Winter dress</a></h3>
                                    <div class="product-price">
                                        <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-sm-4">
                            <div class="product-card-l">
                                <div class="product-img">
                                    <a href="product-details.html">
                                        <img src="/client/assets/images/product/p-dbl14.png" alt="">
                                        <img src="/client/assets/images/product/p-dbl3.png" alt=""
                                            class="hover-img">
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
                                    <h3 class="product-title"><a href="product-details.html">Perfect summer top</a></h3>
                                    <div class="product-price">
                                        <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-sm-4">
                            <div class="product-card-l">
                                <div class="product-img">
                                    <a href="product-details.html">
                                        <img src="/client/assets/images/product/p-dbl15.png" alt="">
                                        <img src="/client/assets/images/product/p-dbl6.png" alt=""
                                            class="hover-img">
                                    </a>
                                    <div class="product-lavels">

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
                                    <h3 class="product-title"><a href="product-details.html">Women Renta Silk Dress</a>
                                    </h3>
                                    <div class="product-price">
                                        <del class="old-price">$302.74</del><ins class="new-price">$290.05</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-50">
                            <div class="custom-pagination d-flex justify-content-center">
                                <ul class="d-flex pagination-links">
                                    <li><a href="#" class="has-arrow"><i
                                                class="flaticon-arrow-pointing-to-left"></i></a></li>
                                    <li><a href="#">1</a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#" class="has-arrow"><i
                                                class="flaticon-arrow-pointing-to-right"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ===============peoduct area end=============== -->




    <!-- ===============  newslatter area start  =============== -->
    <div class="newslatter-area ml-110 mt-100">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="newslatter-wrap text-center">
                        <h5>Connect To EG</h5>
                        <h2 class="newslatter-title">Join Our Newsletter</h2>
                        <p>Hey you, sign up it only, Get this limited-edition T-shirt Free!</p>

                        <form action="#" method="POST">
                            <div class="newslatter-form">
                                <input type="text" placeholder="Type Your Email">
                                <button type="submit">Send <i class="bi bi-envelope-fill"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ===============  newslatter area end  =============== -->
@endsection
