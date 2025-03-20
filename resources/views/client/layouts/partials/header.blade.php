<div class="header-area">
    <div class="container-fluid">
        <div class="row">
            <div
                class="col-xl-2 col-lg-12 col-md-12 col-sm-12 col-xs-12 d-xl-flex align-items-center justify-content-center">
                <div class="main-logo d-flex justify-content-between align-items-center">
                    <a href="{{ route('client.index') }}">
                        <img src="/client/assets/images/Logo.png" alt="" />
                    </a>

                    <div class="mobile-menu d-flex">
                        <a href="javascript:void(0)" class="hamburger d-block d-xl-none">
                            <span class="h-top"></span>
                            <span class="h-middle"></span>
                            <span class="h-bottom"></span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6 d-flex justify-content-center">
                <nav class="main-nav">
                    <div class="inner-logo">
                        <a href="{{ route('client.index') }}"><img src="/client/assets/images/logo-w.png"
                                alt="" /></a>
                    </div>
                    <ul class="nav-item-list">

                        <a class="active" href="{{ route('client.index') }}">Trang Chủ</a>
                        <i class="fl flaticon-plus">+</i>


                        </li>



                        <li><a href="about.html">Sản Phẩm Chính</a></li>

                        <li class="has-child-menu">
                            <a href="javascript:void(0)">Cửa hàng</a>
                            <i class="fl flaticon-plus">+</i>
                            <ul class="sub-menu">
                                <li><a href="{{ route('client.product.list-product') }}">Sản phẩm</a></li>
                                <li>
                                    {{-- <a href="{{ route('client.product.product-details') }}">Product Details</a> --}}
                                </li>
                            </ul>
                        </li>
                        <li class="has-child-menu">
                            <a href="javascript:void(0)">Trang</a>
                            <i class="fl flaticon-plus">+</i>
                            <ul class="sub-menu">

                                <li><a href="cart.html">Giỏ hàng</a></li>
                                <li><a href="checkout.html">Thanh toán</a></li>
                                <li><a href="login.html">Đăng nhập</a></li>
                                <li><a href="register.html">Đăng ký</a></li>
                                <li><a href="profile.html">Trang cá nhân</a></li>
                                <li><a href="dashboard.html">Bảng điều khiển</a></li>
                                <li><a href="order.html">Đơn hàng</a></li>
                                <li><a href="setting.html">Cài đặt</a></li>
                                <li><a href="coming-soon.html">Sắp ra mắt</a></li>
                                <li><a href="faq.html">FAQ</a></li>
                                <li><a href="404.html">404</a></li>

                            </ul>
                        </li>

                        <li class="has-child-menu">
                            <a href="javascript:void(0)">Blogs</a>
                            <i class="fl flaticon-plus">+</i>
                            <ul class="sub-menu">
                                <li><a href="blog-grid">Blog Grid</a></li>
                                <li><a href="blog-sidebar">Blog Sidebar</a></li>
                                <li><a href="blog-details">Blog Details</a></li>
                            </ul>
                        </li>
                        {{-- <li><a href="contact.html">Contact Us</a></li> --}}
                        {{-- <li class="has-child-menu">
                            <a href="javascript:void(0)">Tài khoản</a>
                            <i class="fl flaticon-plus">+</i>
                            <ul class="sub-menu">
                                <li><a href="blog-grid.html">Nguyễn Minh Đỗ</a></li>
                                <li><a href="blog-sidebar.html">Thông tin cá nhân</a></li>
                                <li><a href="blog-details.html">Lịch sử mua hàng</a></li>
                                <li><a href="blog-details.html">Đăng xuất</a></li>
                            </ul>
                        </li> --}}
                    </ul>
                    <div class="inner-top">
                        <div class="inner-mail">
                            <i class="flaticon-envelope"></i>
                            <a href="mail.html"><span class="__cf_email__"
                                    data-cfemail="abc2c5cdc4d8dedbdbc4d9dfebced3cac6dbc7ce85c8c4c6">[email&#160;protected]</span></a>
                        </div>
                        <div class="inner-tel">
                            <i class="flaticon-support"></i>
                            <a href="tel:+008-12124354">+008-12124354</a>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="col-xl-2 col-2 d-none d-xl-flex p-0 justify-content-end">
                <div class="nav-contact-no">
                    <div class="contact-icon">
                        <i class="flaticon-phone-call"></i>
                    </div>
                    <div class="contact-info">
                        <a href="tel:+8801761111456">+880 176 1111 456</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-2 d-none d-xl-flex p-0 align-items-center justify-content-center">
                <div class="nav-right text-center">
                    
                    <h5>Giảm giá sản phẩm tới 20%</h5>
                </div>
            </div>
        </div>
    </div>
</div>