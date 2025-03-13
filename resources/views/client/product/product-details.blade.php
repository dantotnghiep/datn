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

    .color-option input[type="radio"]:checked + label .p-color {
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
                            @foreach ($product->images as $image)
                            <div class="product-variation" id="v-pills-home-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-home" role="tab" aria-controls="v-pills-home">
                                <div class="pd-showcase-img">
                                    <img src="{{ asset($image->url) }}" alt="{{ $product->name }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="tab-content" id="v-pills-tabContent">
                        @foreach ($product->images as $image)
                            @if ($image->is_main == 1)
                                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                    <div class="pd-preview-img">
                                        <img src="{{ asset($image->url) }}" alt="{{ $product->name }}">
                                    </div>
                                </div>
                            @endif
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
                            @if ($product->variations->first()->sale_price)
                                <span class="old-price">{{ number_format($product->variations->first()->price) }} VND</span>
                                <span class="sale-price">{{ number_format($product->variations->first()->sale_price) }} VND</span>
                                <span class="sale-label">Sale</span>
                            @else
                                <span>{{ number_format($product->variations->first()->price) }} VND</span>
                            @endif
                        </h5>

                        <p class="pd-small-info" style="font-weight: bold; font-size: 18px;">
                            <strong style="color: #000000;">Category: </strong>
                            <strong style="color: #078e10;">{{ $product->category->name }}</strong>
                        </p>


                    </div>
                    <div class="pd-quick-discription">
                        <ul>
                        @php
                            $colorMap = [
                                'Đen' => '#000000',
                                'Trắng' => '#FFFFFF',
                                'Đỏ' => '#FF0000',
                                'Xanh' => '#0000FF',
                                'Vàng' => '#FFFF00',
                            ];
                        @endphp

                        <style>

                        </style>

                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="variation_id" value="{{ $product->variations->first()->id }}">
                            <input type="hidden" name="product_name" value="{{ $product->name }}">
                            <input type="hidden" name="price" value="{{ $product->variations->first()->sale_price ?? $product->variations->first()->price }}">

                            <li class="d-flex align-items-center">
                                <span>Color :</span>
                                <div class="color-option d-flex align-items-center">
                                    @foreach ($attributeValues->where('attribute_id', 2) as $color)
                                        @php
                                            $bgColor = $colorMap[$color->value] ?? '#ccc';
                                            $isWhite = strtolower($color->value) === 'trắng';
                                            $borderColor = $isWhite ? '#ccc' : 'transparent';
                                        @endphp
                                        <input type="radio" name="color" id="color{{ $color->id }}" value="{{ $color->value }}" {{ $loop->first ? 'checked' : '' }}>
                                        <label for="color{{ $color->id }}">
                                            <span class="c1 p-color" style="background-color: {{ $bgColor }}; border: 1px solid {{ $borderColor }};" title="{{ $color->value }}"></span>
                                        </label>
                                    @endforeach
                                </div>
                            </li>
                            <li class="d-flex align-items-center">
                                <span>Size :</span>
                                <div class="size-option d-flex align-items-center">
                                    @foreach ($attributeValues->where('attribute_id', 1) as $size)
                                        <input type="radio" name="size" id="size{{ $size->id }}" value="{{ $size->value }}" {{ $loop->first ? 'checked' : '' }}>
                                        <label for="size{{ $size->id }}"><span class="p-size">{{ $size->value }}</span></label>
                                    @endforeach
                                </div>
                            </li>
                            <li class="d-flex align-items-center pd-cart-btns">
                                <div class="quantity">
                                    <input type="number" name="quantity" min="1" max="{{ $product->variations->first()->stock }}" step="1" value="1">
                                </div>
                                <button type="submit" class="pd-add-cart">Add to cart</button>
                            </li>
                            <li class="pd-type">Product Type: <span>{{ $product->category->name }}</span></li>
                            <li class="pd-type">Categories: <span>{{ $product->category->name }}</span></li>
                            <li class="pd-type">Available: <span>{{ $product->variations->first()->stock }}</span></li>
                            <li class="pd-type">Material : <span>100% Cotton, Jens</span></li>
                        </ul>
                        </form>
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
                            data-bs-target="#pd-discription-pill3" role="tab" aria-controls="pd-discription-pill3">
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
                                <p>
                                    {!! $product->description !!}
                                </p>
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
                                                        <img src="/client/assets/images/shapes/reviewer1.png" alt="">
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
                                                        <img src="/client/assets/images/shapes/reviewer2.png" alt="">
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
                                                    <textarea name="review-area" id="review-area" cols="30" rows="7"
                                                        placeholder="Your message"></textarea>
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
<!-- ===============  newslatter area end  =============== -->
@endsection