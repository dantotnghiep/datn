@extends('client.master')
@section('content')
<div class="pt-5 pb-9">
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <section class="py-0">
        <div class="container-small">
            <nav class="mb-3" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="#">{{ $product->category_id }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
            <div class="row g-5 mb-5 mb-lg-8" data-product-details="data-product-details">
                <div class="col-12 col-lg-6">
                    <div class="row g-3 mb-3">
                        <!-- Ảnh sản phẩm phụ (thumbnails) ở bên trái -->
                        <div class="col-2">
                            <div class="product-thumbnails d-flex flex-column gap-2">
                                @foreach ($product->images as $index => $image)
                                    <div class="thumbnail-item mb-2">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                             alt="{{ $product->name }}"
                                             class="img-thumbnail product-thumb-image @if($image->is_primary) active @endif"
                                             data-index="{{ $index }}" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- Ảnh sản phẩm chính ở bên phải -->
                        <div class="col-10">
                            <div class="main-product-image border border-translucent rounded-3 text-center p-3 h-100">
                                @php
                                    $primaryImage = $product->images->where('is_primary', 1)->first() ?? $product->images->first();
                                @endphp
                                <img id="mainProductImage"
                                     src="{{ asset('storage/' . $primaryImage->image_path) }}"
                                     alt="{{ $product->name }}"
                                     class="img-fluid" />
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <button class="btn btn-lg btn-outline-warning rounded-pill w-100 me-3 px-2 px-sm-4 fs-9 fs-sm-8" id="add-to-wishlist">
                            <span class="me-2 far fa-heart" id="wishlist-icon"></span>Thêm vào yêu thích
                        </button>
                        <button class="btn btn-lg btn-warning rounded-pill w-100 fs-9 fs-sm-8">
                            <span class="fas fa-shopping-cart me-2"></span>Thêm vào giỏ hàng
                        </button>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="d-flex flex-column justify-content-between h-100">
                        <div>
                                <h3 class="mb-3 lh-sm">{{ $product->name }}</h3>
                                @if ($product->is_hot)
                                    <div class="d-flex flex-wrap align-items-start mb-3">
                                        <span class="badge text-bg-danger fs-10 product-verified-badge">HOT<span class="fas fa-fire ms-1"></span></span>
                                    </div>
                                @endif
                            <div class="d-flex flex-wrap align-items-center">
                                    @if ($product->variations->count() > 0)
                                        @php
                                            $selectedVariation = $product->variations->first();
                                        @endphp
                                        <div class="price-display">
                                            @if ($selectedVariation->sale_price)
                                                <div class="d-flex align-items-center">
                                                    <h3 class="me-3 mb-0">{{ number_format($selectedVariation->sale_price) }}đ</h3>
                                                    <p class="text-decoration-line-through text-muted mb-0 me-2">
                                                        {{ number_format($selectedVariation->price) }}đ
                                                    </p>
                                                    @php
                                                        $discount = $selectedVariation->price > 0 ? round((($selectedVariation->price - $selectedVariation->sale_price) / $selectedVariation->price) * 100) : 0;
                                                    @endphp
                                                    <span class="badge bg-danger">-{{ $discount }}%</span>
                                                </div>
                                            @else
                                                <h3 class="mb-0">{{ number_format($selectedVariation->price) }}đ</h3>
                                            @endif
                                        </div>
                                    @else
                                        <h1 class="me-3">{{ number_format($product->price) }}đ</h1>
                                    @endif
                            </div>
                                <div class="product-description mb-4">
                                    @if($product->description)
                                        <div class="description-content">
                                            {!! $product->description !!}
                                        </div>
                                    @else
                                        <p class="text-muted">Chưa có mô tả cho sản phẩm này</p>
                                    @endif
                                </div>
                        </div>
                        <div>
                            <div class="mb-3">
                                @if($product->variations->count() > 0)
                                    @foreach($attributes as $attribute)
                                        <div class="variation-{{ $attribute['id'] }} mb-4">
                                            <p class="fw-semibold mb-2 text-body">{{ $attribute['name'] }}:</p>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($attribute['values'] as $value)
                                                    <div class="form-check me-3">
                                                        <input class="form-check-input variation-attribute"
                                                               type="radio"
                                                               name="attribute_{{ $attribute['id'] }}"
                                                               value="{{ $value['id'] }}"
                                                               data-attribute-id="{{ $attribute['id'] }}"
                                                               id="attr-{{ $attribute['id'] }}-{{ $value['id'] }}"
                                                               {{ $loop->first ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="attr-{{ $attribute['id'] }}-{{ $value['id'] }}">
                                                            {{ $value['value'] }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Phần số lượng -->
                                    <div class="variation-quantity mb-4">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="fw-semibold mb-2 text-body">Số lượng:</p>
                                                <div class="d-flex align-items-center">
                                                    <button class="btn btn-outline-secondary btn-sm" id="decrease-quantity">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" class="form-control form-control-sm mx-2 text-center"
                                                           id="quantity" value="1" min="1" style="width: 60px;">
                                                    <button class="btn btn-outline-secondary btn-sm" id="increase-quantity">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="ms-4">
                                                <p class="fw-semibold mb-2 text-body">Tồn kho:</p>
                                                <span class="stock-status">{{ $selectedVariation->stock }} sản phẩm</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted">Sản phẩm không có biến thể</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end of .container-->
    </section><!-- <section> close ============================-->
    <!-- ============================================-->



    <!-- ============================================-->
    <!-- <section> begin ============================-->
    <section class="py-0">
        <div class="container-small">
            <ul class="nav nav-underline fs-9 mb-4" id="productTab" role="tablist">
                <li class="nav-item"><a class="nav-link active" id="description-tab" data-bs-toggle="tab"
                        href="#tab-description" role="tab" aria-controls="tab-description"
                        aria-selected="true">Description</a></li>
            </ul>
            <div class="row gx-3 gy-7">
                <div class="col-12 col-lg-7 col-xl-8">
                    <div class="tab-content" id="productTabContent">
                        <div class="tab-pane pe-lg-6 pe-xl-12 fade show active text-body-emphasis"
                            id="tab-description" role="tabpanel" aria-labelledby="description-tab">
                                {!! $product->description !!}
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end of .container-->
    </section><!-- <section> close ============================-->
    <!-- ============================================-->

</div>

<!-- ============================================-->
<!-- <section> begin ============================-->
<section class="py-0 mb-9">
    <div class="container">
        <div class="d-flex flex-between-center mb-3">
            <div>
                <h3>Similar Products</h3>
            </div>
            <a href="{{ route('product.index', ['category' => $product->category_id]) }}" class="btn btn-sm btn-phoenix-primary">Xem tất cả</a>
        </div>
        <div class="swiper-theme-container products-slider">
            <div class="swiper swiper theme-slider"
                data-swiper='{"slidesPerView":1,"spaceBetween":16,"breakpoints":{"450":{"slidesPerView":2,"spaceBetween":16},"768":{"slidesPerView":3,"spaceBetween":16},"992":{"slidesPerView":4,"spaceBetween":16},"1200":{"slidesPerView":5,"spaceBetween":16},"1540":{"slidesPerView":6,"spaceBetween":16}}}'>
                <div class="swiper-wrapper">
                    @foreach($relatedProducts as $relatedProduct)
                    <div class="swiper-slide">
                        <div class="position-relative text-decoration-none product-card h-30">
                            <div class="d-flex flex-column justify-content-between h-30">
                                <div>
                                    <div class="border border-1 border-translucent rounded-3 position-relative mb-3">
                                        @if($relatedProduct->variations->first())
                                        <button
                                            class="btn btn-wish btn-wish-primary z-2 d-toggle-container wishlist-btn"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-variation-id="{{ $relatedProduct->variations->first()->id }}"
                                            title="{{ in_array($relatedProduct->variations->first()->id, $wishlistItems ?? []) ? 'Đã yêu thích' : 'Thêm vào yêu thích' }}">
                                            @if(in_array($relatedProduct->variations->first()->id, $wishlistItems ?? []))
                                                <span class="fas fa-heart text-danger"
                                                    data-fa-transform="down-1"></span>
                                            @else
                                                <span class="far fa-heart d-none-hover"
                                                    data-fa-transform="down-1"></span>
                                            @endif
                                        </button>
                                        @endif
                                        <a href="{{ route('product.detail', $relatedProduct->slug) }}">
                                            <img class="img-fluid"
                                                src="{{ $relatedProduct->first_image }}" alt="{{ $relatedProduct->name }}" />
                                        </a>
                                        @if($relatedProduct->is_hot)
                                        <span class="badge text-bg-danger fs-10 product-verified-badge">HOT<span class="fas fa-fire ms-1"></span></span>
                                        @endif
                                    </div>
                                    <a class="stretched-link" href="{{ route('product.detail', $relatedProduct->slug) }}">
                                        <h6 class="mb-2 lh-sm line-clamp-3 product-name">
                                            {{ $relatedProduct->name }}
                                        </h6>
                                    </a>
                                </div>
                                <div>
                                    @if($relatedProduct->min_price == $relatedProduct->max_price)
                                        <h6 class="text-danger mb-0 fw-bold">{{ number_format($relatedProduct->min_price, 0, ',', '.') }}</h6>
                                    @else
                                        <h6 class="text-danger mb-0 fw-bold">{{ number_format($relatedProduct->min_price, 0, ',', '.') }} - {{ number_format($relatedProduct->max_price, 0, ',', '.') }}</h6>
                                    @endif

                                    @if($relatedProduct->variations_count > 0)
                                    <p class="text-body-tertiary fw-semibold fs-9 lh-1 mb-0">{{ $relatedProduct->variations_count }} loại sản phẩm</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="swiper-nav">
                <div class="swiper-button-next"><span class="fas fa-chevron-right nav-icon"></span></div>
                <div class="swiper-button-prev"><span class="fas fa-chevron-left nav-icon"></span></div>
            </div>
        </div>
    </div><!-- end of .container-->
</section><!-- <section> close ============================-->
<!-- ============================================-->

<div class="support-chat-container">
    <div class="container-fluid support-chat">
        <div class="card bg-body-emphasis">
            <div class="card-header d-flex flex-between-center px-4 py-3 border-bottom border-translucent">
                <h5 class="mb-0 d-flex align-items-center gap-2">Demo widget<span
                        class="fa-solid fa-circle text-success fs-11"></span></h5>
                <div class="btn-reveal-trigger"><button
                        class="btn btn-link p-0 dropdown-toggle dropdown-caret-none transition-none d-flex"
                            type="button" id="support-chat-dropdown" data-bs-toggle="dropdown" data-boundary="window"
                            aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span
                                class="fas fa-ellipsis-h text-body"></span></button>
                    <div class="dropdown-menu dropdown-menu-end py-2" aria-labelledby="support-chat-dropdown"><a
                            class="dropdown-item" href="#!">Request a callback</a><a class="dropdown-item"
                                href="#!">Search in chat</a><a class="dropdown-item" href="#!">Show
                                history</a><a class="dropdown-item" href="#!">Report to Admin</a><a
                            class="dropdown-item btn-support-chat" href="#!">Close Support</a></div>
                </div>
            </div>
            <div class="card-body chat p-0">
                <div class="d-flex flex-column-reverse scrollbar h-100 p-3">
                    <div class="text-end mt-6"><a
                            class="mb-2 d-inline-flex align-items-center text-decoration-none text-body-emphasis bg-body-hover rounded-pill border border-primary py-2 ps-4 pe-3"
                            href="#!">
                            <p class="mb-0 fw-semibold fs-9">I need help with something</p><span
                                class="fa-solid fa-paper-plane text-primary fs-9 ms-3"></span>
                        </a><a
                            class="mb-2 d-inline-flex align-items-center text-decoration-none text-body-emphasis bg-body-hover rounded-pill border border-primary py-2 ps-4 pe-3"
                            href="#!">
                                <p class="mb-0 fw-semibold fs-9">I can't reorder a product I previously ordered</p>
                            <span class="fa-solid fa-paper-plane text-primary fs-9 ms-3"></span>
                        </a><a
                            class="mb-2 d-inline-flex align-items-center text-decoration-none text-body-emphasis bg-body-hover rounded-pill border border-primary py-2 ps-4 pe-3"
                            href="#!">
                            <p class="mb-0 fw-semibold fs-9">How do I place an order?</p><span
                                class="fa-solid fa-paper-plane text-primary fs-9 ms-3"></span>
                        </a><a
                            class="false d-inline-flex align-items-center text-decoration-none text-body-emphasis bg-body-hover rounded-pill border border-primary py-2 ps-4 pe-3"
                            href="#!">
                            <p class="mb-0 fw-semibold fs-9">My payment method not working</p><span
                                class="fa-solid fa-paper-plane text-primary fs-9 ms-3"></span>
                        </a></div>
                    <div class="text-center mt-auto">
                        <div class="avatar avatar-3xl status-online"><img
                                class="rounded-circle border border-3 border-light-subtle"
                                src="../../../assets/img/team/30.webp" alt="" /></div>
                        <h5 class="mt-2 mb-3">Eric</h5>
                            <p class="text-center text-body-emphasis mb-0">Ask us anything – we'll get back to you
                            here or by email within 24 hours.</p>
                    </div>
                </div>
            </div>
                <div class="card-footer d-flex align-items-center gap-2 border-top border-translucent ps-3 pe-4 py-3">
                <div class="d-flex align-items-center flex-1 gap-3 border border-translucent rounded-pill px-4">
                    <input class="form-control outline-none border-0 flex-1 fs-9 px-0" type="text"
                        placeholder="Write message" /><label
                        class="btn btn-link d-flex p-0 text-body-quaternary fs-9 border-0"
                            for="supportChatPhotos"><span class="fa-solid fa-image"></span></label><input class="d-none"
                            type="file" accept="image/*" id="supportChatPhotos" /><label
                        class="btn btn-link d-flex p-0 text-body-quaternary fs-9 border-0"
                        for="supportChatAttachment"> <span class="fa-solid fa-paperclip"></span></label><input
                            class="d-none" type="file" id="supportChatAttachment" />
                    </div><button class="btn p-0 border-0 send-btn"><span
                        class="fa-solid fa-paper-plane fs-9"></span></button>
            </div>
        </div>
    </div><button class="btn btn-support-chat p-0 border border-translucent"><span
            class="fs-8 btn-text text-primary text-nowrap">Chat demo</span><span
            class="ping-icon-wrapper mt-n4 ms-n6 mt-sm-0 ms-sm-2 position-absolute position-sm-relative"><span
                class="ping-icon-bg"></span><span class="fa-solid fa-circle ping-icon"></span></span><span
            class="fa-solid fa-headset text-primary fs-8 d-sm-none"></span><span
            class="fa-solid fa-chevron-down text-primary fs-7"></span></button>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Xử lý chức năng hiển thị ảnh
            const thumbnails = document.querySelectorAll('.product-thumb-image');
            const mainImage = document.getElementById('mainProductImage');

            // Thêm sự kiện click cho các ảnh thumbnail
            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    // Cập nhật ảnh chính
                    mainImage.src = this.src;

                    // Cập nhật trạng thái active cho thumbnail
                    thumbnails.forEach(item => item.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Khởi tạo biến variations từ PHP
            const variations = @json($product->variations);
            const attributes = @json($attributes);

            // Các elements
            const attributeInputs = document.querySelectorAll('.variation-attribute');
            const quantityInput = document.getElementById('quantity');
            const decreaseBtn = document.getElementById('decrease-quantity');
            const increaseBtn = document.getElementById('increase-quantity');
            const stockStatus = document.querySelector('.stock-status');
            const priceDisplay = document.querySelector('.price-display');

            // Hàm lấy thông tin variation dựa trên các thuộc tính được chọn
            function getVariation(selectedAttributes) {
                return variations.find(variation => {
                    const variationAttributeValues = variation.attribute_values.map(av => av.id);
                    return selectedAttributes.every(attrId => variationAttributeValues.includes(attrId));
                });
            }

            // Hàm kiểm tra tồn tại variation với các thuộc tính và còn hàng
            function hasStock(selectedAttributes) {
                const v = getVariation(selectedAttributes);
                return v && v.stock > 0;
            }

            // Hàm cập nhật trạng thái enable/disable cho các thuộc tính
            function updateAttributeOptions() {
                const selectedAttributes = Array.from(document.querySelectorAll('.variation-attribute:checked'))
                    .map(input => parseInt(input.value));

                attributeInputs.forEach(input => {
                    const attributeId = parseInt(input.dataset.attributeId);
                    const valueId = parseInt(input.value);

                    // Nếu input này đang được chọn, không disable
                    if (input.checked) return;

                    // Kiểm tra xem có variation nào phù hợp không
                    const testAttributes = [...selectedAttributes];
                    const currentAttributeIndex = testAttributes.findIndex(id =>
                        attributes.find(attr => attr.id === attributeId).values.some(v => v.id === id)
                    );

                    if (currentAttributeIndex !== -1) {
                        testAttributes[currentAttributeIndex] = valueId;
                    } else {
                        testAttributes.push(valueId);
                    }

                    input.disabled = !hasStock(testAttributes);
                });
            }

            // Hàm format số
            function numberFormat(number) {
                return new Intl.NumberFormat('vi-VN').format(number);
            }

            // Hàm cập nhật UI dựa trên variation
            function updateUI(variation) {
                if (!variation) return;

                // Cập nhật giá
                if (variation.sale_price) {
                    const discount = variation.price > 0 ? Math.round(((variation.price - variation.sale_price) / variation.price) * 100) : 0;
                    priceDisplay.innerHTML = `
                        <div class="d-flex align-items-center">
                            <h3 class="me-3 mb-0">${numberFormat(variation.sale_price)}đ</h3>
                            <p class="text-decoration-line-through text-muted mb-0 me-2">
                                ${numberFormat(variation.price)}đ
                            </p>
                            <span class="badge bg-danger">-${discount}%</span>
                        </div>
                    `;
                } else {
                    priceDisplay.innerHTML = `<h3 class="mb-0">${numberFormat(variation.price)}đ</h3>`;
                }

                // Cập nhật tồn kho và số lượng
                if (variation.stock > 0) {
                    stockStatus.textContent = `${variation.stock} sản phẩm`;
                    stockStatus.classList.remove('text-danger');
                    stockStatus.classList.add('text-success');

                    const currentQty = parseInt(quantityInput.value) || 1;
                    const newQty = Math.min(currentQty, variation.stock);

                    quantityInput.disabled = false;
                    quantityInput.max = variation.stock;
                    quantityInput.value = newQty;

                    increaseBtn.disabled = newQty >= variation.stock;
                    decreaseBtn.disabled = newQty <= 1;
                } else {
                    stockStatus.textContent = 'Hết hàng';
                    stockStatus.classList.remove('text-success');
                    stockStatus.classList.add('text-danger');

                    quantityInput.disabled = true;
                    quantityInput.value = 0;
                    increaseBtn.disabled = true;
                    decreaseBtn.disabled = true;
                }
            }

            // Hàm cập nhật khi thay đổi thuộc tính
            function updateVariationInfo() {
                const selectedAttributes = Array.from(document.querySelectorAll('.variation-attribute:checked'))
                    .map(input => parseInt(input.value));

                updateAttributeOptions();

                const variation = getVariation(selectedAttributes);
                updateUI(variation);
            }

            // Event Listeners
            attributeInputs.forEach(input => {
                input.addEventListener('change', updateVariationInfo);
            });

            // Xử lý số lượng
            decreaseBtn.addEventListener('click', () => {
                const currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                    updateQuantityButtons();
                }
            });

            increaseBtn.addEventListener('click', () => {
                const currentValue = parseInt(quantityInput.value);
                const max = parseInt(quantityInput.max);
                if (currentValue < max) {
                    quantityInput.value = currentValue + 1;
                    updateQuantityButtons();
                }
            });

            quantityInput.addEventListener('input', () => {
                let value = parseInt(quantityInput.value);
                const max = parseInt(quantityInput.max);

                if (isNaN(value) || value < 1) {
                    value = 1;
                } else if (value > max) {
                    value = max;
                }

                quantityInput.value = value;
                updateQuantityButtons();
            });

            function updateQuantityButtons() {
                const value = parseInt(quantityInput.value);
                const max = parseInt(quantityInput.max);

                decreaseBtn.disabled = value <= 1;
                increaseBtn.disabled = value >= max;
            }

            // Khởi tạo ban đầu
            if (attributeInputs.length) {
                updateVariationInfo();
            }

            // Thêm sự kiện cho nút Add to Cart
            const addToCartBtn = document.querySelector('.btn-warning');
            addToCartBtn.addEventListener('click', function() {
                const selectedAttributes = Array.from(document.querySelectorAll('.variation-attribute:checked'))
                    .map(input => parseInt(input.value));
                const quantity = parseInt(document.getElementById('quantity').value);

                if (selectedAttributes.length === 0) {
                    alert('Vui lòng chọn đầy đủ các thuộc tính');
                    return;
                }

                // Tìm variation tương ứng với các thuộc tính đã chọn
                const variation = getVariation(selectedAttributes);
                if (!variation) {
                    alert('Không tìm thấy biến thể sản phẩm');
                    return;
                }

                // Tạo form và submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("cart.add") }}';
                form.style.display = 'none';

                // Thêm CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Thêm product_id
                const productIdInput = document.createElement('input');
                productIdInput.type = 'hidden';
                productIdInput.name = 'product_id';
                productIdInput.value = '{{ $product->id }}';
                form.appendChild(productIdInput);

                // Thêm variation_id
                const variationIdInput = document.createElement('input');
                variationIdInput.type = 'hidden';
                variationIdInput.name = 'variation_id';
                variationIdInput.value = variation.id;
                form.appendChild(variationIdInput);

                // Thêm quantity
                const quantityInput = document.createElement('input');
                quantityInput.type = 'hidden';
                quantityInput.name = 'quantity';
                quantityInput.value = quantity;
                form.appendChild(quantityInput);

                // Thêm form vào body và submit
                document.body.appendChild(form);
                form.submit();
            });

            // Xử lý chức năng thêm vào yêu thích
            const addToWishlistBtn = document.getElementById('add-to-wishlist');
            const wishlistIcon = document.getElementById('wishlist-icon');

            addToWishlistBtn.addEventListener('click', function() {
                const selectedAttributes = Array.from(document.querySelectorAll('.variation-attribute:checked'))
                    .map(input => parseInt(input.value));

                // Kiểm tra đã đăng nhập chưa
                @if(!Auth::check())
                    // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
                    window.location.href = '{{ route("login") }}';
                    return;
                @endif

                // Kiểm tra đã chọn biến thể chưa
                if (selectedAttributes.length === 0 && variations.length > 0) {
                    alert('Vui lòng chọn đầy đủ các thuộc tính');
                    return;
                }

                // Lấy variation ID
                let variationId;
                if (variations.length > 0) {
                    const variation = getVariation(selectedAttributes);
                    if (!variation) {
                        alert('Không tìm thấy biến thể sản phẩm');
                        return;
                    }
                    variationId = variation.id;
                } else {
                    // Nếu sản phẩm không có biến thể, lấy biến thể đầu tiên (mặc định)
                    variationId = variations[0]?.id;
                }

                // Gọi API để thêm/xóa sản phẩm khỏi wishlist
                fetch('{{ route("wishlist.toggle") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        variation_id: variationId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Cập nhật trạng thái UI
                        if (data.action === 'added') {
                            wishlistIcon.classList.remove('far');
                            wishlistIcon.classList.add('fas', 'text-warning');
                            addToWishlistBtn.innerHTML = `<span class="me-2 fas fa-heart text-warning" id="wishlist-icon"></span>Đã thêm vào yêu thích`;
                        } else {
                            wishlistIcon.classList.remove('fas', 'text-warning');
                            wishlistIcon.classList.add('far');
                            addToWishlistBtn.innerHTML = `<span class="me-2 far fa-heart" id="wishlist-icon"></span>Thêm vào yêu thích`;
                        }
                        // Hiển thị thông báo
                        alert(data.message);
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Đã xảy ra lỗi khi thực hiện thao tác. Vui lòng thử lại sau.');
                });
            });

            // Kiểm tra sản phẩm đã được yêu thích chưa khi tải trang
            @if(Auth::check())
                // Khi trang được tải, kiểm tra xem sản phẩm đã có trong wishlist chưa
                function checkWishlistStatus() {
                    const selectedAttributes = Array.from(document.querySelectorAll('.variation-attribute:checked'))
                        .map(input => parseInt(input.value));

                    let variationId;
                    if (variations.length > 0) {
                        const variation = getVariation(selectedAttributes);
                        if (!variation) return;
                        variationId = variation.id;
                    } else if (variations.length === 1) {
                        variationId = variations[0].id;
                    } else {
                        return;
                    }

                    fetch(`/api/wishlist/check?variation_id=${variationId}`, {
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            wishlistIcon.classList.remove('far');
                            wishlistIcon.classList.add('fas', 'text-warning');
                            addToWishlistBtn.innerHTML = `<span class="me-2 fas fa-heart text-warning" id="wishlist-icon"></span>Đã thêm vào yêu thích`;
                        } else {
                            wishlistIcon.classList.remove('fas', 'text-warning');
                            wishlistIcon.classList.add('far');
                            addToWishlistBtn.innerHTML = `<span class="me-2 far fa-heart" id="wishlist-icon"></span>Thêm vào yêu thích`;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }

                // Gọi function khi trang tải và khi thay đổi biến thể
                checkWishlistStatus();
                attributeInputs.forEach(input => {
                    input.addEventListener('change', checkWishlistStatus);
                });
            @endif

            // Xử lý nút yêu thích trong danh sách sản phẩm liên quan
            const wishlistButtons = document.querySelectorAll('.wishlist-btn');
            wishlistButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Kiểm tra đã đăng nhập chưa
                    @if(!Auth::check())
                        // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
                        window.location.href = '{{ route("login") }}';
                        return;
                    @endif

                    const variationId = this.getAttribute('data-product-variation-id');
                    if (!variationId || variationId === '0') {
                        alert('Không thể thêm sản phẩm này vào danh sách yêu thích');
                        return;
                    }

                    // Gọi API để thêm/xóa sản phẩm khỏi wishlist
                    fetch('{{ route("wishlist.toggle") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            variation_id: variationId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Cập nhật UI: Đổi màu nút yêu thích
                            if (data.action === 'added') {
                                // Đã thêm vào yêu thích
                                this.classList.add('active');
                                alert('Đã thêm sản phẩm vào danh sách yêu thích');
                            } else {
                                // Đã xóa khỏi yêu thích
                                this.classList.remove('active');
                                alert('Đã xóa sản phẩm khỏi danh sách yêu thích');
                            }
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Đã xảy ra lỗi khi thực hiện thao tác. Vui lòng thử lại sau.');
                    });
                });

                // Kiểm tra trạng thái ban đầu
                @if(Auth::check())
                    const variationId = button.getAttribute('data-product-variation-id');
                    if (variationId && variationId !== '0') {
                        fetch(`/api/wishlist/check?variation_id=${variationId}`, {
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.exists) {
                                button.classList.add('active');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                    }
                @endif
            });
        });
    </script>
@endpush

<style>
    .product-thumbnails {
        max-height: 500px;
        overflow-y: auto;
        scrollbar-width: thin;
    }

    .product-thumb-image {
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s ease;
        width: 100%;
        object-fit: cover;
    }

    .product-thumb-image.active {
        border-color: #ffc107;
    }

    .product-thumb-image:hover {
        opacity: 0.85;
    }

    .main-product-image {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    #mainProductImage {
        max-height: 500px;
        object-fit: contain;
    }
</style>
