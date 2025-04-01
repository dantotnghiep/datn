
@extends('client.layouts.master')
@section('content')
@include('client.layouts.partials.lelf-navbar')
<style>
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

    /* Toast */
    .custom-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #e74c3c;
        color: white;
        padding: 12px 18px;
        border-radius: 4px;
        opacity: 0;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        z-index: 9999;
    }

    .custom-toast.show {
        opacity: 1;
        transform: translateY(0);
    }

    /* Hiệu ứng sản phẩm hết hàng */
    .out-of-stock {
        opacity: 0.5;
        cursor: not-allowed;
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
                                            $available = $product->variations->firstWhere(fn($v) =>
                                            $v->attributeValues->contains('value', $color->value) && $v->stock > 0
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

                                <li class="d-flex align-items-center">
                                    <span>Size :</span>
                                    <div class="size-option d-flex align-items-center">
                                        @foreach ($sizeValues as $size)
                                            @php
                                            $available = $product->variations->firstWhere(fn($v) =>
                                            $v->attributeValues->contains('value', $size->value) && $v->stock > 0
                                            );
                                            @endphp

                                            <input type="radio" name="size" id="size{{ $size->id }}"
                                                value="{{ $size->value }}" {{ !$available ? 'disabled' : '' }}>
                                            <label for="size{{ $size->id }}">
                                                <span class="p-size">{{ $size->value }}</span>
                                            </label>
                                        @endforeach

                                    </div>
                                </li>

                                <li class="d-flex align-items-center pd-cart-btns">
                                    <div class="quantity">
                                        <input type="number" min="1" max="{{ $product->variations->first()->stock }}"
                                            step="1" value="1">
                                    </div>
                                    <button type="submit" class="pd-add-cart">Add to cart</button>
                                </li>
                                <li class="pd-type">Product Type: <span>{{ $product->category->name }}</span></li>
                                <li class="pd-type">Categories: <span>{{ $product->category->name }}</span></li>
                                <li class="pd-type">Available: <span id="js-stock">{{
                                        $product->variations->first()->stock }}</span></li>
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
   document.addEventListener('DOMContentLoaded', function () {
    const formatCurrency = (value) => {
        return Number(value).toLocaleString('vi-VN') + ' VND';
    };

    const variations = window.variationsData;

    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'custom-toast';
        toast.innerText = message;
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 100);
        setTimeout(() => {
            toast.classList.remove('show');
            toast.addEventListener('transitionend', () => toast.remove());
        }, 2500);
    }

    function updatePrice() {
        const selectedColor = document.querySelector('input[name="color"]:checked')?.value;
        const selectedSize = document.querySelector('input[name="size"]:checked')?.value;

        let matchedVariation = null;

        if (selectedColor && selectedSize) {
            matchedVariation = variations.find(v => {
                const colorMatch = v.attributes.some(attr => attr.attribute_id === 2 && attr.value === selectedColor);
                const sizeMatch = v.attributes.some(attr => attr.attribute_id === 1 && attr.value === selectedSize);
                return colorMatch && sizeMatch;
            });
        } else if (selectedColor) {
            matchedVariation = variations.find(v =>
                v.attributes.some(attr => attr.attribute_id === 2 && attr.value === selectedColor)
            );
        } else if (selectedSize) {
            matchedVariation = variations.find(v =>
                v.attributes.some(attr => attr.attribute_id === 1 && attr.value === selectedSize)
            );
        } else {
            matchedVariation = variations[0];
        }

        const oldPrice = document.getElementById('js-old-price');
        const salePrice = document.getElementById('js-sale-price');
        const regularPrice = document.getElementById('js-regular-price');
        const saleLabel = document.getElementById('js-sale-label');
        const stockElement = document.getElementById('js-stock');
        const qtyInput = document.getElementById('js-qty');

        if (matchedVariation) {
            const { price, sale_price, stock } = matchedVariation;

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

            // 👉 Nếu hết hàng thì thông báo
            if (stock <= 0) {
                showToast('Sản phẩm đã hết hàng!');
            }
        }
    }

    function filterOptions() {
        const selectedColor = document.querySelector('input[name="color"]:checked')?.value;
        const selectedSize = document.querySelector('input[name="size"]:checked')?.value;

        const hasColor = variations.some(v => v.attributes.some(attr => attr.attribute_id === 2));
        const hasSize = variations.some(v => v.attributes.some(attr => attr.attribute_id === 1));

        // Xử lý size
        if (hasSize) {
            document.querySelectorAll('input[name="size"]').forEach(input => {
                const sizeValue = input.value;

                const hasStock = variations.some(v =>
                    v.stock > 0 &&
                    v.attributes.some(attr => attr.attribute_id === 1 && attr.value === sizeValue) &&
                    (
                        !hasColor || !selectedColor ||
                        v.attributes.some(attr => attr.attribute_id === 2 && attr.value === selectedColor)
                    )
                );

                input.classList.toggle('out-of-stock', !hasStock);
                input.nextElementSibling?.classList.toggle('out-of-stock', !hasStock);
            });
        }

        // Xử lý color
        if (hasColor) {
            document.querySelectorAll('input[name="color"]').forEach(input => {
                const colorValue = input.value;

                const hasStock = variations.some(v =>
                    v.stock > 0 &&
                    v.attributes.some(attr => attr.attribute_id === 2 && attr.value === colorValue) &&
                    (
                        !hasSize || !selectedSize ||
                        v.attributes.some(attr => attr.attribute_id === 1 && attr.value === selectedSize)
                    )
                );

                input.classList.toggle('out-of-stock', !hasStock);
                input.nextElementSibling?.classList.toggle('out-of-stock', !hasStock);
            });
        }
    }

    document.querySelectorAll('input[name="color"], input[name="size"]').forEach(input => {
        input.addEventListener('change', () => {
            updatePrice();
            filterOptions();
        });
    });

    // Gọi ngay lần đầu
    updatePrice();
    filterOptions();
});
</script>

@endsection