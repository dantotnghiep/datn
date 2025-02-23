@extends('client.layouts.master')
@section('content')
    <div class="register-wrapper ml-110 mt-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="register-switcher text-center">
                        <a href="{{ route('register') }}" class="resister-btn active">Register</a>
                        <a href="{{ route('login') }}" class="login-btn">Login</a>
                    </div>
                </div>
            </div>
            <div class="row mt-100 justify-content-center">
                <div class="col-xxl-6 col-xl-6 col-lg-8 col-md-10">
                    <div class="reg-login-forms">
                        <h4 class="reg-login-title text-center">
                            Register Your Account
                        </h4>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="reg-input-group">
                                <label for="fname">Full Name *</label>
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name') }}" placeholder="Fullname " autocomplete="name">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="reg-input-group">
                                <label for="email">Email *</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" autofocus placeholder="Your email" autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="reg-input-group">
                                <label for="password">Password *</label>
                                <div class="position-relative auth-pass-inputgroup">
                                    <input id="password" type="password"
                                        class="form-control pe-5 password-input @error('password') is-invalid @enderror"
                                        name="password" value="{{ old('password') }}" placeholder="Nhập mật khẩu"
                                        autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                    <button
                                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                        type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                </div>
                            </div>

                            <div class="reg-input-group">
                                <label for="sure-pass">Confirm Password *</label>
                                <div class="position-relative auth-pass-inputgroup">
                                    <input id="password-confirm" type="password" class="form-control pe-5 password-input"
                                        name="password_confirmation" placeholder="Nhập lại mật khẩu"
                                        autocomplete="new-password">
                                    <button
                                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                        type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                </div>
                            </div>

                            <div class="reg-input-group reg-check-input d-flex align-items-center">
                                <input type="checkbox" id="form-check" required>
                                <label for="form-check">I agree to the <a href="#">Terms & Policy</a></label>
                            </div>
                            <div class="reg-input-group reg-submit-input d-flex align-items-center">
                                <input type="submit" id="submite-btn" value="CREATE AN ACCOUNT">
                            </div>
                        </form>

                        <div class="reg-social-login">
                            <h5>or Signup WITH</h5>
                            <ul class="social-login-options">
                                <li><a href="#" class="facebook-login"><i class="flaticon-facebook-app-symbol"></i>
                                        Sign
                                        up whit facebook</a></li>
                                <li><a href="#" class="google-login"><i class="flaticon-pinterest-1"></i> Signup whit
                                        google</a></li>
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
@endsection
