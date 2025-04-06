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
                                <a href="#" class="text-muted small">Sửa Hồ Sơ</a>
                            </div>
                        </div>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="{{ route('profile') }}" class="text-primary fw-bold"><i
                                        class="bi bi-person me-2"></i> Tài Khoản Của Tôi</a>
                                <ul class="list-unstyled ps-4">
                                    <li><a href="{{ route('profile') }}" class="text-primary">Hồ Sơ</a></li>
                                    <li><a href="{{ route('profile.addresses') }}" class="text-muted">Địa Chỉ</a></li>
                                    <li><a href="{{ route('profile.change-password') }}" class="text-muted">Đổi Mật Khẩu</a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </div>
                </div>

                <!-- Phần Hồ Sơ bên phải -->
                <div class="col-md-9">
                    <div class="card p-4">
                        <h4 class="mb-1">Hồ Sơ Của Tôi</h4>
                        <p class="text-muted small">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
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

                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <!-- Thông tin bên trái -->
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Tên Đăng Nhập</label>
                                        <input type="text" id="name" name="name"
                                            value="{{ old('name', auth()->user()->name) }}"
                                            class="form-control @error('name') is-invalid @enderror" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" id="email" value="{{ auth()->user()->email }}"
                                            class="form-control" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Số Điện Thoại</label>
                                        <input type="text" id="phone" name="phone"
                                            value="{{ old('phone', auth()->user()->phone) }}"
                                            class="form-control @error('phone') is-invalid @enderror" required>
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Giới Tính</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input type="radio" id="male" name="gender" value="male"
                                                    class="form-check-input"
                                                    {{ old('gender', auth()->user()->gender) == 'male' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="male">Nam</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" id="female" name="gender" value="female"
                                                    class="form-check-input"
                                                    {{ old('gender', auth()->user()->gender) == 'female' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="female">Nữ</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" id="other" name="gender" value="other"
                                                    class="form-check-input"
                                                    {{ old('gender', auth()->user()->gender) == 'other' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="other">Khác</label>
                                            </div>
                                        </div>
                                        @error('gender')
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="birthday" class="form-label">Ngày Sinh</label>
                                        <input type="date" id="birthday" name="birthday"
                                            value="{{ old('birthday', auth()->user()->birthday) }}"
                                            class="form-control @error('birthday') is-invalid @enderror">
                                        @error('birthday')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Ảnh đại diện bên phải -->
                                <div class="col-md-4 text-center border-start">
                                    <div class="mb-3">
                                        @if (auth()->user()->avatar)
                                            <img src="{{ asset('storage/avatars/' . auth()->user()->avatar) }}"
                                                alt="Avatar" class="rounded-circle mb-3"
                                                style="width: 120px; height: 120px; object-fit: cover;">
                                        @else
                                            <img src="https://via.placeholder.com/120" alt="Default Avatar"
                                                class="rounded-circle mb-3" style="width: 120px; height: 120px;">
                                        @endif
                                        <div>
                                            <input type="file" id="avatar" name="avatar"
                                                class="form-control @error('avatar') is-invalid @enderror">
                                            <small class="text-muted d-block mt-1">Dung lượng file tối đa 1MB. Định dạng:
                                                JPEG, PNG</small>
                                            @error('avatar')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
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
