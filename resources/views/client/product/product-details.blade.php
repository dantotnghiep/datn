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
    </style>
    <div class="product-details-area mt-100 ml-110">
        <div class="container">
            <div class="product-details-wrapper">
                <div class="row">
                    <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-8">
                        <div class="product-switcher-wrap">
                            <div class="nav product-tab" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                @foreach ($product->images as $index => $image)
                                <button class="product-variation nav-link @if ($loop->first || $image->is_main) active @endif" 
                                    id="v-pills-tab-{{ $index }}" 
                                    data-bs-toggle="pill"
                                    data-bs-target="#v-pills-{{ $index }}" 
                                    type="button"
                                    role="tab" 
                                    aria-controls="v-pills-{{ $index }}"
                                    aria-selected="{{ $loop->first || $image->is_main ? 'true' : 'false' }}">
                                    <div class="pd-showcase-img">
                                        <img src="{{ asset($image->url) }}" alt="{{ $product->name }}">
                                    </div>
                                </button>
                            @endforeach
                        </div>
                        <div class="tab-content" id="v-pills-tabContent">
                            @foreach ($product->images as $index => $image)
                            <div class="tab-pane fade @if ($loop->first || $image->is_main) show active @endif" 
                                id="v-pills-{{ $index }}" 
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
                                <ul class="product-rating d-flex align-items-center">
                                    <li><i class="bi bi-star-fill"></i></li>
                                    <li><i class="bi bi-star-fill"></i></li>
                                    <li><i class="bi bi-star-fill"></i></li>
                                    <li><i class="bi bi-star-fill"></i></li>
                                    <li><i class="bi bi-star"></i></li>

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