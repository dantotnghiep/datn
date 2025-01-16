@extends('admin.layouts.master')

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
                                        <!-- Login form -->
                                        <form class="login-form" method="post">
                                            <div class="imgcontainer">
                                                <a href="index.html"><img src="assets/img/logo/full-logo.png" alt="logo"
                                                        class="logo"></a>
                                            </div>
                                            <div class="input-control">
                                                <input type="text" placeholder="Enter Username" name="uname" required>
                                                <span class="password-field-show">
                                                    <input type="password" placeholder="Enter Password" name="password"
                                                        class="password-field" value="" required>
                                                    <span data-toggle=".password-field"
                                                        class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                </span>
                                                <label class="label-container">Remember me
                                                    <input type="checkbox">
                                                    <span class="checkmark"></span>
                                                </label>
                                                <span class="psw"><a href="forgot.html" class="forgot-btn">Forgot
                                                        password?</a></span>
                                                <div class="login-btns">
                                                    <button type="submit">Login</button>
                                                </div>
                                                <div class="division-lines">
                                                    <p>or login with</p>
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
                                                    <span class="already-acc">Not a member? <a href="signup.html"
                                                            class="signup-btn">Sign up</a></span>
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
