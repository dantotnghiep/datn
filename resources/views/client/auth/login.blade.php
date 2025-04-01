@extends('client.layouts.master')
@section('content')

<div class="register-wrapper ml-110 mt-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 mb-3">
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

                  <!-- Hiển thị thông báo lỗi chung (dùng để debug) -->
                  @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <!-- Hiển thị thông báo lỗi dạng modal -->
                    @if ($errors->any())
                        <div class="d-none" id="error-message" data-error="{{ $errors->first() }}"></div>
                    @endif

                    <!-- Modal thông báo -->
                    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="errorModalLabel">Thông báo</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p id="modal-error-text"></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Đóng</button>
                                </div>
                            </div>
                        </div>
                    </div>

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
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript để hiển thị modal -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const errorMessage = document.getElementById('error-message');
        if (errorMessage && errorMessage.dataset.error) {
            const modalErrorText = document.getElementById('modal-error-text');
            modalErrorText.textContent = errorMessage.dataset.error;
            const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        }
    });
</script>

@endsection