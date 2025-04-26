
@extends('client.layouts.master')
@section('content')
    @include('client.layouts.partials.lelf-navbar')
    <style>
        label.out-of-stock {
            position: relative;
            opacity: 0.5;
            cursor: not-allowed;
        }

        label.out-of-stock::after {
            content: "❌";
            position: absolute;
            top: 50%;
            right: 8px;
            transform: translateY(-50%);
            font-size: 14px;
            color: red;
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
        .breadcrumb {
            background: none;
            padding: 0;
            margin-bottom: 1rem;
        }
        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            color: #6c757d;
        }
        .disabled-label {
            opacity: 0.5;
            position: relative;
        }

        .disabled-label::after {
            content: '✕';
            color: red;
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 14px;
        }

    </style>


    <div class="product-details-area mt-100 ml-110">
        <div class="container">
            <nav aria-label="breadcrumb" class="my-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('client.index') }}">Trang Chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('client.index') }}">Sản Phẩm</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('client.index') }}">{{ $product->category->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>

            <div class="product-details-wrapper">
                <div class="row">
                    <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-8">
                        <div class="product-switcher-wrap">
                            <div class="nav product-tab" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                @foreach ($product->images as $index => $image)
                                    <div class="product-variation {{ $loop->first ? 'active' : '' }}"
                                         id="v-pills-tab-{{ $index }}"
                                         data-bs-toggle="pill"
                                         data-bs-target="#v-pills-pane-{{ $index }}"
                                         role="tab"
                                         aria-controls="v-pills-pane-{{ $index }}">
                                        <div class="pd-showcase-img">
                                            <img src="{{ asset($image->url) }}" alt="{{ $product->name }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="tab-content" id="v-pills-tabContent">
                                @foreach ($product->images as $index => $image)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                         id="v-pills-pane-{{ $index }}"
                                         role="tabpanel"
                                         aria-labelledby="v-pills-tab-{{ $index }}">
                                        <div class="pd-preview-img">
                                            <img src="{{ asset($image->url) }}" alt="{{ $product->name }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                    <div class="col-xxl-6 col-xl-6 col-lg-6">
                        <div class="product-details-wrap">
                            <div class="pd-top">
                                <h3 class="pd-title">Tên sản phẩm:  {{ $product->name }}</h3>
                                <h5 class="pd-price">
                                    <span id="js-old-price" class="old-price d-none"></span>
                                    <span id="js-sale-price" class="sale-price d-none"></span>
                                    <span id="js-regular-price"></span>
                                    <span id="js-sale-label" class="sale-label d-none">Giảm giá</span>
                                </h5>
                                <p class="pd-small-info">
                                    <strong>Phân Loại: {{ $product->category->name }} -</strong> {!! $product->description !!}
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
                                                       value="{{ $size->value }}" {{ !$available ? 'disabled' : '' }}>
                                                <label for="size{{ $size->id }}" class="{{ !$available ? 'disabled-label' : '' }}">
                                                    <span class="p-size">{{ $size->value }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </li>

                                    <li class="d-flex align-items-center pd-cart-btns">


                                        <form action="{{ route('cart.add') }}" method="POST"
                                              class="d-flex align-items-center">
                                            @csrf
                                            <input type="hidden"  name="variation_id" id="variation_id">
                                            <input type="hidden"  name="product_name" value="{{ $product->name }}">
                                            <input type="hidden"  name="color" id="selected_color">
                                            <input type="hidden"  name="size" id="selected_size">
                                            <input type="hidden"  name="price" id="selected_price">

                                            <div class="quantity-group">
                                                <button type="button" class="qty-btn qty-minus">−</button>
                                                <input type="number" id="product-quantity" name="quantity" min="1" max="10"
                                                       step="1" value="1" >
                                                <button type="button" class="qty-btn qty-plus">+</button>
                                            </div>
                                            <button type="submit" class="pd-add-cart">Thêm vào giỏ hàng</button>
                                        </form>


                                    </li>
                                    <li class="pd-type">Loại sản phẩm:<span>{{ $product->category->name }}</span></li>
                                    <li class="pd-type">Danh mục:<span>{{ $product->category->name }}</span></li>
                                    <li class="pd-type">Sẵn có:<span
                                            id="js-stock">{{ $product->variations->first()->stock }}</span></li>
                                    <input type="hidden" id="js-qty" min="1"
                                           max="{{ $product->variations->first()->stock }}" step="1" value="1">
                                    <li class="pd-type">Chất liệu:<span>100% Cotton, Jens</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @if($relatedProducts->isNotEmpty())
                    <div class="related-products">
                        <h3>Sản phẩm cùng loại</h3>
                        <div class="row">
                            @foreach($relatedProducts as $related)
                                @php
                                    $firstVariation = $related->variations->first();
                                @endphp
                                <div class="col-md-4" style="max-height: 400px; height:100%;">
                                    <div class="product-list" >
                                        <div class="product-card" style="max-width: 300px; border: 1px solid #eee; padding: 10px; text-align: center;">
                                            <img style="max-height: 300px; max-width:250px" src="{{ $related->images->first()->url ?? 'default.jpg' }}" alt="{{ $related->name }}" style="width: 100%; height: auto;">
                                            <h3 class="product-title mb-2">
                                                <a href="{{ route('client.product.product-details', ['id' => $related->id]) }}"
                                                   class="text-dark text-decoration-none link-primary">{{ $related->name }}</a>
                                            </h3>

                                            @if ($firstVariation)
                                                <div>
                                                    @if ($firstVariation->sale_price && now()->between($firstVariation->sale_start, $firstVariation->sale_end))
                                                        <del class="text-muted">{{ number_format($firstVariation->price, 0, ',', '.') }}đ</del>
                                                        <span class="text-danger ms-2">{{ number_format($firstVariation->sale_price, 0, ',', '.') }}đ</span>
                                                    @else
                                                        <span class="text-dark">{{ number_format($firstVariation->price, 0, ',', '.') }}đ</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                @endif
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
        document.addEventListener("DOMContentLoaded", function () {
            const variations = window.variationsData;
            const colorInputs = document.querySelectorAll('input[name="color"]');
            const sizeInputs = document.querySelectorAll('input[name="size"]');
            const qtyInput = document.getElementById('product-quantity');
            const addToCartBtn = document.querySelector('.pd-add-cart');
            const variationIdInput = document.getElementById('variation_id');
            const selectedColor = document.getElementById('selected_color');
            const selectedSize = document.getElementById('selected_size');
            const selectedPrice = document.getElementById('selected_price');

            const oldPrice = document.getElementById('js-old-price');
            const salePrice = document.getElementById('js-sale-price');
            const regularPrice = document.getElementById('js-regular-price');
            const saleLabel = document.getElementById('js-sale-label');
            const stockDisplay = document.getElementById('js-stock');

            const toast = (msg) => {
                toastr.error(msg);
            };

            const hasColor = colorInputs.length > 0;
            const hasSize = sizeInputs.length > 0;

            function findSelectedVariation() {
                const color = document.querySelector('input[name="color"]:checked')?.value;
                const size = document.querySelector('input[name="size"]:checked')?.value;

                return variations.find(variation => {
                    const attr = variation.attributes.map(a => a.value);
                    const hasColorMatch = !hasColor || (color && attr.includes(color));
                    const hasSizeMatch = !hasSize || (size && attr.includes(size));

                    return hasColorMatch && hasSizeMatch;
                });
            }
            function showDefaultPriceSuggestion() {
                if (!hasColor && !hasSize) return;

                let prices = variations.map(v => v.sale_price && v.sale_price < v.price ? v.sale_price : v.price);
                if (prices.length === 0) return;

                let minPrice = Math.min(...prices);
                regularPrice.textContent =  minPrice.toLocaleString() + "vnđ";
            }

            function updateInfo() {
                const selectedVariation = findSelectedVariation();

                const colorSelected = document.querySelector('input[name="color"]:checked');
                const sizeSelected = document.querySelector('input[name="size"]:checked');
                const needColor = hasColor && !colorSelected;
                const needSize = hasSize && !sizeSelected;

                if ((hasColor && hasSize && (needColor || needSize))) {
                    // Chỉ disable nếu có cả màu và size nhưng chưa chọn đủ
                    addToCartBtn.style.pointerEvents = "none";
                    addToCartBtn.style.opacity = "0.5";
                    variationIdInput.value = "";
                    selectedColor.value = "";
                    selectedSize.value = "";
                    selectedPrice.value = "";
                    stockDisplay.textContent = 0;
                    return;
                }

                if (!selectedVariation) {
                    addToCartBtn.style.pointerEvents = "none";
                    addToCartBtn.style.opacity = "0.5";
                    stockDisplay.textContent = 0;
                    toast('Sản phẩm đã hết hàng!');
                    return;
                }

                const { id, stock, price, sale_price } = selectedVariation;

                if (sale_price && sale_price < price) {
                    const discount = Math.round(((price - sale_price) / price) * 100);
                    oldPrice.textContent = price.toLocaleString() + "đ";
                    salePrice.textContent = sale_price.toLocaleString() + "đ";
                    saleLabel.textContent = `Giảm ${discount}%`;
                    oldPrice.classList.remove("d-none");
                    salePrice.classList.remove("d-none");
                    saleLabel.classList.remove("d-none");
                    regularPrice.textContent = "";
                } else {
                    regularPrice.textContent = price.toLocaleString() + "đ";
                    oldPrice.classList.add("d-none");
                    salePrice.classList.add("d-none");
                    saleLabel.classList.add("d-none");
                }

                stockDisplay.textContent = stock;
                qtyInput.max = stock;
                if (parseInt(qtyInput.value) > stock) qtyInput.value = stock;

                if (stock > 0) {
                    addToCartBtn.style.pointerEvents = "";
                    addToCartBtn.style.opacity = "";
                } else {
                    addToCartBtn.style.pointerEvents = "none";
                    addToCartBtn.style.opacity = "0.5";
                    toast('Sản phẩm đã hết hàng!');
                }

                variationIdInput.value = id;
                selectedColor.value = colorSelected?.value || '';
                selectedSize.value = sizeSelected?.value || '';
                selectedPrice.value = sale_price && sale_price < price ? sale_price : price;
            }
            [...colorInputs, ...sizeInputs].forEach(input => {
                input.addEventListener('change', updateInfo);
            });

            // Ngăn người dùng bấm nếu chưa chọn đầy đủ
            addToCartBtn.addEventListener('click', function (e) {
                const colorSelected = document.querySelector('input[name="color"]:checked');
                const sizeSelected = document.querySelector('input[name="size"]:checked');

                const needColor = hasColor && !colorSelected;
                const needSize = hasSize && !sizeSelected;

                if (needColor || needSize || !variationIdInput.value) {
                    e.preventDefault();
                    toast('Chưa chọn biến thể sản phẩm!');
                }
            });
            document.querySelector('.qty-plus').addEventListener('click', () => {
                let current = parseInt(qtyInput.value);
                let max = parseInt(qtyInput.max);
                if (current < max) {
                    qtyInput.value = current + 1;
                } else {
                    toast("Không đủ hàng trong kho!");
                }
            });



            qtyInput.addEventListener('input', () => {
                let max = parseInt(qtyInput.max);
                if (parseInt(qtyInput.value) > max) {
                    qtyInput.value = max;
                    toast("Không đủ hàng trong kho!");
                }
            });
            showDefaultPriceSuggestion();
            updateInfo();
        });

    </script>

@endsection
