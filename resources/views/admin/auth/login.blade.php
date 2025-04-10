@extends('admin.layouts.formm')

@section('content')
    <main class="wrapper sb-default">
        <section class="auth-section anim">
            <div class="cr-login-page">
                <div class="container-fluid no-gutters">
                    <div class="row">
                        <div class="offset-lg-6 col-lg-6">
                            <div class="content-detail">
                                <div class="main-info">
                                    <div class="hero-container">
                                        <!-- Form Đăng Nhập -->
                                        <form class="login-form" method="post" action="{{ route('vh.dz') }}">
                                            @csrf
                                            <div class="imgcontainer">
                                                <a href="index.html"><img src="/be/assets/img/logo/full-logo.png" alt="logo"
                                                        class="logo"></a>
                                            </div>
                                            <div class="input-control">
                                                <input type="email" name="email" required placeholder="Email">
                                                <span class="password-field-show">
                                                    <input type="password" name="password" required placeholder="Mật khẩu">
                                                    <span data-toggle=".password-field"
                                                        class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                </span>
                                                <label class="label-container">Ghi nhớ đăng nhập
                                                    <input type="checkbox">
                                                    <span class="checkmark"></span>
                                                </label>
                                                <span class="psw"><a href="forgot.html" class="forgot-btn">Quên mật khẩu?</a></span>
                                                <div class="login-btns">
                                                    <button type="submit">Đăng nhập</button>
                                                </div>
                                                <div class="division-lines">
                                                    <p>hoặc đăng nhập bằng</p>
                                                </div>
                                                <div class="login-with-btns">
                                                    <button type="button" class="google">
                                                        <i class="ri-google-fill"></i>
                                                    </button>
                                                    <button type="button" class="facebook">
                                                        <i class="ri-facebook-fill"></i>
                                                    </button>
                                                    <button type="button" class="twitter">
                                                        <i class="ri-twitter-fill"></i>
                                                    </button>
                                                    <button type="button" class="linkedin">
                                                        <i class="ri-linkedin-fill"></i>
                                                    </button>
                                                    <span class="already-acc">Chưa có tài khoản? <a href="signup.html"
                                                            class="signup-btn">Đăng ký</a></span>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
