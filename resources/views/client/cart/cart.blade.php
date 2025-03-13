@extends('client.layouts.master')
@section('content')
@include('client.layouts.partials.lelf-navbar')

<<<<<<< HEAD
<!-- =============== Cart area start =============== -->
<div class="cart-area mt-100 ml-110">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-8">
                <table class="table cart-table">
                    <thead>
                        <tr>

                            <th scope="col">Image</th>
                            <th scope="col">Product Title</th>
                            <th scope="col">Unite Price</th>
                            <th scope="col">Discount Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Subtotal</th>
                            <th scope="col">Delete</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $key => $value)
                        <tr>

                            <td class="image-col">
                                <img src="" alt="">
                            </td>
                            <td class="product-col"><a href="product-details.html" class="product-title">{{$value['name']}}</a></td>
                            <td class="unite-col"><del><span class="unite-price-del">{{number_format($value['price'])}}</span></del> <span
                                    class="unite-price"></span></td>
                            <td class="discount-col"><span class="discount-price"></span></td>
                            <td class="quantity-col">

                                <div class="quantity">
                                    <input type="number" min="1" max="90" step="10"
                                        value="{{$value['quantity']}}">
                                </div>
                            </td>
                            <td class="total-col">{{number_format($value['price'] * $value['quantity'])}}</td>
                            <td class="delete-col">
                                <div class="delete-icon">
                                    <a href="#"><i class="flaticon-letter-x"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach



                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mt-60">
            <div class="col-xxl-4 col-lg-4">
                <div class="cart-coupon-input">
                    <h5 class="coupon-title">Coupon Code</h5>
                    <form class="coupon-input d-flex align-items-center">
                        <input type="text" placeholder="Coupon Code">
                        <button type="submit">Apply Code</button>
                    </form>
                </div>
            </div>
            <div class="col-xxl-8 col-lg-8">
                <table class="table total-table">
                    <tbody>
                        <tr>
                            <td class="tt-left">Cart Totals</td>
                            <td></td>
                            <td class="tt-right">$128.70</td>
                        </tr>
                        <tr>
                            <td class="tt-left">Shipping</td>
                            <td>
                                <ul class="cart-cost-list">
                                    <li>Shipping Fee</li>
                                    <li>Total ( tax excl.)</li>
                                    <li>Total ( tax incl.)</li>
                                    <li>Taxes</li>
                                    <li>Shipping Enter your address to view shipping options. <a
                                            href="#">Calculate
                                            shipping</a>
                                    </li>
                                </ul>
                            </td>
                            <td class="tt-right cost-info-td">
                                <ul class="cart-cost">
                                    <li>Free</li>
                                    <li>$15</li>
                                    <li>$15</li>
                                    <li>$5</li>
                                    <li></li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="tt-left">Subtotal</td>
                            <td>

                            </td>
                            <td class="tt-right">$162.70</td>
                        </tr>
                    </tbody>
                </table>
                <div class="cart-proceed-btns">
                    <a href="checkout" class="cart-proceed">Proceed to Checkout</a>
                    <a href="product" class="continue-shop">Continue to shopping</a>
=======
    <!-- =============== Cart area start =============== -->
    <div class="cart-area mt-100 ml-110">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-8">
                    @if($cartItems->count() > 0)
                    <table class="table cart-table">
                        <thead>
                            <tr>
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
                            @foreach($cartItems as $item)
                            @php
                                $variation = $item->variation;
                                $product = $variation->product;
                                $mainImage = $product->images()->where('is_main', 1)->first();
                                $subtotal = $item->price * $item->quantity;
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td class="image-col">
                                    <img src="{{ $mainImage ? $mainImage->url : 'default-image.jpg' }}"
                                         alt="{{ $item->product_name }}"
                                         style="max-width: 100px;">
                                </td>
                                <td class="product-col">
                                    <div class="product-details">
                                        <h5>{{ $item->product_name }}</h5>
                                        <p>Color: {{ $item->color }}</p>
                                        <p>Size: {{ $item->size }}</p>
                                    </div>
                                </td>
                                <td class="price-col">
                                    @if($variation->sale_price && now()->between($variation->sale_start, $variation->sale_end))
                                        <del class="text-muted">{{ number_format($variation->price) }} VND</del>
                                        <div class="text-danger">{{ number_format($variation->sale_price) }} VND</div>
                                    @else
                                        <div>{{ number_format($item->price) }} VND</div>
                                    @endif
                                </td>
                                <td class="quantity-col">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <div class="quantity">
                                            <input type="number"
                                                   name="quantity"
                                                   min="1"
                                                   max="{{ $variation->stock }}"
                                                   value="{{ $item->quantity }}"
                                                   onchange="this.form.submit()">
                                        </div>
                                    </form>
                                </td>
                                <td class="total-col">{{ number_format($subtotal) }} VND</td>
                                <td class="delete-col">
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline">
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
                                <form class="coupon-input d-flex align-items-center">
                                    <input type="text" placeholder="Coupon Code">
                                    <button type="submit">Apply Code</button>
                                </form>
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
                                    <tr>
                                        <td class="tt-left">Shipping Fee</td>
                                        <td></td>
                                        <td class="tt-right">Free</td>
                                    </tr>
                                    <tr>
                                        <td class="tt-left">Total</td>
                                        <td></td>
                                        <td class="tt-right"><strong>{{ number_format($total) }} VND</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="cart-proceed-btns">
                                <a href="" class="cart-proceed">Proceed to Checkout</a>
                                <a href="{{ route('client.index') }}" class="continue-shop">Continue Shopping</a>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center">
                        <h3>Your cart is empty</h3>
                        <a href="{{ route('client.index') }}" class="btn btn-primary mt-3">Continue Shopping</a>
                    </div>
                    @endif
>>>>>>> 7a64860aa616456782c8ebf544e8fb5c23ab8eb8
                </div>
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
@endsection