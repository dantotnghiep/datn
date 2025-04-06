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
                                        <!-- Form Quên Mật Khẩu -->
                                        <form class="forgot-form" method="post">
                                            <div class="imgcontainer">
                                                <a href="index.html"><img src="/be/assets/img/logo/full-logo.png" alt="logo"
                                                        class="logo"></a>
                                            </div>
                                            <div class="input-control">
                                                <p>Nhập email của bạn, chúng tôi sẽ gửi một liên kết để đặt lại mật khẩu.</p>
                                                <input type="email" placeholder="Nhập email của bạn" name="email"
                                                    required>
                                                <div class="login-btns">
                                                    <button type="submit">Đặt lại mật khẩu</button>
                                                </div>
                                                <div class="login-with-btns">
                                                    <span class="already-acc">Quay lại <a href="signin.html"
                                                            class="login-btn">Đăng nhập</a></span>
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
