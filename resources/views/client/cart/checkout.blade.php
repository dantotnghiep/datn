@extends('client.layouts.master')
@section('content')
@include('client.layouts.partials.lelf-navbar')

<div class="checkout-area ml-110 mt-100">
    <div class="container">

        <div class="row">
            <div class="col-xxl-8 col-xl-8">
                <form action="" method="POST" id="checkout-form">
                    @csrf
                    <h5 class="checkout-title">Billing Details</h5>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="eg-input-group">
                                <label for="user-name">User Name</label>
                                <input type="text" id="user-name" name="user_name" placeholder="Your full name" value="{{ old('user_name', Auth::user()->name ?? '') }}" required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="eg-input-group">
                                <label>Phone Number</label>
                                <input type="text" name="phone" placeholder="Your Phone Number" value="{{ old('phone', Auth::user()->phone ?? '') }}" required>
                            </div>
                            <div class="eg-input-group">
                                <label>Email Address</label>
                                <input type="email" name="email" placeholder="Your Email Address" value="{{ old('email', Auth::user()->email ?? '') }}" required>
                            </div>
                            <div class="col-lg-12">
                                <div class="eg-input-group">
                                    <label>Street Address</label>
                                    <input type="text" name="street_address" placeholder="House and street name" value="{{ old('street_address') }}" required>
                                </div>
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
                                        <strong>Giá: <span class="product-price">{{ number_format($item->price, 2) }}</span></strong>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="total-cost-summary">
                        <ul>
                            <li class="subtotal">Subtotal <span>{{ number_format($subtotal, 2) }}</span></li>
                            @if ($discountAmount > 0)
                            <li>Discount <span>-{{ number_format($discountAmount, 2) }}</span></li>
                            @endif
                            <li>Total <span>{{ number_format($finalTotal, 2) }}</span></li>
                        </ul>
                    </div>
                </div>
                <form class="payment-form">
                    <div class="payment-methods">
                        <div class="form-check payment-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                            <label class="form-check-label" for="flexRadioDefault1">
                                Check payments
                            </label>
                            <p>Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode.</p>

                          </div>
                          <div class="form-check payment-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                            <label class="form-check-label" for="flexRadioDefault2">
                                Cash on delivery
                            </label>
                            <p>Pay with cash upon delivery.</p>
                          </div>
                          <div class="form-check payment-check paypal">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault3" checked>
                            <label class="form-check-label" for="flexRadioDefault3">
                                PayPal
                            </label>
                            <img src="assets/images/payment/payment-cards.png" alt="">
                            <a href="#" class="about-paypal">What is PayPal</a>
                          </div>
                    </div>


                </form>
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