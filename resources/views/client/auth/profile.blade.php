@extends('client.layouts.master')
@section('content')
    <div class="profile-wrapper ml-110 mt-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-6 col-xl-6 col-lg-8 col-md-10">
                    <div class="reg-login-forms">
                        <h4 class="reg-login-title text-center mb-4">
                            Thông tin cá nhân
                        </h4>

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="reg-input-group">
                                <label for="name">Họ và tên</label>
                                <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" 
                                       class="@error('name') is-invalid @enderror" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="reg-input-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" value="{{ auth()->user()->email }}" 
                                       class="form-control" disabled>
                            </div>

                            <div class="reg-input-group">
                                <label for="phone">Số điện thoại</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" 
                                       class="@error('phone') is-invalid @enderror">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="reg-input-group reg-submit-input d-flex align-items-center">
                                <button type="submit" class="btn btn-primary w-100">Cập nhật thông tin</button>
                            </div>
                        </form>
<!-- Nút mở modal thêm địa chỉ -->
<div class="mt-5 text-center">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                Thêm địa chỉ mới
                            </button>
                        </div>

                        <!-- Modal thêm địa chỉ -->
                        <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addAddressModalLabel">Thêm địa chỉ mới</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('addresses.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="reg-input-group">
                                                <label for="recipient_name">Tên người nhận</label>
                                                <input type="text" id="recipient_name" name="recipient_name" value="{{ old('recipient_name') }}" 
                                                       class="@error('recipient_name') is-invalid @enderror" required>
                                                @error('recipient_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="reg-input-group">
                                                <label for="phone">Số điện thoại</label>
                                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" 
                                                       class="@error('phone') is-invalid @enderror" required>
                                                @error('phone')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="reg-input-group">
                                                <label for="province">Tỉnh/Thành phố</label>
                                                <input type="text" id="province" name="province" value="{{ old('province') }}" 
                                                       class="@error('province') is-invalid @enderror" required>
                                                @error('province')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="reg-input-group">
                                                <label for="district">Quận/Huyện</label>
                                                <input type="text" id="district" name="district" value="{{ old('district') }}" 
                                                       class="@error('district') is-invalid @enderror" required>
                                                @error('district')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="reg-input-group">
                                                <label for="ward">Phường/Xã</label>
                                                <input type="text" id="ward" name="ward" value="{{ old('ward') }}" 
                                                       class="@error('ward') is-invalid @enderror" required>
                                                @error('ward')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="reg-input-group">
                                                <label for="street">Số nhà / Tòa nhà / Đường</label>
                                                <input type="text" id="street" name="street" value="{{ old('street') }}" 
                                                       class="@error('street') is-invalid @enderror" required>
                                                @error('street')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="reg-input-group form-check">
                                                <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1">
                                                <label class="form-check-label" for="is_default">Đặt làm địa chỉ mặc định</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                            <button type="submit" class="btn btn-primary">Thêm địa chỉ</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Danh sách địa chỉ -->
                        <h5 class="mt-5">Địa chỉ của bạn</h5>
                        @if(auth()->user()->addresses->isEmpty())
                            <p class="text-center">Bạn chưa có địa chỉ nào</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Người nhận</th>
                                            <th>Địa chỉ</th>
                                            <th>Mặc định</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(auth()->user()->addresses as $address)
                                            <tr>
                                                <td>{{ $address->recipient_name }}</td>
                                                <td>{{ $address->street }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}</td>
                                                <td>
                                                    @if($address->is_default)
                                                        <span class="badge bg-success">Mặc định</span>
                                                    @else
                                                        <form action="{{ route('addresses.set-default', $address) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-success">Đặt làm mặc định</button>
                                                        </form>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editAddressModal{{ $address->id }}">
                                                        Chỉnh sửa
                                                    </button>
                                                    <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này không?')">Xóa</button>
                                                    </form>
                                                </td>
                                            </tr>

                                            <!-- Modal chỉnh sửa địa chỉ -->
                                            <div class="modal fade" id="editAddressModal{{ $address->id }}" tabindex="-1" aria-labelledby="editAddressModalLabel{{ $address->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editAddressModalLabel{{ $address->id }}">Chỉnh sửa địa chỉ</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('addresses.update', $address) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="reg-input-group">
                                                                    <label for="recipient_name_{{ $address->id }}">Họ tên người nhận</label>
                                                                    <input type="text" id="recipient_name_{{ $address->id }}" name="recipient_name" value="{{ old('recipient_name', $address->recipient_name) }}" 
                                                                           class="@error('recipient_name') is-invalid @enderror" required>
                                                                    @error('recipient_name')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>

                                                                <div class="reg-input-group">
                                                                    <label for="phone_{{ $address->id }}">Số điện thoại</label>
                                                                    <input type="text" id="phone_{{ $address->id }}" name="phone" value="{{ old('phone', $address->phone) }}" 
                                                                           class="@error('phone') is-invalid @enderror" required>
                                                                    @error('phone')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>

                                                                <div class="reg-input-group">
                                                                    <label for="province_{{ $address->id }}">Tỉnh/Thành phố</label>
                                                                    <input type="text" id="province_{{ $address->id }}" name="province" value="{{ old('province', $address->province) }}" 
                                                                           class="@error('province') is-invalid @enderror" required>
                                                                    @error('province')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>

                                                                <div class="reg-input-group">
                                                                    <label for="district_{{ $address->id }}">Quận/Huyện</label>
                                                                    <input type="text" id="district_{{ $address->id }}" name="district" value="{{ old('district', $address->district) }}" 
                                                                           class="@error('district') is-invalid @enderror" required>
                                                                    @error('district')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>

                                                                <div class="reg-input-group">
                                                                    <label for="ward_{{ $address->id }}">Phường/Xã</label>
                                                                    <input type="text" id="ward_{{ $address->id }}" name="ward" value="{{ old('ward', $address->ward) }}" 
                                                                           class="@error('ward') is-invalid @enderror" required>
                                                                    @error('ward')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>

                                                                <div class="reg-input-group">
                                                                    <label for="street_{{ $address->id }}">Số nhà/Tòa nhà/Đường</label>
                                                                    <input type="text" id="street_{{ $address->id }}" name="street" value="{{ old('street', $address->street) }}" 
                                                                           class="@error('street') is-invalid @enderror" required>
                                                                    @error('street')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>

                                                                <div class="reg-input-group form-check">
                                                                    <input type="checkbox" class="form-check-input" id="is_default_{{ $address->id }}" name="is_default" value="1" {{ $address->is_default ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="is_default_{{ $address->id }}">Đặt làm địa chỉ mặc định</label>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                <button type="submit" class="btn btn-primary">Cập nhật địa chỉ</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <!-- Nút đăng xuất -->
                        <div class="mt-4 text-center">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger">Đăng xuất</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection