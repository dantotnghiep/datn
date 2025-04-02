@extends('client.layouts.master')
@section('content')
    @include('client.layouts.partials.lelf-navbar')

    <!-- =============== Cart area start =============== -->
    <div class="cart-area mt-100 ml-110">
        <div class="container">
            <!-- Add this alert section -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <!-- End alert section -->

            <div class="row justify-content-center">
                <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-8">
                    <button type="button" id="select-all-btn" class="btn btn-secondary">Chọn tất cả</button>
                    <button type="button" id="deselect-all-btn" class="btn btn-secondary">Bỏ chọn tất cả</button>
                    @if ($cartItems->count() > 0)
                        <form action="{{ route('cart.checkout') }}" method="GET" id="checkout-form">
                            <table class="table cart-table">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Product Details</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Subtotal</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = 0; @endphp
                                    @foreach ($cartItems as $item)
                                        @php
                                            $variation = $item->variation;
                                            $product = $variation->product;
                                            $mainImage = $product->images()->where('is_main', 1)->first();
                                            $subtotal = $item->price * $item->quantity;
                                            $total += $subtotal;
                                        @endphp
                                        <tr>
                                            <td style="text-align: center; vertical-align: middle;">
                                                <input type="checkbox" name="selected_items[]" value="{{ $item->id }}"
                                                    class="select-item" style="width: 20px; height: 20px;">
                                            </td>
                                            <td class="image-col">
                                                <img src="{{ $mainImage ? $mainImage->url : 'default-image.jpg' }}"
                                                    alt="{{ $item->product_name }}" style="max-width: 100px;">
                                            </td>
                                            <td class="product-col">
                                                <div class="product-details">
                                                    <h5>{{ $item->product_name }}</h5>
                                                    <p>Color: {{ $item->color }}</p>
                                                    <p>Size: {{ $item->size }}</p>
                                                </div>
                                            </td>
                                            <td class="price-col">
                                                @if ($variation->sale_price && now()->between($variation->sale_start, $variation->sale_end))
                                                    <del class="text-muted">{{ number_format($variation->price) }} VND</del>
                                                    <div class="text-danger">{{ number_format($variation->sale_price) }}
                                                        VND
                                                    </div>
                                                @else
                                                    <div>{{ number_format($item->price) }} VND</div>
                                                @endif
                                            </td>
                                            <td class="quantity-col">
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="quantity">
                                                        <input type="number" name="quantity" min="1"
                                                            max="{{ $variation->stock }}" value="{{ $item->quantity }}"
                                                            onchange="this.form.submit()">
                                                    </div>
                                                </form>
                                            </td>
                                            <td class="total-col">{{ number_format($subtotal) }} VND</td>
                                            <td class="delete-col">
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="flaticon-letter-x"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="row mt-60">
                                <div class="col-xxl-4 col-lg-4">
                                    <div class="cart-coupon-input">
                                        <h5 class="coupon-title">Coupon Code</h5>

                                        <!-- Debug information -->
                                        @php
                                            $availableDiscounts = $discounts->filter(function ($discount) {
                                                return now()->between($discount->startDate, $discount->endDate);
                                            });
                                        @endphp

                                        @if ($availableDiscounts->isEmpty())
                                            <div class="alert alert-info">Không có mã giảm giá nào khả dụng</div>
                                        @endif

                                        <form action="{{ route('cart.apply-coupon') }}" method="POST"
                                            class="coupon-input">
                                            @csrf
                                            <select name="discount_code" class="form-select" style="min-width: 200px;">
                                                <option value="">-- Chọn mã giảm giá --</option>
                                                @foreach ($discounts as $discount)
                                                    <option value="{{ $discount->code }}">
                                                        {{ $discount->code }}
                                                        (Giảm {{ number_format($discount->sale) }}%
                                                        @if ($discount->minOrderValue > 0)
                                                            - Đơn tối thiểu {{ number_format($discount->minOrderValue) }}đ
                                                        @endif)
                                                        - HSD: {{ $discount->endDate->format('d/m/Y') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-primary">Áp dụng</button>
                                        </form>

                                        <!-- Thêm debug info -->
                                        <div style="display: none;">
                                            <p>Debug Info:</p>
                                            <p>Current time: {{ now() }}</p>
                                            <p>Available discounts: {{ $discounts->count() }}</p>
                                            @foreach ($discounts as $discount)
                                                <p>
                                                    Code: {{ $discount->code }} <br>
                                                    Start: {{ $discount->startDate }} <br>
                                                    End: {{ $discount->endDate }}
                                                </p>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-8 col-lg-8">
                                    <table class="table total-table">
                                        <tbody>
                                            <tr>
                                                <td class="tt-left">Cart Subtotal</td>
                                                <td></td>
                                                <td class="tt-right">{{ number_format($total) }} VND</td>
                                            </tr>
                                            @if (isset($discountAmount) && $discountAmount > 0)
                                                <tr>
                                                    <td class="tt-left">Discount ({{ session('discount_code') }})</td>
                                                    <td></td>
                                                    <td class="tt-right">-{{ number_format($discountAmount) }} VND</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td class="tt-left">Total</td>
                                                <td></td>
                                                <td class="tt-right"><strong>{{ number_format($finalTotal) }} VND</strong>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="cart-proceed-btns mt-4">
                                <button type="submit" class="cart-proceed">Checkout</button>
                                <a href="{{ route('client.index') }}" class="continue-shop">Continue Shopping</a>
                            </div>
                        </form>
                    @else
                        <div class="text-center">
                            <h3>Your cart is empty</h3>
                            <a href="{{ route('client.index') }}" class="btn btn-primary mt-3">Continue Shopping</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- =============== Cart area end =============== -->

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

    <script>
        document.getElementById('select-all-btn').addEventListener('click', function() {
            document.querySelectorAll('.select-item').forEach(item => item.checked = true);
        });

        document.getElementById('deselect-all-btn').addEventListener('click', function() {
            document.querySelectorAll('.select-item').forEach(item => item.checked = false);
        });

        document.getElementById('select-all').addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.select-item').forEach(item => item.checked = isChecked);
        });
    </script>

    <style>
        .select-item {
            display: block;
            margin: 0 auto;
        }
    </style>
@endsection