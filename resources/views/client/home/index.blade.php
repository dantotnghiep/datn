@extends('client.master')
@section('content')
<section class="py-0 px-xl-3">
    <div class="container px-xl-0 px-xxl-3">
        <div class="row g-3 mb-9">
            <div class="col-12">
                <div class="whooping-banner w-100 rounded-3 overflow-hidden">
                    <div class="bg-holder z-n1 product-bg"
                        style="background-image:url({{ asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/e-commerce/whooping_banner_product.png') }});background-position: bottom right;">
                    </div>
                    <!--/.bg-holder-->
                    <div class="bg-holder z-n1 shape-bg"
                        style="background-image:url({{ asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/e-commerce/whooping_banner_shape_2.png') }});background-position: bottom left;">
                    </div>
                    <!--/.bg-holder-->
                    <div class="banner-text" data-bs-theme="light">
                        <h2 class="text-warning-light fw-bolder fs-lg-3 fs-xxl-2">Whooping <span
                                class="gradient-text">60% </span>Off</h2>
                        <h3 class="fw-bolder fs-lg-5 fs-xxl-3 text-white">on everyday items</h3>
                    </div><a class="btn btn-lg btn-primary rounded-pill banner-button" href="#!">Shop
                        Now</a>
                </div>
            </div>
        </div>
        
        <!-- Sản phẩm HOT -->
        @include('client.home.hot-product')
        
        <!-- Sản phẩm thường -->
        @include('client.home.normal-product')
    </div>
</section>
@endsection
