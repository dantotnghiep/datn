@extends('client.layouts.master')

@section('content')

<!-- ===============Breadcrumb area start=============== -->
<div class="breadcrumb-area ml-110">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-bg d-flex justify-content-center align-items-center">
                    <div class="breadcrumb-shape1 position-absolute top-0 end-0">
                        <img src="assets/images/shapes/bs-right.png" alt="">
                    </div>
                    <div class="breadcrumb-shape2 position-absolute bottom-0 start-0">
                        <img src="assets/images/shapes/bs-left.png" alt="">
                    </div>
                    <div class="breadcrumb-content text-center">
                        <h2 class="page-title">Liên hệ chúng tôi</h2>
                        <ul class="page-switcher d-flex ">
                            <li><a href="{{ route('client.index') }}">Trang chủ</a> <i class="flaticon-arrow-pointing-to-right"></i></li>
                            <li>Liên hệ chúng tôi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ===============Breadcrumb area end=============== -->


<div class="contact-wrapper mt-76 ml-110">
    <div class="container-fluid">
        <div class="row justify-content-center mb-100">
            <div class="col-xxl-9">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="contact-box">
                            <div class="contect-icon">
                                <i class="flaticon-placeholder"></i>
                            </div>
                            <div class="contact-box-content">
                                <h5>Địa chỉ</h5>
                                <div class="contact-link-list">
                                    <a href="#" class="contact-link">13 P. Trịnh Văn Bô, Xuân Phương, Nam Từ Liêm, Hà Nội</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="contact-box">
                            <div class="contect-icon">
                                <i class="flaticon-phone-call"></i>
                            </div>
                            <div class="contact-box-content">
                                <h5>Số điện thoại</h5>
                                <div class="contact-link-list">
                                    <a href="#" class="contact-link">098 172 58 36</a>
                                    <a href="#" class="contact-link">091 532 9213</a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 ">
                        <div class="contact-box">
                            <div class="contect-icon">
                                <i class="flaticon-envelope"></i>
                            </div>
                            <div class="contact-box-content">
                                <h5>Email</h5>
                                <div class="contact-link-list">
                                    <a href="#" class="contact-link"><span class="__cf_email__" data-cfemail="1e777078715e7b667f736e727b307d7173">caodang@fpt.edu.vn</span></a>
                                    <a href="#" class="contact-link"><span class="__cf_email__" data-cfemail="1e777078715e7b667f736e727b307d7173">caodang@fpt.edu.vn</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="contact-box">
                            <div class="contect-icon">
                                <i class="flaticon-user-1"></i>
                            </div>
                            <div class="contact-box-content">
                                <h5>Social Link</h5>
                                <div class="contact-link-list">
                                    <a href="https://www.facebook.com/caodangfptpolytechnic/?locale=vi_VN" class="contact-link">Facebook</a>
                                    <a href="https://caodang.fpt.edu.vn/tuyen-sinh-cao-dang-xet-tuyen?utm_source=Facebook&utm_medium=Tsinh2024&utm_campaign=AnhVTL&utm_term=Facebooks&utm_content=Tuyensinh_2024" class="contact-link">Website</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

   
        <div class="instagram-img-wrap mt-100">
            <div class="row ">

                <!-- <div class="instagram-img-card">
                                        <img src="assets/images/instagram/insta1.png" alt="">
                                    </div> -->
                <div class="col-xxl-3 col-md-3">
                    <div class="instagram-img-card">
                        <div class="instagram-img">
                            <img src="/client/assets/images/instagram/insta1.png" alt="">
                        </div>

                    </div>
                </div>
                <div class="col-xxl-3 col-md-3">
                    <div class="instagram-img-card">
                        <div class="instagram-img"><img src="/client/assets/images/instagram/insta2.png" alt=""></div>

                    </div>
                </div>
                <div class="col-xxl-3 col-md-3">
                    <div class="instagram-img-card">
                        <div class="instagram-img"><img src="/client/assets/images/instagram/insta3.png" alt=""></div>

                    </div>
                </div>
                <div class="col-xxl-3 col-md-3">
                    <div class="instagram-img-card">
                        <div class="instagram-img"><img src="/client/assets/images/instagram/insta4.png" alt=""></div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

  <!--Javascript -->
  <script data-cfasync="false" src="../../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/swiper.js"></script>
    <script src="assets/js/jquery-ui.min.js"></script>
    <script src="assets/js/jquery.fancybox.min.js"></script>

    <!-- Custom JavaScript -->
    <script src="assets/js/main.js"></script>
@endsection