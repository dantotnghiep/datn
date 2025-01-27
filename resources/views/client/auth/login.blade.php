@extends('client.layouts.master')
@section('content')
    
    <div class="register-wrapper ml-110 mt-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="register-switcher text-center">
                        <a href="{{ route('register') }}" class="resister-btn">Account</a>
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

                        <form action="#" method="POST" id="login-form">
                            <div class="reg-input-group">
                                <label for="fname">User Name *</label>
                                <input type="text" id="fname" placeholder="Your first name" required>
                            </div>
                            <div class="reg-input-group">
                                <label for="password">Password *</label>
                                <input type="password" id="password" placeholder="Enter your password" required>
                            </div>
                            <div class="password-recover-group d-flex justify-content-between">
                                <div class="reg-input-group reg-check-input d-flex align-items-center">
                                    <input type="checkbox" id="form-check" required>
                                    <label for="form-check">Remember Me</label>
                                </div>
                                <div class="forgot-password-link">
                                    <a href="#">Forgot Password?</a>
                                </div>
                            </div>
                            <div class="reg-input-group reg-submit-input d-flex align-items-center">
                                <input type="submit" id="submite-btn" value="LOG IN">
                            </div>
                        </form>

                        <div class="reg-social-login">
                            <h5>or login WITH</h5>
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
