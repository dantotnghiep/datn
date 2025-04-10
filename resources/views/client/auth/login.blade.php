@extends('client.layouts.master')
@section('content')
    
    <div class="register-wrapper ml-110 mt-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="register-switcher text-center">
                        <a href="{{ route('register') }}" class="resister-btn">Đăng ký</a>
                        <a href="{{ route('login') }}" class="login-btn active">Đăng nhập</a>
                    </div>
                </div>
            </div>
            <div class="row mt-100 justify-content-center">
                <div class="col-xxl-6 col-xl-6 col-lg-8 col-md-10">
                    <div class="reg-login-forms">
                        <h4 class="reg-login-title text-center">
                            Đăng nhập tài khoản
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
                                <label for="password">Mật khẩu *</label>
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
                                    <label for="remember">Ghi nhớ đăng nhập</label>
                                </div>
                                <div class="forgot-password-link">
                                    <a href="{{ route('forgot-password') }}">Quên mật khẩu?</a>
                                </div>
                            </div>
                            <div class="reg-input-group reg-submit-input d-flex align-items-center">
                                <button type="submit" class="btn btn-primary w-100">ĐĂNG NHẬP</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
