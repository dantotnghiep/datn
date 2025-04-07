@extends('admin.layouts.master')

@section('content')
<div class="cr-main-content mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Chi tiết khách hàng</h1>
        <form action="{{ route('admin.users.clients.reset-password', $customer->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm" onclick="return confirm('Bạn có chắc muốn đặt lại mật khẩu?')">
                <i class="bi bi-key me-1"></i> Đặt lại mật khẩu
            </button>
        </form>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Thông tin khách hàng và địa chỉ mặc định -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="me-3">
                            @if($customer->avatar)
                                <img src="{{ asset('storage/avatars/' . $customer->avatar) }}"
                                     alt="{{ $customer->name }}" class="rounded-circle"
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center"
                                     style="width: 80px; height: 80px; background-color: #ccc; font-size: 32px;">
                                    {{ mb_substr($customer->name, 0, 1, 'UTF-8') }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-1">{{ $customer->name }}</h3>
                            <p class="text-muted mb-2">Tham gia: {{ $customer->created_at->diffForHumans() }}</p>
                            <p class="mb-1"><i class="bi bi-envelope me-2"></i>{{ $customer->email }}</p>
                            <p class="mb-1"><i class="bi bi-telephone me-2"></i>{{ $customer->phone }}</p>
                            <p class="mb-3"><i class="bi bi-circle-fill me-2 {{ $customer->status === 'active' ? 'text-success' : 'text-danger' }}"></i>{{ $customer->status === 'active' ? 'Hoạt động' : 'Bị khóa' }}</p>
                            <!-- Nút khóa, mở khóa, cảnh báo -->
                            @if($customer->status === 'active')
                                <button type="button" class="btn btn-danger btn-sm me-2" data-bs-toggle="modal" data-bs-target="#lockModal">Khóa tài khoản</button>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#warnModal">Cảnh báo</button>
                            @else
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#unlockModal">Mở khóa</button>
                            @endif
                        </div>
                    </div>
                    <div class="row text-center mt-3">
                        <div class="col">
                            <p class="text-muted mb-0">Đơn hàng</p>
                            <h5>{{ $customer->orders->count() }}</h5>
                        </div>
                        <div class="col">
                            <p class="text-muted mb-0">Tổng chi tiêu</p>
                            <h5>{{ number_format($customer->orders()->where('status_id', 4)->sum('total_amount'), 0) }} VNĐ</h5>
                        </div>
                        <div class="col">
                            <p class="text-muted mb-0">Đơn hoàn thành</p>
                            <h5>{{ $customer->orders()->where('status_id', 4)->count() }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Địa chỉ mặc định</h5>
                    <i class="bi bi-pencil text-muted"></i>
                </div>
                <div class="card-body">
                    @if($customer->addresses->isNotEmpty())
                        @php $address = $customer->addresses->first(); @endphp
                        <p class="mb-1"><strong>{{ $address->recipient_name }}</strong></p>
                        <p class="mb-1">{{ $address->street }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}</p>
                        <p class="mb-1">Email: {{ $customer->email }}</p>
                        <p class="mb-0">Điện thoại: {{ $address->phone }}</p>
                    @else
                        <p class="text-muted">Chưa có địa chỉ</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Thống kê</h5>
        </div>
        <div class="card-body">
            <div class="row text-center">
                @foreach($stats as $label => $count)
                    <div class="col-md-2">
                        <h6>{{ $label }}</h6>
                        <p class="fw-bold">{{ $count }}</p>
                    </div>
                @endforeach
                <div class="col-md-4">
                    <h6>Tổng tiền thanh toán</h6>
                    <p class="fw-bold">{{ number_format($totalPaid, 0) }} VNĐ</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách đơn hàng -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Danh sách đơn hàng</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái thanh toán</th>
                        <th>Trạng thái giao hàng</th>
                        <th>Phương thức giao hàng</th>
                        <th>Ngày đặt</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customer->orders as $order)
                    <tr>
                        <td>{{ $order->order_code }}</td>
                        <td>{{ number_format($order->total_amount, 0) }} VNĐ</td>
                        <td>
                            <span class="badge 
                                {{ $order->payment_status === 'pending' ? 'bg-warning' : '' }}
                                {{ $order->payment_status === 'completed' ? 'bg-success' : '' }}
                                {{ $order->payment_status === 'failed' ? 'bg-danger' : '' }}
                                text-white">
                                @if($order->payment_status === 'pending')
                                    Chờ thanh toán
                                @elseif($order->payment_status === 'completed')
                                    Đã thanh toán
                                @elseif($order->payment_status === 'failed')
                                    Thất bại
                                @else
                                    {{ ucfirst($order->payment_status) }}
                                @endif
                            </span>
                        </td>
                        <td>
                            <span class="badge 
                                {{ $order->status->status_name === 'Pending' ? 'bg-warning' : '' }}
                                {{ $order->status->status_name === 'Completed' ? 'bg-success' : '' }}
                                {{ $order->status->status_name === 'Processing' ? 'bg-info' : '' }}
                                {{ $order->status->status_name === 'Cancelled' ? 'bg-danger' : '' }}
                                {{ $order->status->status_name === 'Failed' ? 'bg-danger' : '' }}
                                text-white">
                                @if($order->status->status_name === 'Pending')
                                    Đang chờ xử lý
                                @elseif($order->status->status_name === 'Completed')
                                    Đã hoàn thành
                                @elseif($order->status->status_name === 'Processing')
                                    Đang xử lý
                                @elseif($order->status->status_name === 'Cancelled')
                                    Đã hủy
                                @elseif($order->status->status_name === 'Failed')
                                    Thất bại
                                @else
                                    {{ $order->status->status_name }}
                                @endif
                            </span>
                        </td>
                        <td>{{ $order->payment_method }}</td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Không có đơn hàng nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Danh sách yêu thích -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Danh sách yêu thích</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Màu sắc</th>
                        <th>Kích thước</th>
                        <th>Giá</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customer->wishlist as $item)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($item->variation->product->images->isNotEmpty())
                                    <img src="{{ asset($item->variation->product->images->where('is_main', true)->first()->url) }}"
                                         alt="{{ $item->variation->product->name }}"
                                         style="width: 50px; height: 50px; object-fit: cover;" class="me-2">
                                @endif
                                {{ $item->variation->product->name }}
                            </div>
                        </td>
                        <td>{{ $item->variation->sku }}</td>
                        <td>N/A</td>
                        <td>{{ number_format($item->variation->sale_price ?? $item->variation->price, 0) }} VNĐ</td>
                        <td>{{ number_format($item->variation->sale_price ?? $item->variation->price, 0) }} VNĐ</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Không có sản phẩm trong danh sách yêu thích.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Hoạt động tài khoản -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Hoạt động tài khoản</h5>
        </div>
        <div class="card-body">
            @forelse($customer->activities as $activity)
            <div class="d-flex justify-content-between align-items-center mb-2">
                <p class="mb-0">
                    @if($activity->activity_type === 'locked')
                        Tài khoản bị khóa {{ $activity->created_at->diffForHumans() }}. Lý do: {{ $activity->reason }}
                    @elseif($activity->activity_type === 'unlocked')
                        Tài khoản được mở khóa {{ $activity->created_at->diffForHumans() }}. Lý do: {{ $activity->reason }}
                    @elseif($activity->activity_type === 'password_reset')
                        Mật khẩu được đặt lại {{ $activity->created_at->diffForHumans() }}. Lý do: {{ $activity->reason }}
                    @elseif($activity->activity_type === 'warning')
                        Cảnh báo {{ $activity->created_at->diffForHumans() }}: {{ $activity->reason }}
                    @endif
                </p>
                <small class="text-muted">{{ $activity->created_at->format('d/m/Y H:i') }}</small>
            </div>
            @empty
            <p class="text-center text-muted">Chưa có hoạt động nào.</p>
            @endforelse
        </div>
    </div>

    <!-- Đánh giá -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Đánh giá</h5>
        </div>
        <div class="card-body">
            <p class="text-center text-muted">Chưa có đánh giá nào.</p>
        </div>
    </div>
</div>

<!-- Modal khóa tài khoản -->
<div class="modal fade" id="lockModal" tabindex="-1" aria-labelledby="lockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lockModalLabel">Khóa tài khoản</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.clients.lock.detail', $customer->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Lý do khóa tài khoản *</label>
                        <textarea id="reason" name="reason" class="form-control @error('reason') is-invalid @enderror" required></textarea>
                        @error('reason')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger">Khóa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal mở khóa tài khoản -->
<div class="modal fade" id="unlockModal" tabindex="-1" aria-labelledby="unlockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unlockModalLabel">Mở khóa tài khoản</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.clients.unlock.detail', $customer->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Lý do mở khóa tài khoản *</label>
                        <textarea id="reason" name="reason" class="form-control @error('reason') is-invalid @enderror" required></textarea>
                        @error('reason')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success">Mở khóa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal cảnh báo -->
<div class="modal fade" id="warnModal" tabindex="-1" aria-labelledby="warnModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="warnModalLabel">Gửi cảnh báo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.clients.warn', $customer->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Nội dung cảnh báo *</label>
                        <textarea id="reason" name="reason" class="form-control @error('reason') is-invalid @enderror" required></textarea>
                        @error('reason')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-warning">Gửi cảnh báo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection