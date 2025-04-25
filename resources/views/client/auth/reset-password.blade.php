@extends('client.layouts.master')
@section('content')
    <div class="register-wrapper ml-110 mt-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="register-switcher text-center">
                        <a href="{{ route('login') }}" class="login-btn active">Đăng nhập</a>
                    </div>
                </div>
            </div>
            <div class="row mt-100 justify-content-center">
                <div class="col-xxl-6 col-xl-6 col-lg-8 col-md-10">
                    <div class="reg-login-forms">
                        <h4 class="reg-login-title text-center">
                            Khôi phục mật khẩu
                        </h4>


                        <!-- Lỗi validation -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                {{ $errors->first() }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- Error --}}
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        {{-- Warning --}}
                        @if (session('warning'))
                            <div class="alert alert-warning" role="alert">
                                {{ session('warning') }}
                            </div>
                        @endif
                        @if ($status)
                            <form action="{{ route('reset-password.post') }}" method="POST">
                                @csrf
                                <div class="reg-input-group">
                                    <label for="password">Mật khẩu mới *</label>
                                    <input type="passowrd" id="password" name="password" value="{{ old('password') }}"
                                        class="@error('password') is-invalid @enderror">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="reg-input-group reg-submit-input d-flex align-items-center">
                                    <button type="submit" class="btn btn-primary w-100">Xác nhận</button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-warning" role="alert">
                                Liên kết không đúng hoặc đã hết hạn.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script>
        window.onload = function() {
            // Truyền session('error') trực tiếp và escape để tránh lỗi
            const errorMessage = "{{ session('error') ? addslashes(session('error')) : '' }}";
            if (errorMessage) {
                console.log("Error: " + errorMessage);
                const modalElement = document.getElementById('errorModal');
                const modalErrorText = document.getElementById('modal-error-text');

                if (modalElement && modalErrorText) {
                    modalErrorText.textContent = errorMessage;
                    try {
                        const errorModal = new bootstrap.Modal(modalElement);
                        errorModal.show();
                    } catch (e) {
                        console.error("Error showing modal: ", e);
                    }
                } else {
                    console.error("Modal element not found: ", {
                        modalElement,
                        modalErrorText
                    });
                }
            }
        };
    </script> -->

    <script>
        window.onload = function() {
            const errorMessage = "{{ session('error') ? addslashes(session('error')) : '' }}";
            if (errorMessage && errorMessage.trim() !== '') { // Kiểm tra không rỗng
                console.log("Error: " + errorMessage);
                const modalElement = document.getElementById('errorModal');
                const modalErrorText = document.getElementById('modal-error-text');

                if (modalElement && modalErrorText) {
                    modalErrorText.textContent = errorMessage;
                    try {
                        const errorModal = new bootstrap.Modal(modalElement);
                        errorModal.show();
                    } catch (e) {
                        console.error("Error showing modal: ", e);
                    }
                }
            }
        };
    </script>
@endsection
