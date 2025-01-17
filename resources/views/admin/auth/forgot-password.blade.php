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
                                        <!-- Forgot form -->
                                        <form class="forgot-form" method="post">
                                            <div class="imgcontainer">
                                                <a href="index.html"><img src="/be/assets/img/logo/full-logo.png" alt="logo"
                                                        class="logo"></a>
                                            </div>
                                            <div class="input-control">
                                                <p>Enter your email, we will send a link to reset your password.</p>
                                                <input type="email" placeholder="Enter your email" name="email"
                                                    required>
                                                <div class="login-btns">
                                                    <button type="submit">Reset</button>
                                                </div>
                                                <div class="login-with-btns">
                                                    <span class="already-acc">Return to<a href="signin.html"
                                                            class="login-btn">Login</a></span>
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
