<div class="row g-4 mb-6">
        <div class="col-12 col-lg-9 col-xxl-10">
            <div class="d-flex flex-between-center mb-3">
                <div class="d-flex"><span class="fas fa-bolt text-warning fs-6"></span>
                    <h3 class="mx-2">Sản phẩm nổi bật</h3><span
                        class="fas fa-bolt text-warning fs-6"></span>
                </div><a class="btn btn-link btn-lg p-0 d-none d-md-block" href="#!">Xem thêm
                    <span class="fas fa-chevron-right fs-9 ms-1"></span></a>
            </div>
            <div class="swiper-theme-container products-slider">
                <div class="swiper swiper theme-slider"
                    data-swiper='{"slidesPerView":1,"spaceBetween":16,"breakpoints":{"450":{"slidesPerView":2,"spaceBetween":16},"768":{"slidesPerView":3,"spaceBetween":20},"1200":{"slidesPerView":4,"spaceBetween":16},"1540":{"slidesPerView":5,"spaceBetween":16}}}'>
                    <div class="swiper-wrapper">
                        @foreach($hotProducts as $product)
                        <div class="swiper-slide">
                            <div class="position-relative text-decoration-none product-card h-100">
                                <div class="d-flex flex-column justify-content-between h-100">
                                    <div>
                                        <div
                                            class="border border-1 border-translucent rounded-3 position-relative mb-3">
                                            <button
                                                class="btn btn-wish btn-wish-primary z-2 d-toggle-container"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Add to wishlist"><span
                                                    class="fas fa-heart d-block-hover"
                                                    data-fa-transform="down-1"></span><span
                                                    class="far fa-heart d-none-hover"
                                                    data-fa-transform="down-1"></span></button>
                                            <img class="img-fluid"
                                                src="{{ asset($product->image ? $product->image : 'theme/prium.github.io/phoenix/v1.22.0/assets/img/products/6.png') }}"
                                                alt="{{ $product->name }}" />
                                            <!-- Hiển thị badge HOT -->
                                            <span class="badge text-bg-danger fs-10 product-verified-badge">HOT<span class="fas fa-fire ms-1"></span></span>
                                        </div>
                                        <a class="stretched-link" href="{{ route('product.detail', $product->slug) }}">
                                            <h6 class="mb-2 lh-sm line-clamp-3 product-name">
                                                {{ $product->name }}
                                            </h6>
                                        </a>
                                        <p class="fs-9"><span
                                                class="fa fa-star text-warning"></span><span
                                                class="fa fa-star text-warning"></span><span
                                                class="fa fa-star text-warning"></span><span
                                                class="fa fa-star text-warning"></span><span
                                                class="fa fa-star text-warning"></span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="fs-9 text-body-highlight fw-bold mb-2">{{ $product->category->name ?? 'Danh mục' }}</p>
                                        @if($product->min_price == $product->max_price)
                                            <h5 class="text-danger mb-0 fw-bold">{{ number_format($product->min_price, 0, ',', '.') }} VNĐ</h5>
                                        @else
                                            <h5 class="text-danger mb-0 fw-bold">{{ number_format($product->min_price, 0, ',', '.') }} - {{ number_format($product->max_price, 0, ',', '.') }} VNĐ</h5>
                                        @endif
                                        
                                        @if($product->variations_count > 0)
                                        <p class="text-body-tertiary fw-semibold fs-9 lh-1 mb-0">{{ $product->variations_count }} loại sản phẩm</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="swiper-nav swiper-product-nav">
                    <div class="swiper-button-next"><span
                            class="fas fa-chevron-right nav-icon"></span></div>
                    <div class="swiper-button-prev"><span class="fas fa-chevron-left nav-icon"></span>
                    </div>
                </div>
            </div>
            <a class="fw-bold d-md-none px-0" href="#!">Xem thêm<span
                    class="fas fa-chevron-right fs-9 ms-1"></span></a>
        </div>
        <div class="col-lg-3 d-none d-lg-block col-xxl-2">
            <div class="h-100 position-relative rounded-3 overflow-hidden">
                <div class="bg-holder"
                    style="background-image:url({{ asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/e-commerce/4.png') }});"></div>
                <!--/.bg-holder-->
            </div>
        </div>
        <div class="col-12 d-lg-none"><a href="#!"><img class="w-100 rounded-3"
                    src="../../../assets/img/e-commerce/6.png" alt="" /></a></div>
    </div>