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
                                <label for="first-name1">First Name</label>
                                <input type="text" id="first-name1" name="first_name" placeholder="Your first name" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="eg-input-group">
                                <label for="last-name">Last Name</label>
                                <input type="text" id="last-name" name="last_name" placeholder="Your last name" required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="eg-input-group">
                                <label>Street Address</label>
                                <input type="text" name="street_address" placeholder="House and street name" required>
                            </div>
                            <div class="eg-input-group">
                                <input type="text" name="city" placeholder="Town / City" required>
                            </div>
                            <div class="eg-input-group">
                                <input type="text" name="country" placeholder="Country / Region" required>
                            </div>
                            
                        </div>
                        <div class="col-lg-12">
                            <div class="eg-input-group">
                                <label>Phone Number</label>
                                <input type="text" name="phone" placeholder="Your Phone Number" required>
                            </div>
                            <div class="eg-input-group">
                                <label>Email Address</label>
                                <input type="email" name="email" placeholder="Your Email Address" required>
                            </div>
                            <div class="eg-input-group">
                                <label>Order_Code</label>
                                <input type="text" name="order_code" placeholder="Order_code" required>
                            </div>
                            <div class="eg-input-group mb-0">
                                <textarea name="order_notes" cols="30" rows="7" placeholder="Order Notes (Optional)"></textarea>
                            </div>
                            
                            {{-- <div class="col-lg-12">
                                <button type="submit">Place Order</button>
                            </div> --}}
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
                            <h5 class="checkout-title">
                                Order Summary
                            </h5>
                            <ul class="added-products">
                                <li class="single-product">
                                    <div class="product-img">
                                        <img src="/client/assets/images/product/added-p1.png" alt="">
                                    </div>
                                    <div class="product-info">
                                        <h5 class="product-title"><a href="product.html">Something Yellow Party
                                                Dress</a></h5>
                                        <div class="product-total">
                                            <div class="quantity">
                                                <input type="number" min="1" max="90" step="10"
                                                    value="1">
                                            </div>
                                            <strong> <i class="bi bi-x-lg"></i> <span
                                                    class="product-price">$22.36</span></strong>

                                        </div>
                                    </div>
                                </li>
                                <li class="single-product">
                                    <div class="product-img">
                                        <img src="/client/assets/images/product/added-p2.png" alt="">
                                    </div>
                                    <div class="product-info">
                                        <h5 class="product-title"><a href="product.html">Women Yellow Pattern Shirt</a>
                                        </h5>
                                        <div class="product-total">
                                            <div class="quantity">
                                                <input type="number" min="1" max="90" step="10"
                                                    value="1">
                                            </div>
                                            <strong> <i class="bi bi-x-lg"></i> <span
                                                    class="product-price">$22.36</span></strong>

                                        </div>
                                    </div>
                                </li>
                                <li class="single-product">
                                    <div class="product-img">
                                        <img src="/client/assets/images/product/added-p3.png" alt="">
                                    </div>
                                    <div class="product-info">
                                        <h5 class="product-title"><a href="product.html">Man Mannequin Casual</a></h5>
                                        <div class="product-total">
                                            <div class="quantity">
                                                <input type="number" min="1" max="90" step="10"
                                                    value="1">
                                            </div>
                                            <strong> <i class="bi bi-x-lg"></i> <span
                                                    class="product-price">$22.36</span></strong>

                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>



                        <div class="total-cost-summary">
                            <ul>
                                <li class="subtotal">Subtotal <span>$128.70</span></li>
                                <li>Tax <span>$5</span></li>
                                <li>Total ( tax excl.) <span>$15</span></li>
                                <li>Total ( tax incl.) <span>$15</span></li>
                            </ul>
                        </div>
                        <div class="total-cost">
                            <ul>
                                <li class="d-flex justify-content-between">Subtotal <span>$128.70</span></li>
                            </ul>
                        </div>

                        <form class="payment-form">
                            <div class="payment-methods">
                                <div class="form-check payment-check">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault"
                                        id="flexRadioDefault1">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Check payments
                                    </label>
                                    <p>Please send a check to Store Name, Store Street, Store Town, Store State /
                                        County, Store Postcode.</p>

                                </div>
                                <div class="form-check payment-check">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault"
                                        id="flexRadioDefault2" checked>
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        Cash on delivery
                                    </label>
                                    <p>Pay with cash upon delivery.</p>
                                </div>
                                <div class="form-check payment-check paypal">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault"
                                        id="flexRadioDefault3" checked>
                                    <label class="form-check-label" for="flexRadioDefault3">
                                        PayPal
                                    </label>
                                    <img src="/client/assets/images/payment/payment-cards.png" alt="">
                                    <a href="#" class="about-paypal">What is PayPal</a>
                                </div>
                            </div>

                            
                        </form>
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
