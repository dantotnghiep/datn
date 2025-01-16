@extends('client.layouts.master')
@section('content')
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
                            <tr>

                                <td class="image-col">
                                    <img src="/be/assets/images/product/cart-p4.png" alt="">
                                </td>
                                <td class="product-col"><a href="product-details.html" class="product-title">Something
                                        Yellow Party Dress</a></td>
                                <td class="unite-col"><del><span class="unite-price-del">$32.36</span></del> <span
                                        class="unite-price"></span></td>
                                <td class="discount-col"><span class="discount-price">$22.36</span></td>
                                <td class="quantity-col">

                                    <div class="quantity">
                                        <input type="number" min="1" max="90" step="10" value="1">
                                    </div>
                                </td>
                                <td class="total-col">$22.36</td>
                                <td class="delete-col">
                                    <div class="delete-icon">
                                        <a href="#"><i class="flaticon-letter-x"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <tr>

                                <td class="image-col">
                                    <img src="/be/assets/images/product/cart-p6.png" alt="">
                                </td>
                                <td class="product-col"><a href="product-details.html" class="product-title">Woamn
                                        Something Navy Jens</a></td>
                                <td class="unite-col"><del><span class="unite-price-del">$32.36</span></del> <span
                                        class="unite-price"></span></td>
                                <td class="discount-col"><span class="discount-price">$22.36</span></td>
                                <td class="quantity-col">

                                    <div class="quantity">
                                        <input type="number" min="1" max="90" step="10" value="1">
                                    </div>
                                </td>
                                <td class="total-col">$22.36</td>
                                <td class="delete-col">
                                    <div class="delete-icon">
                                        <a href="#"><i class="flaticon-letter-x"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <tr>

                                <td class="image-col">
                                    <img src="/be/assets/images/product/cart-p5.png" alt="">
                                </td>
                                <td class="product-col"><a href="product-details.html" class="product-title">Men Casual
                                        Summer Sale</a></td>
                                <td class="unite-col"><del><span class="unite-price-del"></span></del> <span
                                        class="unite-price">$32.36</span></td>
                                <td class="discount-col"><span class="discount-price">$22.36</span></td>
                                <td class="quantity-col">

                                    <div class="quantity">
                                        <input type="number" min="1" max="90" step="10" value="1">
                                    </div>
                                </td>
                                <td class="total-col">$22.36</td>
                                <td class="delete-col">
                                    <div class="delete-icon">
                                        <a href="#"><i class="flaticon-letter-x"></i></a>
                                    </div>
                                </td>
                            </tr>


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
                        <a href="checkout.html" class="cart-proceed">Proceed to Checkout</a>
                        <a href="product.html" class="continue-shop">Continue to shopping</a>
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

    <!-- ===============  footer area start  =============== -->
    <div class="footer-area ml-110">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3">
                    <div class="footer-widget footer-about">
                        <!-- <div class="footer-logo">
                            <a href="index.html">
                                <img src="/be/assets/images/Logo.png" alt="">
                            </a>
                        </div> -->
                        <h5 class="footer-widget-title">About EG Store</h5>
                        <p class="about-company">EG STORE - We sell over 200+ branded products on our
                            web-site. </p>

                        <div class="footer-contact-actions">
                            <div class="footer-action"><a href="#">168/170, Avenue 01, Mirpur DOHS, Bangladesh</a>
                            </div>
                            <div class="footer-action"><span>Email : </span><a href="#"> <span class="__cf_email__"
                                        data-cfemail="335a5d555c73564b525e435f561d505c5e">[email&#160;protected]</span></a>
                            </div>
                        </div>

                        <ul class="footer-social-links d-flex">
                            <li><a href="#"><i class="flaticon-facebook"></i></a></li>
                            <li><a href="#"><i class="flaticon-twitter"></i></a></li>
                            <li><a href="#"><i class="flaticon-pinterest"></i></a></li>
                            <li><a href="#"><i class="flaticon-instagram-1"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9 ">
                    <div class="row">

                        <!-- company colum -->
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="footer-widget">
                                <h5 class="footer-widget-title">Company</h5>
                                <ul class="footer-links">
                                    <li><a href="#">Privacy Policy</a></li>
                                    <li><a href="#">Returns </a></li>
                                    <li><a href="#">Terms & Conditions</a></li>
                                    <li><a href="#">Our Support</a></li>
                                    <li><a href="#">Terms & Service</a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- quick links colum colum -->
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="footer-widget">
                                <h5 class="footer-widget-title">Quick Links</h5>
                                <ul class="footer-links">
                                    <li><a href="about.html">About EG STORE</a></li>
                                    <li><a href="product.html">New Collection</a></li>
                                    <li><a href="product.html">Woman Dress</a></li>
                                    <li><a href="product.html">Man Dress</a></li>
                                    <li><a href="blog-grid.html">Our Latest News</a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Brands colum -->
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="footer-widget">
                                <h5 class="footer-widget-title">Our Brands</h5>
                                <ul class="footer-links">
                                    <li><a href="#">Louis Vuitton</a></li>
                                    <li><a href="#">Polo Ralph Lauren</a></li>
                                    <li><a href="#">Dresses Tranding CO</a></li>
                                    <li><a href="#">Aeropostale </a></li>
                                    <li><a href="#">Consistent Manner Collective</a></li>
                                    <li><a href="#">Fashionable Houses</a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Store colum -->
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="footer-widget">
                                <h5 class="footer-widget-title">Our Stores</h5>
                                <ul class="footer-links">
                                    <li><a href="#">las Vegas</a></li>
                                    <li><a href="#">Returns New London N</a></li>
                                    <li><a href="#">USA, Wall Street</a></li>
                                    <li><a href="#">Mirpur, Bangladesh </a></li>
                                    <li><a href="#">los Angeles</a></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-5">
                        <div class="footer-copyright">
                            <p>Copyright 2021 EG Shop Fashion | Design By Egens Lab</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-7">
                        <div class="footer-bottom-paymant-option d-flex align-items-center justify-content-end flex-wrap">
                            <p>We Using Safe Payment For:</p>
                            <ul class="payment-options d-flex">
                                <li><img src="/be/assets/images/payment/payment-1.png" alt=""></li>
                                <li><img src="/be/assets/images/payment/payment-2.png" alt=""></li>
                                <li><img src="/be/assets/images/payment/payment-3.png" alt=""></li>
                                <li><img src="/be/assets/images/payment/payment-2.png" alt=""></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ===============  footer area end  =============== -->
@endsection
