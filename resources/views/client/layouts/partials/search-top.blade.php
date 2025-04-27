<!-- ===============Breadcrumb area start=============== -->
<div class="breadcrumb-area ml-110">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-bg d-flex justify-content-center align-items-center">
                    <div class="breadcrumb-shape1 position-absolute top-0 end-0">
                        <img src="assets/images/shapes/bs-right.png" alt="">
                    </div>
                    <div class="breadcrumb-shape2 position-absolute bottom-0 start-0">
                        <img src="assets/images/shapes/bs-left.png" alt="">
                    </div>
                    <div class="breadcrumb-content text-center">
                        <h2 class="page-title">Our All Products</h2>
                        <ul class="page-switcher d-flex ">
                            <li><a href="index.html">Home</a> <i class="flaticon-arrow-pointing-to-right"></i></li>
                            <li><a href="index.html">Products</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ===============Breadcrumb area end=============== -->
<div class="product-area ml-110 mt-100">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-3 col-xl-4 col-lg-4">
                <div class="product-sidebar">
                    <div class="row">
                        <div class="col-lg-12 mt-2">
                            <div class="sidebar-searchbar-wrap">
                                <form action="{{ route('timKiem') }}" method="POST" autocomplete="off"
                                    class="sidebar-searchbar-form">
                                    @csrf
                                    <input type="text" name="keywords_submit" id="keywords"
                                        placeholder="Tìm kiếm sản phẩm">
                                    <div id="search_ajax" class="bg-white border rounded"
                                        style="position: absolute; z-index: 1000;"></div>
                                    <button type="submit"><i class="bi bi-search"></i></button>
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-2">
                            <div class="sidebar-category">
                                <h5 class="sb-title">Lọc theo danh mục</h5>
                                <ul class="sb-category-list">
                                    @foreach ($categories as $category)
                                        <li>
                                            <form action="{{ route('locdanhmuc') }}" method="POST"
                                                class="category-form">
                                                @csrf
                                                <input type="hidden" name="category_slug"
                                                    value="{{ $category->slug }}">
                                                <button type="submit" class="category-button">
                                                    {{ $category->name }}
                                                </button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>


                        <div class="col-lg-12 mb-4">
                            <div class="sb-pricing-range">
                                {{-- <h5 class="sb-title">PRICE</h5> --}}
                                <form method="POST" action="{{ route('loc') }}">
                                    @csrf
                                    <h5 class="sb-title" style="padding-bottom: 20px">Lọc theo khoảng giá</h5>
                                    <div class="form-group" style="margin-top: -10px">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="price_range"
                                                value="1"
                                                {{ isset($priceRange) && $priceRange == 1 ? 'checked' : '' }}
                                                id="price1">
                                            <label class="form-check-label ms-3" for="price1"> Dưới 100.000đ</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="price_range"
                                                value="2"
                                                {{ isset($priceRange) && $priceRange == 2 ? 'checked' : '' }}
                                                id="price2">
                                            <label class="form-check-label ms-3" for="price2">100.000đ -
                                                300.000đ</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="price_range"
                                                value="3"
                                                {{ isset($priceRange) && $priceRange == 3 ? 'checked' : '' }}
                                                id="price3">
                                            <label class="form-check-label ms-3" for="price3">300.000đ -
                                                500.000đ</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="price_range"
                                                value="4"
                                                {{ isset($priceRange) && $priceRange == 4 ? 'checked' : '' }}
                                                id="price4">
                                            <label class="form-check-label ms-3" for="price4">500.000đ -
                                                1.000.000đ</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="price_range"
                                                value="5"
                                                {{ isset($priceRange) && $priceRange == 5 ? 'checked' : '' }}
                                                id="price5">
                                            <label class="form-check-label ms-3" for="price5">Trên
                                                1.000.000đ</label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Lọc sản phẩm</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-3">
                            <div class="top-sell-cards">
                                <h5 class="sb-title" style="padding-bottom: 20px">TOP SALE THIS WEEK</h5>

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
                        <div class="col-lg-12 mt-3">
                            <div class="sb-tags">
                                <h5 class="sb-title" style="padding-bottom: 20px">PRODUCT TAG</h5>

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
                {{-- <div class="product-sorting d-flex justify-content-between align-items-center">
                    <div class="show-text"><span>Showing 1-9 of 18 Result</span></div>
                    <div class="category-sort">
                        <select name="category-sort" id="category-sort">
                            <option selected>Default Soprting</option>
                            <option value="1">Sort by Size</option>
                            <option value="2">Sort by Price</option>
                            <option value="3">Sort by Color</option>
                        </select>
                    </div>
                </div> --}}
