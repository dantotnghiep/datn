@extends('admin.layouts.master')

@section('content')
<div class="cr-main-content">
    <div class="container-fluid">
        <div class="container mt-5">
            <h1>Danh Sách Khách Hàng</h1>

            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Bộ lọc và tìm kiếm -->
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <a href="{{ route('admin.users.clients.index') }}" class="btn btn-outline-primary me-2">
                        Tất Cả ({{ \App\Models\User::where('role', 'user')->count() }})
                    </a>
                    <a href="{{ route('admin.users.clients.index', ['filter' => 'new']) }}"
                        class="btn btn-outline-primary me-2">
                        Mới
                        ({{ \App\Models\User::where('role', 'user')->where('created_at', '>=', now()->subDays(7))->count() }})
                    </a>
                    <a href="{{ route('admin.users.clients.index', ['filter' => 'top_reviews']) }}"
                        class="btn btn-outline-primary me-2">
                        Đánh Giá Cao (2)
                    </a>
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle"
                            data-bs-toggle="dropdown">
                            Địa Phương
                        </button>
                        <ul class="dropdown-menu">
                            @foreach ($provinces as $province)
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('admin.users.clients.index', ['filter' => 'local', 'province' => $province]) }}">
                                    {{ $province }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="d-flex">
                    <form action="{{ route('admin.users.clients.index') }}" method="GET" class="me-2">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm khách hàng"
                            value="{{ request('search') }}">
                    </form>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addCustomerModal">
                        <i class="bi bi-plus"></i> Thêm Khách Hàng
                    </button>
                </div>
            </div>

            <!-- Bảng danh sách khách hàng -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Khách Hàng</th>
                        <th>Email</th>
                        <th>Đơn Hàng</th>
                        <th>Tổng Chi Tiêu</th>
                        <th>Thành Phố</th>
                        <th>Đơn Hàng Gần Nhất</th>
                        <th>Trạng Thái</th>
                        <th>Cảnh báo</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if ($customer->avatar)
                                <img src="{{ asset('storage/avatars/' . $customer->avatar) }}"
                                    alt="{{ $customer->name }}" class="rounded-circle me-2"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                <div class="rounded-circle me-2 text-white d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px; background-color: #ccc; font-size: 16px;">
                                    {{ mb_substr($customer->name, 0, 1, 'UTF-8') }}
                                </div>
                                @endif
                                <a
                                    href="{{ route('admin.users.clients.detail', $customer->id) }}">{{ $customer->name }}</a>
                            </div>
                        </td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->completed_orders }}</td>
                        <td>{{ number_format($customer->total_spent, 0) }} VNĐ</td>
                        <td>{{ $customer->addresses->first()->province ?? 'N/A' }}</td>
                        <td>{{ $customer->last_order ? $customer->last_order->format('d/m/Y H:i') : 'N/A' }}</td>
                        <td>{{ $customer->status === 'active' ? 'Hoạt động' : 'Bị khóa' }}</td>
                        <td>
                            @php
                            $warning = $customer->activities()
                            ->where('activity_type', 'warning')
                            ->latest()
                            ->first();
                            @endphp
                            @if($warning)
                            <span class="badge bg-warning text-dark" title="{{ $warning->reason }}">
                                Có cảnh báo
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($customer->status === 'active')
                            <form action="{{ route('admin.users.clients.lock', $customer->id) }}"
                                method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có chắc muốn khóa tài khoản này?')">Khóa</button>
                            </form>
                            @else
                            <form action="{{ route('admin.users.clients.unlock', $customer->id) }}"
                                method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm"
                                    onclick="return confirm('Bạn có chắc muốn mở khóa tài khoản này?')">Mở
                                    khóa</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Không có khách hàng nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Phân trang -->
            <div class="d-flex justify-content-center">
                {{ $customers->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm Khách Hàng -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerModalLabel">Thêm Khách Hàng Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.clients.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên *</label>
                        <input type="text" id="name" name="name"
                            class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số Điện Thoại *</label>
                        <input type="text" id="phone" name="phone"
                            class="form-control @error('phone') is-invalid @enderror" required>
                        @error('phone')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật Khẩu *</label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Xác Nhận Mật Khẩu *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-control" required>
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