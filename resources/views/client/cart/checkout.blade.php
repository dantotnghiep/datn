@extends('client.layouts.master')
@section('content')
    @include('client.layouts.partials.lelf-navbar')

    <div class="checkout-area ml-110 mt-100">
        <div class="container">
            <div class="row">
                <div class="col-xxl-8 col-xl-8">
                    <form id="payment-form" action="{{ route('order.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="payment_intent_id" id="payment_intent_id">
                        <!-- Hiển thị lỗi validation nếu có -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Hiển thị thông báo success/error -->
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <h5 class="checkout-title">Billing Details</h5>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="eg-input-group">
                                    <label for="user_name">User Name</label>
                                    <input type="text" id="user_name" name="user_name" placeholder="Your full name"
                                        value="{{ old('user_name', Auth::user()->name ?? '') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="eg-input-group">
                                    <label>Phone Number</label>
                                    <input type="text" name="user_phone" placeholder="Your Phone Number"
                                        value="{{ old('user_phone', Auth::user()->phone ?? '') }}" required>
                                </div>
                                <div class="eg-input-group">
                                    <label>Email Address</label>
                                    <input type="email" name="user_email" placeholder="Your Email Address"
                                        value="{{ old('user_email', Auth::user()->email ?? '') }}" required>
                                </div>
                                <div class="col-lg-12">
                                    <div class="eg-input-group">
                                        <label>Shipping Address</label>
                                        <input type="text" name="shipping_address" placeholder="House and street name"
                                            value="{{ old('shipping_address') }}" required>
                                    </div>
                                </div>
                                <div class="payment-methods">
                                    <div class="form-check payment-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="paymentCOD"
                                            value="cod" checked>
                                        <label class="form-check-label" for="paymentCOD">
                                            Cash on Delivery
                                        </label>
                                        <p>Pay with cash upon delivery.</p>
                                    </div>
                                    <div class="form-check payment-check">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                            id="paymentVNPay" value="vnpay">
                                        <label class="form-check-label" for="paymentVNPay">
                                            Thanh toán qua VNPay
                                        </label>
                                        <p>Thanh toán an toàn với VNPay.</p>
                                    </div>
                                </div>
                                <div class="place-order-btn">
                                    <button type="submit" class="place-order-btn">Place Order</button>
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
                                            <img src="{{ $mainImage ? asset($mainImage->url) : asset('default-image.jpg') }}"
                                                alt="{{ $item->product_name }}">
                                        </div>
                                        <div class="product-info">
                                            <h5 class="product-title"><a href="#">{{ $item->product_name }}</a></h5>
                                            <div class="product-total">
                                                <div class="quantity">
                                                    <span>Số lượng: {{ $item->quantity }}</span>
                                                </div>
                                            </div>
                                            <div class="quantity">
                                                <strong>Giá: <span
                                                        class="product-price">{{ number_format($item->price, 2) }}</span></strong>
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

    <style>
        #card-element {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: white;
            margin-top: 10px;
        }

        #card-errors {
            color: #dc3545;
            margin-top: 5px;
            font-size: 14px;
        }

        .place-order-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('payment-form');
            const paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');

            form.addEventListener('submit', function(event) {
                const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked')
                    .value;

                // Nếu là VNPay hoặc COD, submit form trực tiếp
                if (selectedPaymentMethod === 'vnpay' || selectedPaymentMethod === 'cod') {
                    return; // Tiếp tục submit form
                }

                // Nếu không phải VNPay hoặc COD, ngăn submit để xử lý Stripe (nếu có)
                event.preventDefault();
                alert('Phương thức thanh toán này chưa được hỗ trợ!');
            });
        });
    </script>

@endsection
