@extends('client.auth.master')

@section('content')
<div class="col-11 col-sm-10 col-xl-8">
    <div class="card border border-translucent auth-card">
        <div class="card-body pe-md-0">
            <div class="row align-items-center gx-0 gy-7">
                <div
                    class="col-auto bg-body-highlight dark__bg-gray-1100 rounded-3 position-relative overflow-hidden auth-title-box">
                    <div class="bg-holder" style="background-image:url({{asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/bg/38.png')}});">
                    </div>
                    <!--/.bg-holder-->
                    <div
                        class="position-relative px-4 px-lg-7 pt-7 pb-7 pb-sm-5 text-center text-md-start pb-lg-7">
                        <h3 class="mb-3 text-body-emphasis fs-7">Phoenix Authentication</h3>
                        <p class="text-body-tertiary">Give yourself some hassle-free development process
                            with the uniqueness of Phoenix!</p>
                        <ul class="list-unstyled mb-0 w-max-content w-md-auto">
                            <li class="d-flex align-items-center"><span
                                    class="uil uil-check-circle text-success me-2"></span><span
                                    class="text-body-tertiary fw-semibold">Fast</span></li>
                            <li class="d-flex align-items-center"><span
                                    class="uil uil-check-circle text-success me-2"></span><span
                                    class="text-body-tertiary fw-semibold">Simple</span></li>
                            <li class="d-flex align-items-center"><span
                                    class="uil uil-check-circle text-success me-2"></span><span
                                    class="text-body-tertiary fw-semibold">Responsive</span></li>
                        </ul>
                    </div>
                    <div class="position-relative z-n1 mb-6 d-none d-md-block text-center mt-md-5"><img
                            class="auth-title-box-img d-dark-none"
                            src="{{asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/spot-illustrations/auth.png')}}" alt="" /><img
                            class="auth-title-box-img d-light-none"
                            src="{{asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/spot-illustrations/auth-dark.png')}}" alt="" /></div>
                </div>
                <div class="col mx-auto">
                    <div class="auth-form-box">
                        <div class="text-center"><a class="d-flex flex-center text-decoration-none mb-4"
                                href="{{asset('theme/prium.github.io/phoenix/v1.22.0/index.html')}}">
                                <div class="d-flex align-items-center fw-bolder fs-3 d-inline-block">
                                    <img src="{{asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/icons/logo.png')}}" alt="phoenix"
                                        width="58" /></div>
                            </a>
                            <h4 class="text-body-highlight">Forgot your password?</h4>
                            <p class="text-body-tertiary mb-5">Enter your email below and we will <br
                                    class="d-md-none" />send you <br class="d-none d-xxl-block" />a
                                reset link</p>
                            <form class="d-flex align-items-center mb-5"><input
                                    class="form-control flex-1" id="email" type="email"
                                    placeholder="Email" /><button class="btn btn-primary ms-2">Send<span
                                        class="fas fa-chevron-right ms-2"></span></button></form><a
                                class="fs-9 fw-bold" href="#!">Still having problems?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
