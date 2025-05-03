@extends('client.auth.master')

@section('content')
    <div class="col-11 col-sm-10 col-xl-8">
        <div class="card border border-translucent auth-card">
            <div class="card-body pe-md-0">
                <div class="row align-items-center gx-0 gy-7">
                    <div
                        class="col-auto bg-body-highlight dark__bg-gray-1100 rounded-3 position-relative overflow-hidden auth-title-box">
                        <div class="bg-holder"
                            style="background-image:url({{ asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/bg/38.png') }});">
                        </div>
                        <!--/.bg-holder-->
                        <div
                            class="position-relative px-4 px-lg-7 pt-7 pb-7 pb-sm-5 text-center text-md-start pb-lg-7 pb-md-7">
                            <h3 class="mb-3 text-body-emphasis fs-7">Phoenix Authentication</h3>
                            <p class="text-body-tertiary">Give yourself some hassle-free development process with the
                                uniqueness of Phoenix!</p>
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
                        <div class="position-relative z-n1 mb-6 d-none d-md-block text-center mt-md-15">
                            <img class="auth-title-box-img d-dark-none" src="{{asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/spot-illustrations/auth.png')}}"
                                alt="" />
                            <img class="auth-title-box-img d-light-none"
                                src="{{asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/spot-illustrations/auth-dark.png')}}" alt="" />
                        </div>
                    </div>
                    <div class="col mx-auto">
                        <div class="auth-form-box">
                            <div class="text-center mb-7">
                                <a class="d-flex flex-center text-decoration-none mb-4" href="{{ route('home') }}">
                                    <div class="d-flex align-items-center fw-bolder fs-3 d-inline-block">
                                        <img src="{{ asset('theme/prium.github.io/phoenix/v1.22.0/assets/img/icons/logo.png') }}"
                                            alt="phoenix" width="58" />
                                    </div>
                                </a>
                                <h3 class="text-body-highlight">Đăng nhập</h3>
                                <p class="text-body-tertiary">Đăng nhập vào tài khoản của bạn</p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="mb-3 text-start">
                                    <label class="form-label" for="email">Email</label>
                                    <div class="form-icon-container">
                                        <input class="form-control form-icon-input @error('email') is-invalid @enderror" 
                                               id="email" type="email" name="email" value="{{ old('email') }}" 
                                               required autocomplete="email" autofocus placeholder="name@example.com" />
                                        <span class="fas fa-user text-body fs-9 form-icon"></span>
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3 text-start">
                                    <label class="form-label" for="password">Mật khẩu</label>
                                    <div class="form-icon-container">
                                        <input class="form-control form-icon-input @error('password') is-invalid @enderror" 
                                               id="password" type="password" name="password" 
                                               required autocomplete="current-password" placeholder="Mật khẩu" />
                                        <span class="fas fa-key text-body fs-9 form-icon"></span>
                            </div>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>

                            <div class="row flex-between-center mb-7">
                                <div class="col-auto">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" id="remember" name="remember" 
                                                   type="checkbox" {{ old('remember') ? 'checked' : '' }} />
                                            <label class="form-check-label mb-0" for="remember">Ghi nhớ tôi</label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <a class="fs-9 fw-bold" href="{{ route('password.request') }}">Quên mật khẩu?</a>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 mb-3">Đăng nhập</button>
                                
                                <div class="text-center">
                                    <a class="fs-9 fw-bold" href="{{ route('register') }}">Tạo tài khoản</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
