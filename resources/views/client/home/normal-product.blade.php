<div class="mb-6">
    <div class="d-flex flex-between-center mb-3">
        <h3>Sản phẩm thường</h3><a class="fw-bold d-none d-md-block" href="#!">Xem thêm
            <span class="fas fa-chevron-right fs-9 ms-1"></span></a>
    </div>
    <div class="swiper-theme-container products-slider">
        <div class="swiper swiper theme-slider"
            data-swiper='{"slidesPerView":1,"spaceBetween":16,"breakpoints":{"450":{"slidesPerView":2,"spaceBetween":16},"576":{"slidesPerView":3,"spaceBetween":20},"768":{"slidesPerView":4,"spaceBetween":20},"992":{"slidesPerView":5,"spaceBetween":20},"1200":{"slidesPerView":6,"spaceBetween":16}}}'>
            <div class="swiper-wrapper">
                @foreach ($normalProducts as $product)
                    <div class="swiper-slide">
                        <div class="position-relative text-decoration-none product-card h-100">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <div class="border border-1 border-translucent rounded-3 position-relative mb-3">
                                        @if($product->first_variation_id)
                                        <button class="btn btn-wish btn-wish-primary z-2 d-toggle-container wishlist-btn"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-variation-id="{{ $product->first_variation_id }}"
                                            title="{{ in_array($product->first_variation_id, $wishlistItems ?? []) ? 'Đã yêu thích' : 'Thêm vào yêu thích' }}">
                                            @if(in_array($product->first_variation_id, $wishlistItems ?? []))
                                                <span class="fas fa-heart text-danger"
                                                    data-fa-transform="down-1"></span>
                                            @else
                                                <span class="far fa-heart d-none-hover"
                                                    data-fa-transform="down-1"></span>
                                            @endif
                                        </button>
                                        @endif
                                        <img class="img-fluid"
                                            src="{{ $product->first_image }}" alt="{{ $product->name }}" />
                                    </div>
                                    <a class="stretched-link" href="{{ route('product.detail', $product->slug) }}">
                                        <h6 class="mb-2 lh-sm line-clamp-3 product-name">
                                            {{ $product->name }}
                                        </h6>
                                    </a>
                                </div>
                                <div>
                                    @if ($product->min_price == $product->max_price)
                                        <h6 class="text-danger fw-bold mb-0">
                                            {{ number_format($product->min_price, 0, ',', '.') }}</h5>
                                    @else
                                            <h6 class="text-danger fw-bold mb-0">
                                                {{ number_format($product->min_price, 0, ',', '.') }} -
                                                {{ number_format($product->max_price, 0, ',', '.') }}</h5>
                                    @endif

                                    @if ($product->variations_count > 0)
                                        <p class="text-body-tertiary fw-semibold fs-9 lh-1 mb-0">
                                            {{ $product->variations_count }} loại sản phẩm</p>
                                    @endif
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
    <a class="fw-bold d-md-none" href="#!">Xem thêm<span class="fas fa-chevron-right fs-9 ms-1"></span></a>
</div>
