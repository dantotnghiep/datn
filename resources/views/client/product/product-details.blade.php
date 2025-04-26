
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

                                    <li class="count-review">(<span>{{ $product->reviews_count ?? 0 }}</span> Review)</li>
                                </ul>
                                <h3 class="pd-title">{{ $product->name }}</h3>
                                <h5 class="pd-price">
                                    <span id="js-old-price" class="old-price d-none"></span>
                                    <span id="js-sale-price" class="sale-price d-none"></span>
                                    <span id="js-regular-price"></span>
                                    <span id="js-sale-label" class="sale-label d-none">Sale</span>
                                </h5>


                                {{-- <p class="pd-small-info">
                                    <strong>{{ $product->category->name }} -</strong> {!! $product->description !!}
                                </p> --}}
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
                                                    $bgColor = $colorMap[mb_strtolower($color->value)] ?? '#ccc';
                                                    $isWhite = mb_strtolower($color->value) === 'trắng';
                                                    $borderColor = $isWhite ? '#ccc' : 'transparent';
                                                @endphp

                                                <input type="radio" name="color" id="color{{ $color->id }}"
                                                    value="{{ $color->value }}" {{ $loop->first ? 'checked' : '' }}>
                                                <label for="color{{ $color->id }}">
                                                    <span class="c1 p-color"
                                                        style="background-color: {{ $bgColor }}; border: 1px solid {{ $borderColor }};"
                                                        title="{{ $color->value }}">
                                                    </span>
                                                </label>
                                            @endforeach

                                        </div>
                                    </li>

                                    <li class="d-flex align-items-center">
                                        <span>Size :</span>
                                        <div class="size-option d-flex align-items-center">
                                            @foreach ($sizeValues as $size)
                                                <input type="radio" name="size" id="size{{ $size->id }}"
                                                    value="{{ $size->value }}" {{ $loop->first ? 'checked' : '' }}>
                                                <label for="size{{ $size->id }}"><span
                                                        class="p-size">{{ $size->value }}</span></label>
                                            @endforeach
                                        </div>
                                    </li>

                                    <li class="d-flex align-items-center pd-cart-btns">
                                        <form action="{{ route('cart.add') }}" method="POST"
                                            class="d-flex align-items-center">
                                            @csrf
                                            <input type="hidden" name="variation_id" id="variation_id">
                                            <input type="hidden" name="product_name" value="{{ $product->name }}">
                                            <input type="hidden" name="color" id="selected_color">
                                            <input type="hidden" name="size" id="selected_size">
                                            <input type="hidden" name="price" id="selected_price">

                                            <div class="quantity">
                                                <input type="number" name="quantity" min="1"
                                                    max="{{ $product->variations->first()->stock }}" step="1"
                                                    value="1">
                                            </div>
                                            <button type="submit" class="pd-add-cart">Add to cart</button>
                                        </form>
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

            <div class="product-discription-wrapper mt-100">
                <div class="row">
                    <div class="col-xxl-3 col-xl-3">
                        <div class="nav flex-column nav-pills discription-bar" id="v-pills-tab2" role="tablist"
                            aria-orientation="vertical">
                            <button class="nav-link active" id="pd-discription3" data-bs-toggle="pill"
                                data-bs-target="#pd-discription-pill3" role="tab"
                                aria-controls="pd-discription-pill3">
                                Discription
                            </button>
                            <button class="nav-link" id="pd-discription2" data-bs-toggle="pill"
                                data-bs-target="#pd-discription-pill2" role="tab"
                                aria-controls="pd-discription-pill2">Additional
                                Information</button>
                            <button class="nav-link" id="pd-discription1" data-bs-toggle="pill"
                                data-bs-target="#pd-discription-pill1" role="tab"
                                aria-controls="pd-discription-pill1">Our Review (2)</button>
                        </div>
                    </div>
                    <div class="col-xxl-9 col-xl-9">
                        <div class="tab-content discribtion-tab-content" id="v-pills-tabContent2">
                            <div class="tab-pane fade show active" id="pd-discription-pill3" role="tabpanel"
                                aria-labelledby="pd-discription3">
                                <div class="discription-texts">
                                    <p>Aenean dolor massa, rhoncus ut tortor in, pretium tempus neque. Vestibulum
                                        venenatis leo et dictum finibus. Nulla vulputate dolor sit amet tristique
                                        dapibus. Maecenas posuere luctus leo, non consequat felis ullamcorper non.
                                        Aliquam erat volutpat. Donec vitae porta enim. Cras eu volutpat dolor, vitae
                                        accumsan tellus. Donec pulvinar auctor nunc, et gravida elit porta non. Aliquam
                                        erat volutpat. Proin facilisis interdum felis, sit amet pretium purus feugiat
                                        ac. Donec in leo metus. Sed quis dui nec justo ullamcorper molestie. Mauris
                                        consequat lacinia est, eget tincidunt leo ornare sed. Sed sagittis, neque ac
                                        euismod sollicitudin, mauris orci semper sem, a molestie nisi mi sit amet magna.
                                    </p>

                                    <p>Aenean dolor massa, rhoncus ut tortor in, pretium tempus neque. Vestibulum
                                        venenatis leo et dictum finibus. Nulla vulputate dolor sit amet tristique
                                        dapibus. Maecenas posuere luctus leo, non consequat felis ullamcorper non.
                                        Aliquam erat volutpat. Donec vitae porta enim. Cras eu volutpat dolor, vitae
                                        accumsan tellus. Donec pulvinar auctor nunc, et gravida elit porta non. Aliquam
                                        erat volutpat.</p>
                                    <p>Aenean dolor massa, rhoncus ut tortor in, pretium tempus neque. Vestibulum
                                        venenatis leo et dictum finibus. Nulla vulputate dolor sit amet tristique</p>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pd-discription-pill2" role="tabpanel"
                                aria-labelledby="pd-discription2">
                                <div class="additional-discription">
                                    <ul>
                                        <li>
                                            <h5 class="additition-name">Color</h5>
                                            <div class="additition-variant"><span>:</span>Red, Green, Blue, Yellow,
                                                pink, </div>
                                        </li>
                                        <li>
                                            <h5 class="additition-name">Size</h5>
                                            <div class="additition-variant"><span>:</span>S, M, L, Xl, XXL</div>
                                        </li>
                                        <li>
                                            <h5 class="additition-name">Material</h5>
                                            <div class="additition-variant"><span>:</span>100% Cotton, Jens </div>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                            <div class="tab-pane fade " id="pd-discription-pill1" role="tabpanel"
                                aria-labelledby="pd-discription1">
                                <div class="discription-review">
                                    <div class="clients-review-cards">
                                        <div class="row">
                                            <div class="col-lg-6">


                                                <div class="client-review-card">
                                                    <div class="review-card-head">
                                                        <div class="client-img">
                                                            <img src="/client/assets/images/shapes/reviewer1.png"
                                                                alt="">
                                                        </div>
                                                        <div class="client-info">
                                                            <h5 class="client-name">Jenny Wilson <span
                                                                    class="review-date">- 8th Jan 2021</span> </h5>
                                                            <ul class="review-rating d-flex">
                                                                <li><i class="bi bi-star-fill"></i></li>
                                                                <li><i class="bi bi-star-fill"></i></li>
                                                                <li><i class="bi bi-star-fill"></i></li>
                                                                <li><i class="bi bi-star-fill"></i></li>
                                                                <li><i class="bi bi-star"></i></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <p class="review-text">
                                                        Aenean dolor massa, rhoncus ut tortor in, pretium tempus neque.
                                                        Vestibulum venenatis leo et dictum finibus. Nulla vulputate
                                                        dolor sit amet tristique dapibus.
                                                    </p>
                                                    <ul class="review-actions d-flex align-items-center">
                                                        <li><a href="#"><i class="flaticon-like"></i></a></li>
                                                        <li><a href="#"><i class="flaticon-heart"></i></a></li>
                                                        <li><a href="#">Reply</a></li>
                                                    </ul>
                                                </div>


                                            </div>
                                            <div class="col-lg-6">


                                                <div class="client-review-card">
                                                    <div class="review-card-head">
                                                        <div class="client-img">
                                                            <img src="/client/assets/images/shapes/reviewer2.png"
                                                                alt="">
                                                        </div>
                                                        <div class="client-info">
                                                            <h5 class="client-name">Jenny Wilson <span
                                                                    class="review-date">- 8th Jan 2021</span> </h5>
                                                            <ul class="review-rating d-flex">
                                                                <li><i class="bi bi-star-fill"></i></li>
                                                                <li><i class="bi bi-star-fill"></i></li>
                                                                <li><i class="bi bi-star-fill"></i></li>
                                                                <li><i class="bi bi-star-fill"></i></li>
                                                                <li><i class="bi bi-star"></i></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <p class="review-text">
                                                        Aenean dolor massa, rhoncus ut tortor in, pretium tempus neque.
                                                        Vestibulum venenatis leo et dictum finibus. Nulla vulputate
                                                        dolor sit amet tristique dapibus.
                                                    </p>
                                                    <ul class="review-actions d-flex align-items-center">
                                                        <li><a href="#"><i class="flaticon-like"></i></a></li>
                                                        <li><a href="#"><i class="flaticon-heart"></i></a></li>
                                                        <li><a href="#">Reply</a></li>
                                                    </ul>
                                                </div>


                                            </div>
                                        </div>
                                    </div>

                                    <div class="review-form-wrap">
                                        <h5>Write a Review</h5>
                                        <h3>Leave A Comment</h3>
                                        <p>Your email address will not be published. Required fields are marked *</p>




                                        <form action="#" method="POST" class="review-form">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="review-input-group">
                                                        <label for="fname">First Name</label>
                                                        <input type="text" name="fname" id="fname"
                                                            placeholder="Your first name">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="review-input-group">
                                                        <label for="lname">Last Name</label>
                                                        <input type="text" name="lname" id="lname"
                                                            placeholder="Your last name ">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="review-input-group">
                                                        <textarea name="review-area" id="review-area" cols="30" rows="7" placeholder="Your message"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="review-rating">
                                                        <p>Your Rating</p>
                                                        <ul class="d-flex">
                                                            <li><i class="bi bi-star-fill"></i></li>
                                                            <li><i class="bi bi-star-fill"></i></li>
                                                            <li><i class="bi bi-star-fill"></i></li>
                                                            <li><i class="bi bi-star-fill"></i></li>
                                                            <li><i class="bi bi-star-fill"></i></li>

                                                        </ul>
                                                    </div>

                                                    <div class="submit-btn">
                                                        <input type="submit" value="Post Comment">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ===============peoduct details area start=============== -->




    <!-- ===============peoduct details area end=============== -->




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

    <!-- ===============  newslatter area end  =============== -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formatCurrency = (value) => {
                return Number(value).toLocaleString('vi-VN') + ' VND';
            };

            const variations = @json($variations);

            function updatePrice() {
                const selectedColor = document.querySelector('input[name="color"]:checked')?.value;
                const selectedSize = document.querySelector('input[name="size"]:checked')?.value;

                if (!selectedColor || !selectedSize) return;

                const matchedVariation = variations.find(v => {
                    const colorMatch = v.attributes.some(attr => attr.attribute_id === 2 && attr.value ===
                        selectedColor);
                    const sizeMatch = v.attributes.some(attr => attr.attribute_id === 1 && attr.value ===
                        selectedSize);
                    return colorMatch && sizeMatch;
                });

                const oldPrice = document.getElementById('js-old-price');
                const salePrice = document.getElementById('js-sale-price');
                const regularPrice = document.getElementById('js-regular-price');
                const saleLabel = document.getElementById('js-sale-label');
                const stockElement = document.getElementById('js-stock');
                const qtyInput = document.getElementById('js-qty'); // nếu có input số lượng

                if (matchedVariation) {
                    const {
                        price,
                        sale_price,
                        stock
                    } = matchedVariation;

                    // ✅ Cập nhật tồn kho
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

                    // Cập nhật các input hidden
                    document.getElementById('variation_id').value = matchedVariation.id;
                    document.getElementById('selected_color').value = selectedColor;
                    document.getElementById('selected_size').value = selectedSize;
                    document.getElementById('selected_price').value = matchedVariation.sale_price ||
                        matchedVariation.price;
                }
            }

            document.querySelectorAll('input[name="color"], input[name="size"]').forEach(input => {
                input.addEventListener('change', updatePrice);
            });

            updatePrice(); // Lần đầu load
        });
    </script>
@endsection