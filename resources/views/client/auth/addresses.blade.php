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
                            <div class="rounded-circle me-2 text-white d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px; background-color: #ccc; font-size: 16px;">
                            {{ mb_substr($user->name, 0, 1, 'UTF-8') }}
                        </div>
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
                                    <li><a href="{{ route('profile.addresses') }}" class="text-primary">Địa Chỉ</a></li>
                                    <li><a href="{{ route('profile.change-password') }}" class="text-muted">Đổi Mật Khẩu</a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </div>
                </div>

                <!-- Phần Địa Chỉ bên phải -->
                <div class="col-md-9">
                    <div class="card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0">Địa Chỉ Của Tôi</h4>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#addAddressModal">
                                <i class="bi bi-plus"></i> Thêm Địa Chỉ Mới
                            </button>
                        </div>
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

                        @if ($addresses->isEmpty())
                            <p class="text-center">Bạn chưa có địa chỉ nào.</p>
                        @else
                            @foreach ($addresses as $address)
                                <div class="border-bottom py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="mb-1 fw-bold">{{ $address->recipient_name }} (+84)
                                                {{ $address->phone }}</p>
                                            <p class="mb-1 text-muted">{{ $address->street }}, {{ $address->ward }},
                                                {{ $address->district }}, {{ $address->province }}</p>
                                            @if ($address->is_default)
                                                <span class="badge bg-success">Mặc Định</span>
                                            @endif
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-link text-primary p-0 me-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editAddressModal{{ $address->id }}">
                                                Cập nhật
                                            </button>
                                            @if (!$address->is_default)
                                                <form action="{{ route('addresses.set-default', $address) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-link text-muted p-0 me-2">Thiết
                                                        lập mặc định</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('addresses.destroy', $address) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0"
                                                    onclick="return confirm('Bạn có chắc muốn xóa địa chỉ này không?')">Xóa</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal chỉnh sửa địa chỉ -->
                                <div class="modal fade" id="editAddressModal{{ $address->id }}" tabindex="-1"
                                    aria-labelledby="editAddressModalLabel{{ $address->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editAddressModalLabel{{ $address->id }}">Chỉnh
                                                    Sửa Địa Chỉ</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('addresses.update', $address) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="recipient_name_{{ $address->id }}"
                                                            class="form-label">Tên Người Nhận *</label>
                                                        <input type="text" id="recipient_name_{{ $address->id }}"
                                                            name="recipient_name"
                                                            value="{{ old('recipient_name', $address->recipient_name) }}"
                                                            class="form-control @error('recipient_name') is-invalid @enderror"
                                                            required>
                                                        @error('recipient_name')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="phone_{{ $address->id }}" class="form-label">Số Điện
                                                            Thoại *</label>
                                                        <input type="text" id="phone_{{ $address->id }}" name="phone"
                                                            value="{{ old('phone', $address->phone) }}"
                                                            class="form-control @error('phone') is-invalid @enderror"
                                                            required>
                                                        @error('phone')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="street_{{ $address->id }}"
                                                            class="form-label">Đường/Số Nhà *</label>
                                                        <input type="text" id="street_{{ $address->id }}"
                                                            name="street" value="{{ old('street', $address->street) }}"
                                                            class="form-control @error('street') is-invalid @enderror"
                                                            required>
                                                        @error('street')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="ward_{{ $address->id }}"
                                                            class="form-label">Phường/Xã *</label>
                                                        <input type="text" id="ward_{{ $address->id }}"
                                                            name="ward" value="{{ old('ward', $address->ward) }}"
                                                            class="form-control @error('ward') is-invalid @enderror"
                                                            required>
                                                        @error('ward')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="district_{{ $address->id }}"
                                                            class="form-label">Quận/Huyện *</label>
                                                        <input type="text" id="district_{{ $address->id }}"
                                                            name="district"
                                                            value="{{ old('district', $address->district) }}"
                                                            class="form-control @error('district') is-invalid @enderror"
                                                            required>
                                                        @error('district')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="province_{{ $address->id }}"
                                                            class="form-label">Tỉnh/Thành Phố *</label>
                                                        <input type="text" id="province_{{ $address->id }}"
                                                            name="province"
                                                            value="{{ old('province', $address->province) }}"
                                                            class="form-control @error('province') is-invalid @enderror"
                                                            required>
                                                        @error('province')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="form-check mb-3">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="is_default_{{ $address->id }}" name="is_default"
                                                            value="1" {{ $address->is_default ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="is_default_{{ $address->id }}">Đặt làm địa chỉ mặc
                                                            định</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Đóng</button>
                                                    <button type="submit" class="btn btn-primary">Cập Nhật</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal thêm địa chỉ -->
    <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAddressModalLabel">Thêm Địa Chỉ Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('addresses.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="recipient_name" class="form-label">Tên Người Nhận *</label>
                            <input type="text" id="recipient_name" name="recipient_name"
                                value="{{ old('recipient_name') }}"
                                class="form-control @error('recipient_name') is-invalid @enderror" required>
                            @error('recipient_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Số Điện Thoại *</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                class="form-control @error('phone') is-invalid @enderror" required>
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="street" class="form-label">Đường/Số Nhà *</label>
                            <input type="text" id="street" name="street" value="{{ old('street') }}"
                                class="form-control @error('street') is-invalid @enderror" required>
                            @error('street')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="ward" class="form-label">Phường/Xã *</label>
                            <input type="text" id="ward" name="ward" value="{{ old('ward') }}"
                                class="form-control @error('ward') is-invalid @enderror" required>
                            @error('ward')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="district" class="form-label">Quận/Huyện *</label>
                            <input type="text" id="district" name="district" value="{{ old('district') }}"
                                class="form-control @error('district') is-invalid @enderror" required>
                            @error('district')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="province" class="form-label">Tỉnh/Thành Phố *</label>
                            <input type="text" id="province" name="province" value="{{ old('province') }}"
                                class="form-control @error('province') is-invalid @enderror" required>
                            @error('province')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="is_default" name="is_default"
                                value="1">
                            <label class="form-check-label" for="is_default">Đặt làm địa chỉ mặc định</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
