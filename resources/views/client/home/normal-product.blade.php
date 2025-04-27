<div class="mb-6">
        <div class="d-flex flex-between-center mb-3">
            <h3>Sản phẩm thường</h3><a class="fw-bold d-none d-md-block" href="#!">Xem thêm
                <span class="fas fa-chevron-right fs-9 ms-1"></span></a>
        </div>
        <div class="swiper-theme-container products-slider">
            <div class="swiper swiper theme-slider"
                data-swiper='{"slidesPerView":1,"spaceBetween":16,"breakpoints":{"450":{"slidesPerView":2,"spaceBetween":16},"576":{"slidesPerView":3,"spaceBetween":20},"768":{"slidesPerView":4,"spaceBetween":20},"992":{"slidesPerView":5,"spaceBetween":20},"1200":{"slidesPerView":6,"spaceBetween":16}}}'>
                <div class="swiper-wrapper">
                    @foreach($normalProducts as $product)
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
                                            src="{{ asset($product->image ? $product->image : 'theme/prium.github.io/phoenix/v1.22.0/assets/img/products/5.png') }}"
                                            alt="{{ $product->name }}" />
                                    </div>
                                    <a class="stretched-link" href="{{ route('detail') }}">
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
                                    <h3 class="text-body-emphasis">{{ number_format($product->price, 0, ',', '.') }} VNĐ</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="swiper-nav">
                <div class="swiper-button-next"><span class="fas fa-chevron-right nav-icon"></span>
                </div>
                <div class="swiper-button-prev"><span class="fas fa-chevron-left nav-icon"></span>
                </div>
            </div>
        </div>
        <a class="fw-bold d-md-none" href="#!">Xem thêm<span
                class="fas fa-chevron-right fs-9 ms-1"></span></a>
    </div>