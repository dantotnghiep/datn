@extends('client.layouts.master')
@section('content')
@include('client.layouts.partials.lelf-navbar')

<div class="checkout-area ml-110 mt-100">
    <div class="container">

        <div class="row">
            <div class="col-xxl-8 col-xl-8">
                <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                    @csrf
                    <h5 class="checkout-title">Billingggg Details</h5>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="eg-input-group">
                                <label for="first-name1">User Name</label>
                                <input type="text" id="user-name" name="user_name" placeholder="Your full name" value="{{ old('user_name', $user->name ?? '') }}" required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="eg-input-group">
                                <label>Street Address</label>
                                <input type="text" name="street_address" placeholder="House and street name" value="{{ old('street_address') }}" required>
                            </div>
                            <div class="eg-input-group">
                                <input type="text" name="city" placeholder="Town / City" value="{{ old('city') }}" required>
                            </div>
                            <div class="eg-input-group">
                                <input type="text" name="country" placeholder="Country / Region" value="{{ old('country') }}" required>
                            </div>

                        </div>
                        <div class="col-lg-12">
                            <div class="eg-input-group">
                                <label>Phone Number</label>
                                <input type="text" name="phone" placeholder="Your Phone Number" value="{{ old('phone', $user->phone ?? '') }}" required>
                            </div>
                            <div class="eg-input-group">
                                <label>Email Address</label>
                                <input type="email" name="email" placeholder="Your Email Address" value="{{ old('email', $user->email ?? '') }}" required>
                            </div>

                            <div class="eg-input-group mb-0">
                                <textarea name="order_notes" cols="30" rows="7" placeholder="Order Notes (Optional)">{{ old('order_notes') }}</textarea>
                            </div>

                            <div class="place-order-btn">
                                <button type="submit">Place Order</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>



            <div class="col-xxl-4 col-xl-4">
                <div class="order-summary">
                    <div class="added-product-summary">
                        <h5 class="checkout-title">Order Summary</h5>
                        <ul class="added-products">

                            @foreach ($cartItems as $item)
                            @php
                            $product = $item->variation->product ?? null;
                            $mainImage = $product ? $product->images->where('is_main', 1)->first() : null;
                            @endphp
                            <li class="single-product">
                                <div class="product-img">
                                    <img src="{{ $mainImage ? asset($mainImage->url) : asset('default-image.jpg') }}" alt="{{ $item->product_name }}">

                                </div>
                                <div class=" product-info">
                                    <h5 class="product-title"><a href="#">{{ $item->product_name }}</a></h5>
                                    <div class="product-total">
                                        <div class="quantity">
                                            <span>Số lượng: {{ $item->quantity }}</span>
                                        </div>
                                    </div>
                                    <div class="quantity">
                                        <strong>Giá: <span class="product-price">${{ number_format($item->price, 2) }}</span></strong>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="total-cost-summary">
                        <ul>
                            <li class="subtotal">Subtotal <span>${{ number_format($subtotal, 2) }}</span></li>
                            @isset($discountAmount)
                            @if ($discountAmount > 0)
                            <li>Discount ({{ $discountCode ?? 'No code' }}) <span>-${{ number_format($discountAmount, 2) }}</span></li>
                            @endif
                            @endisset
                            <li>Total <span>${{ number_format($finalTotal, 2) }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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