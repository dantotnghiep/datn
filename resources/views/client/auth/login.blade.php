@extends('client.layouts.master')
@section('content')
    
    <div class="register-wrapper ml-110 mt-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="register-switcher text-center">
                        <a href="{{ route('register') }}" class="resister-btn">Register</a>
                        <a href="{{ route('login') }}" class="login-btn active">Login</a>
                    </div>
                </div>
            </div>
            <div class="row mt-100 justify-content-center">
                <div class="col-xxl-6 col-xl-6 col-lg-8 col-md-10">
                    <div class="reg-login-forms">
                        <h4 class="reg-login-title text-center">
                            Login Your Account
                        </h4>

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
                            <div class="reg-input-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" 
                                       class="@error('email') is-invalid @enderror">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="reg-input-group">
                                <label for="password">Password *</label>
                                <input type="password" id="password" name="password" 
                                       class="@error('password') is-invalid @enderror">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="password-recover-group d-flex justify-content-between">
                                <div class="reg-input-group reg-check-input d-flex align-items-center">
                                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label for="remember">Remember Me</label>
                                </div>
                                <div class="forgot-password-link">
                                    <a href="{{ route('forgot-password') }}">Forgot Password?</a>
                                </div>
                            </div>
                            <div class="reg-input-group reg-submit-input d-flex align-items-center">
                                <button type="submit" class="btn btn-primary w-100">LOG IN</button>
                            </div>
                        </form>

                        <div class="reg-social-login">
                            <h5>Or login WITH</h5>
                            <ul class="social-login-options">
                                <li><a href="#" class="facebook-login"><i class="flaticon-facebook-app-symbol"></i>
                                        Sign up with facebook</a></li>
                                <li><a href="#" class="google-login"><i class="flaticon-pinterest-1"></i> Sign up with
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
