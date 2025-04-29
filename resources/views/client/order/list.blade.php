@extends('client.master')
@section('content')
    <div class="container-small">
        <nav class="mb-3" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Đơn hàng của tôi</li>
            </ol>
        </nav>
        <h2 class="mb-6">Đơn hàng của tôi</h2>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Tên người nhận</th>
                                <th>Số điện thoại</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái đơn hàng</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>    
                                    <td>{{ $order->user_name }}</td>
                                    <td>{{ $order->user_phone }}</td>
                                    <td>{{ number_format($order->total_with_discount) }}đ</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status->color ?? 'primary' }}">
                                            {{ $order->status->name ?? 'Đang xử lý' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('client.order.detail', $order->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Xem chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">Bạn chưa có đơn hàng nào</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($orders->hasPages())
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection 