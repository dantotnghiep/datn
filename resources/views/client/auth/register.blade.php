
@extends('client.layouts.master')
@section('content')
    <div class="register-wrapper ml-110 mt-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="register-switcher text-center">
                        <a href="{{ route('register') }}" class="resister-btn active">Đăng ký</a>
                        <a href="{{ route('login') }}" class="login-btn">Đăng nhập</a>
                    </div>
                </div>
            </div>
            <div class="row mt-100 justify-content-center">
                <div class="col-xxl-6 col-xl-6 col-lg-8 col-md-10">
                    <div class="reg-login-forms">
                        <h4 class="reg-login-title text-center">
                            Tạo tài khoản mới 2
                        </h4>

                        <form action="{{ route('register.post') }}" method="POST">
                            <div class="reg-input-group">
                                <label for="name">Họ và tên *</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="@error('name') is-invalid @enderror">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="reg-input-group">
                                <label for="email">Email *</label>
                                <input type="text" id="email" name="email" value="{{ old('email') }}"
                                    class="@error('email') is-invalid @enderror">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="reg-input-group">
                                <label for="password">Mật khẩu *</label>
                                <input type="password" id="password" name="password"
                                    class="@error('password') is-invalid @enderror">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="reg-input-group">
                                <label for="password_confirmation">Xác nhận mật khẩu *</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                class="@error('password_confirmation') is-invalid @enderror">
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="reg-input-group">
                                <label for="phone">Số điện thoại</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                    class="@error('phone') is-invalid @enderror">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="reg-input-group reg-submit-input d-flex align-items-center">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">ĐĂNG KÝ</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
