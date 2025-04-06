@extends('client.layouts.master')
@section('content')
    <div class="profile-wrapper mt-5">
        <div class="container">
            <div class="row">
                <!-- Sidebar bên trái -->
                <div class="col-md-3">
                    <div class="sidebar">
                        <div class="d-flex align-items-center mb-3">
                            @if (auth()->user()->avatar)
                                <img src="{{ asset('storage/avatars/' . auth()->user()->avatar) }}" alt="Avatar"
                                    class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/120" alt="Default Avatar" class="rounded-circle mb-3"
                                    style="width: 120px; height: 120px;">
                            @endif
                            <div>
                                <p class="mb-0 fw-bold">{{ auth()->user()->name }}</p>
                                <a href="{{ route('profile') }}" class="text-muted small">Sửa Hồ Sơ</a>
                            </div>
                        </div>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="{{ route('profile') }}" class="text-primary fw-bold"><i
                                        class="bi bi-person me-2"></i> Tài Khoản Của Tôi</a>
                                <ul class="list-unstyled ps-4">
                                    <li><a href="{{ route('profile') }}" class="text-muted">Hồ Sơ</a></li>
                                    <li><a href="{{ route('profile.addresses') }}" class="text-muted">Địa Chỉ</a></li>
                                    <li><a href="{{ route('profile.change-password') }}" class="text-primary">Đổi Mật
                                            Khẩu</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Phần Đổi Mật Khẩu bên phải -->

                <div class="col-md-9">

                    <div class="card p-4">
                        <h4 class="mb-1">Đổi Mật Khẩu</h4>
                        <p class="text-muted small">Để bảo mật tài khoản, vui lòng không chia sẻ mật khẩu cho người khác</p>
                        <hr>

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('profile.change-password.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Mật Khẩu Cũ</label>
                                        <input type="password" id="current_password" name="current_password"
                                            class="form-control @error('current_password') is-invalid @enderror" required>
                                        @error('current_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">Mật Khẩu Mới</label>
                                        <input type="password" id="new_password" name="new_password"
                                            class="form-control @error('new_password') is-invalid @enderror" required>
                                        @error('new_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="new_password_confirmation" class="form-label">Xác Nhận Mật Khẩu
                                            Mới</label>
                                        <input type="password" id="new_password_confirmation"
                                            name="new_password_confirmation" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-danger">Lưu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
