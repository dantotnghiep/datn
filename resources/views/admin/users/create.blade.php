@extends('admin.layouts.master')

@section('content')

    <div class="cr-main-content">
        <div class="container-fluid">
            <div class="cr-page-title cr-page-title-2">
                <div class="cr-breadcrumb">
                    <h5>Thêm người dùng</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Carrot</a></li>
                        <li>Thêm người dùng</li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="cr-card card-default">
                        <div class="cr-card-content">
                            <form method="POST" action="{{ route('users.store') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Tên</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $user->name ?? '') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $user->email ?? '') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="text" name="phone" class="form-control"
                                        value="{{ old('phone', $user->phone ?? '') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu</label>
                                    <input type="password" name="password" class="form-control">

                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Trạng thái</label>
                                    <select name="status" class="form-control">
                                        <option value="active"
                                            {{ old('status', $user->status ?? '') == 'active' ? 'selected' : '' }}>Kích hoạt
                                        </option>
                                        <option value="inactive"
                                            {{ old('status', $user->status ?? '') == 'inactive' ? 'selected' : '' }}>Ẩn</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Vai trò</label>
                                    <select name="role" class="form-control">
                                        <option value="user"
                                            {{ old('role', $user->role ?? '') == 'user' ? 'selected' : '' }}>Người dùng</option>
                                        <option value="admin"
                                            {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                                        <option value="staff"
                                            {{ old('role', $user->role ?? '') == 'staff' ? 'selected' : '' }}>Nhân viên</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">Thêm</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
