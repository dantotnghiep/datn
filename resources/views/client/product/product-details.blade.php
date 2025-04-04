
@extends('client.layouts.master')
@section('content')
    @include('client.layouts.partials.lelf-navbar')
    <style>
        label.not-available {
            position: relative;
            opacity: 0.5;
            /* làm mờ 50% */
            cursor: not-allowed;
            /* con trỏ chuột dạng cấm */
        }

        /* Thêm chữ X ở giữa label */
        label.not-available::after {
            content: "X";
            position: absolute;
            color: red;
            font-weight: bold;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .old-price {
            text-decoration: line-through;
            color: gray;
        }

        .sale-price {
            color: red;
            font-weight: bold;
        }

        .sale-label {
            background-color: yellow;
            padding: 0 5px;
            color: red;
            font-weight: bold;
        }

        .color-option input[type="radio"] {
            display: none;
        }

        .color-option label {
            margin-right: 8px;
            cursor: pointer;
        }

        .color-option .p-color {
            display: inline-block;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .color-option input[type="radio"]:checked+label .p-color {
            box-shadow: 0 0 0 2px #007bff;
        }

        .quantity-group {
            display: inline-flex;
            border: 1px solid #ccc;
            border-radius: 4px;
            overflow: hidden;
            height: 42px;
            width: 100px;
        }

        .qty-btn {
            width: 40px;
            background: #f9f9f9;
            border: none;
            font-size: 20px;
            cursor: pointer;
            outline: none;
            transition: background-color 0.2s;
        }

        .qty-btn:hover {
            background-color: #e0e0e0;
        }

        #product-quantity {
            width: 50px;
            text-align: center;
            border: none;
            font-size: 18px;
            pointer-events: none;
            background-color: #fff;
        }
    </style>
    <div class="product-details-area mt-100 ml-110">
        <div class="container">
            <div class="product-details-wrapper">
                <div class="row">
                    <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-8">
                        <div class="product-switcher-wrap">
                            <div class="nav product-tab" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                @foreach ($product->images as $image)
                                    <div class="product-variation" id="v-pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-home" role="tab" aria-controls="v-pills-home">
                                        <div class="pd-showcase-img">
                                            <img src="{{ asset($image->url) }}" alt="{{ $product->name }}">
                                            {{-- <img src="{{ asset('storage/' . $image->url) }}" alt="{{ $product->name }}">
                                    --}}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="tab-content" id="v-pills-tabContent">
                                @php
                                    $mainImage = $product->images->firstWhere('is_main', 1);
                                @endphp

                                @if ($mainImage)
                                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                                        aria-labelledby="v-pills-home-tab">
                                        <div class="pd-preview-img">
                                            <img src="{{ asset($mainImage->url) }}" alt="{{ $product->name }}">
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                    <div class="col-xxl-6 col-xl-6 col-lg-6">
                        <div class="product-details-wrap">
                            <div class="pd-top">
                                <ul class="product-rating d-flex align-items-center">
                                    <li><i class="bi bi-star-fill"></i></li>
                                    <li><i class="bi bi-star-fill"></i></li>
                                    <li><i class="bi bi-star-fill"></i></li>
                                    <li><i class="bi bi-star-fill"></i></li>
                                    <li><i class="bi bi-star"></i></li>

                                    <li class="count-review">(<span>{{ $product->reviews_count ?? 0 }}</span> Review)</li>
                                </ul>
                                <h3 class="pd-title">{{ $product->name }}</h3>
                                <h5 class="pd-price">
                                    <span id="js-old-price" class="old-price d-none"></span>
                                    <span id="js-sale-price" class="sale-price d-none"></span>
                                    <span id="js-regular-price"></span>
                                    <span id="js-sale-label" class="sale-label d-none">Sale</span>
                                </h5>


                                <p class="pd-small-info">
                                    <strong>{{ $product->category->name }} -</strong> {!! $product->description !!}
                                </p>
                            </div>
                            <div class="pd-quick-discription">
                                <ul>
                                    @php
                                        $colorValues = collect();
                                        $sizeValues = collect();

                                        foreach ($product->variations as $variation) {
                                            foreach ($variation->attributeValues as $attrValue) {
                                                if ($attrValue->attribute_id == 2) {
                                                    $colorValues->push($attrValue);
                                                } elseif ($attrValue->attribute_id == 1) {
                                                    $sizeValues->push($attrValue);
                                                }
                                            }
                                        }
                                        $colorValues = $colorValues->unique('id');
                                        $sizeValues = $sizeValues->unique('id');
                                        $colorMap = [
                                            'đen' => '#000000',
                                            'trắng' => '#ffffff',
                                            'đỏ' => '#ff0000',
                                            'vàng' => '#ffff00',
                                            'xanh' => '#008000', // xanh lá
                                            // hoặc nếu muốn xanh dương thì:
                                            // 'xanh' => '#0000ff',
                                        ];
                                    @endphp

                                    <li class="d-flex align-items-center">
                                        <span>Color :</span>
                                        <div class="color-option d-flex align-items-center">
                                            @foreach ($colorValues as $color)
                                                @php
                                                    $available = $product->variations->firstWhere(
                                                        fn($v) => $v->attributeValues->contains(
                                                            'value',
                                                            $color->value,
                                                        ) && $v->stock > 0,
                                                    );
                                                    $bgColor = $colorMap[mb_strtolower($color->value)] ?? '#ccc';
                                                    $isWhite = mb_strtolower($color->value) === 'trắng';
                                                    $borderColor = $isWhite ? '#ccc' : 'transparent';
                                                @endphp

                                                <input type="radio" name="color" id="color{{ $color->id }}"
                                                    value="{{ $color->value }}" {{ !$available ? 'disabled' : '' }}>
                                                <label for="color{{ $color->id }}">
                                                    <span class="c1 p-color"
                                                        style="background-color: {{ $bgColor }}; border: 1px solid {{ $borderColor }};"
                                                        title="{{ $color->value }}"></span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </li>


                                    <!-- ✅ Giao diện chọn size -->
                                    <li class="d-flex align-items-center">
                                        <span>Size :</span>
                                        <div class="size-option d-flex align-items-center">
                                            @foreach ($sizeValues as $size)
                                                @php
                                                    $available = $product->variations->firstWhere(
                                                        fn($v) => $v->attributeValues->contains(
                                                            'value',
                                                            $size->value,
                                                        ) && $v->stock > 0,
                                                    );
                                                @endphp

                                                <input type="radio" name="size" id="size{{ $size->id }}"
                                                    value="{{ $size->value }}">
                                                <label for="size{{ $size->id }}">
                                                    <span class="p-size">{{ $size->value }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </li>
                                    <li class="mt-2 text-left">
                                        <button id="js-reset-selection"
                                            class="btn btn-outline-secondary btn-sm">Reset</button>
                                    </li>

                                    <li class="d-flex align-items-center pd-cart-btns">
                                        <div class="quantity-group">
                                            <button type="button" class="qty-btn qty-minus">−</button>
                                            <input type="number" id="product-quantity" min="1" max="10"
                                                step="1" value="1" readonly>
                                            <button type="button" class="qty-btn qty-plus">+</button>
                                        </div>



                                        <button type="submit" class="pd-add-cart">Add to cart</button>
                                    </li>
                                    <li class="pd-type">Product Type: <span>{{ $product->category->name }}</span></li>
                                    <li class="pd-type">Categories: <span>{{ $product->category->name }}</span></li>
                                    <li class="pd-type">Available: <span
                                            id="js-stock">{{ $product->variations->first()->stock }}</span></li>
                                    <input type="hidden" id="js-qty" min="1"
                                        max="{{ $product->variations->first()->stock }}" step="1" value="1">
                                    <li class="pd-type">Material : <span>100% Cotton, Jens</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    @php
        $variations = $product->variations
            ->map(function ($v) {
                return [
                    'id' => $v->id,
                    'price' => $v->price,
                    'sale_price' => $v->sale_price,
                    'stock' => $v->stock,
                    'attributes' => $v->attributeValues
                        ->map(function ($av) {
                            return [
                                'attribute_id' => $av->attribute_id,
                                'value' => $av->value,
                            ];
                        })
                        ->values()
                        ->toArray(),
                ];
            })
            ->values()
            ->toArray();
    @endphp

    <script>
        window.variationsData = @json($variations);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('product-quantity');
            const btnPlus = document.querySelector('.qty-plus');
            const btnMinus = document.querySelector('.qty-minus');

            btnPlus.addEventListener('click', function() {
                let current = parseInt(input.value);
                let max = parseInt(input.max) || Infinity;
                if (current < max) {
                    input.value = current + 1;
                    input.dispatchEvent(new Event('change'));
                }
            });

            btnMinus.addEventListener('click', function() {
                let current = parseInt(input.value);
                let min = parseInt(input.min) || 1;
                if (current > min) {
                    input.value = current - 1;
                    input.dispatchEvent(new Event('change'));
                }
            });
            const formatCurrency = (value) => {
                return Number(value).toLocaleString('vi-VN') + ' VND';
            };

            const variations = window.variationsData;

            function getCheckedValue(name) {
                return document.querySelector(`input[name="${name}"]:checked`)?.value;
            }

            function updatePrice() {
                const selectedColor = getCheckedValue('color');
                const selectedSize = getCheckedValue('size');

                let matchedVariation = variations.find(v => {
                    const colorMatch = selectedColor ? v.attributes.some(attr => attr.attribute_id === 2 &&
                        attr.value === selectedColor) : true;
                    const sizeMatch = selectedSize ? v.attributes.some(attr => attr.attribute_id === 1 &&
                        attr.value === selectedSize) : true;
                    return colorMatch && sizeMatch;
                });

                const oldPrice = document.getElementById('js-old-price');
                const salePrice = document.getElementById('js-sale-price');
                const regularPrice = document.getElementById('js-regular-price');
                const saleLabel = document.getElementById('js-sale-label');
                const stockElement = document.getElementById('js-stock');
                const qtyInput = document.getElementById('js-qty');

                if (matchedVariation) {
                    const {
                        price,
                        sale_price,
                        stock
                    } = matchedVariation;
                    if (stockElement) stockElement.textContent = stock;
                    if (qtyInput) qtyInput.max = stock;

                    if (sale_price) {
                        oldPrice.textContent = formatCurrency(price);
                        salePrice.textContent = formatCurrency(sale_price);
                        regularPrice.textContent = '';
                        oldPrice.classList.remove('d-none');
                        salePrice.classList.remove('d-none');
                        saleLabel.classList.remove('d-none');
                    } else {
                        regularPrice.textContent = formatCurrency(price);
                        oldPrice.classList.add('d-none');
                        salePrice.classList.add('d-none');
                        saleLabel.classList.add('d-none');
                    }
                } else {
                    // Không có biến thể phù hợp
                    if (stockElement) stockElement.textContent = '0';
                    if (qtyInput) qtyInput.max = 1;
                    oldPrice.classList.add('d-none');
                    salePrice.classList.add('d-none');
                    saleLabel.classList.add('d-none');
                    regularPrice.textContent = '';
                }
            }

            function filterOptions() {
                const selectedColor = getCheckedValue('color');
                const selectedSize = getCheckedValue('size');

                const hasColor = document.querySelectorAll('input[name="color"]').length > 0;
                const hasSize = document.querySelectorAll('input[name="size"]').length > 0;

                const noSelection = !selectedColor && !selectedSize;

                // ---- Xử lý SIZE ----
                if (hasSize) {
                    document.querySelectorAll('input[name="size"]').forEach(input => {
                        const sizeValue = input.value;
                        const label = input.nextElementSibling;

                        // Gắn sự kiện bỏ chọn nếu chưa có
                        if (!input.dataset.uncheckHandlerAdded) {
                            let wasChecked = false;

                            input.addEventListener('mousedown', () => {
                                wasChecked = input.checked;
                            });

                            input.addEventListener('click', e => {
                                if (wasChecked) {
                                    input.checked = false;
                                    e.preventDefault();
                                    filterOptions();
                                    updatePrice();
                                }
                            });

                            input.dataset.uncheckHandlerAdded = 'true';
                        }

                        if (noSelection) {
                            input.disabled = false;
                            label.classList.remove('not-available');
                            return;
                        }

                        const matched = variations.find(v =>
                            v.stock > 0 &&
                            v.attributes.some(attr => attr.attribute_id === 1 && attr.value ===
                                sizeValue) &&
                            (!selectedColor || v.attributes.some(attr => attr.attribute_id === 2 && attr
                                .value === selectedColor))
                        );

                        if (matched) {
                            input.disabled = false;
                            label.classList.remove('not-available');
                        } else {
                            input.disabled = true;
                            input.checked = false;
                            label.classList.add('not-available');
                        }
                    });
                }

                // ---- Xử lý COLOR ----
                if (hasColor) {
                    document.querySelectorAll('input[name="color"]').forEach(input => {
                        const colorValue = input.value;
                        const label = input.nextElementSibling;

                        // Gắn sự kiện bỏ chọn nếu chưa có
                        if (!input.dataset.uncheckHandlerAdded) {
                            let wasChecked = false;

                            input.addEventListener('mousedown', () => {
                                wasChecked = input.checked;
                            });

                            input.addEventListener('click', e => {
                                if (wasChecked) {
                                    input.checked = false;
                                    e.preventDefault();
                                    filterOptions();
                                    updatePrice();
                                }
                            });

                            input.dataset.uncheckHandlerAdded = 'true';
                        }

                        if (noSelection) {
                            input.disabled = false;
                            label.classList.remove('not-available');
                            return;
                        }

                        const matched = variations.find(v =>
                            v.stock > 0 &&
                            v.attributes.some(attr => attr.attribute_id === 2 && attr.value ===
                                colorValue) &&
                            (!selectedSize || v.attributes.some(attr => attr.attribute_id === 1 && attr
                                .value === selectedSize))
                        );

                        if (matched) {
                            input.disabled = false;
                            label.classList.remove('not-available');
                        } else {
                            input.disabled = true;
                            input.checked = false;
                            label.classList.add('not-available');
                        }
                    });
                }
            }

            function preventOutOfStockClick() {
                document.querySelectorAll('input[name="color"], input[name="size"]').forEach(input => {
                    input.addEventListener('click', (e) => {
                        if (input.disabled) {
                            e.preventDefault();
                            const label = input.nextElementSibling?.innerText || 'Sản phẩm';
                            showToast(`${label} đã hết hàng!`);
                        }
                    });
                });
            }

            // Gắn sự kiện onchange
            document.querySelectorAll('input[name="color"], input[name="size"]').forEach(input => {
                input.addEventListener('change', () => {
                    updatePrice();
                    filterOptions();
                    preventOutOfStockClick();
                });
            });

            // ✅ Nút "Bỏ chọn"
            const resetBtn = document.getElementById('js-reset-selection');
            if (resetBtn) {
                resetBtn.addEventListener('click', () => {
                    document.querySelectorAll('input[name="color"]:checked, input[name="size"]:checked')
                        .forEach(input => {
                            input.checked = false;
                        });

                    const qtyInput = document.getElementById('js-qty');
                    if (qtyInput) qtyInput.value = 1;

                    updatePrice();
                    filterOptions();
                });
            }

            // Gọi lúc ban đầu
            updatePrice();
            filterOptions();
            preventOutOfStockClick();
        });
    </script>
@endsection
