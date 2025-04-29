@extends('client.master')
@section('content')
    <div class="container-small">
        <nav class="mb-3" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('client.order.list') }}">Đơn hàng của tôi</a></li>
                <li class="breadcrumb-item active" aria-current="page">Chi tiết đơn hàng #{{ $order->order_number }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-4">Chi tiết đơn hàng</h3>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Đơn giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset(optional($item->productVariation->product->images->first())->image_path ?? 'assets/img/products/default.png') }}"
                                                        alt="{{ $item->productVariation->product->name }}" width="60" class="me-3">
                                                    <div>
                                                        <h6 class="mb-1">{{ $item->productVariation->product->name }}</h6>
                                                        <p class="mb-0 text-muted small">
                                                            {{ $item->productVariation->name }}
                                                            @if($item->productVariation->attributeValues->count())
                                                                - {{ $item->productVariation->attributeValues->map(function($attrVal) {
                                                                    return ($attrVal->attribute ? $attrVal->attribute->name . ': ' : '') . $attrVal->value;
                                                                })->implode(' / ') }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ number_format($item->price) }}đ</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->total) }}đ</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-4">Thông tin đơn hàng</h3>
                        
                        <div class="mb-4">
                            <h5 class="mb-2">Thông tin người nhận</h5>
                            <p class="mb-1"><strong>Tên:</strong> {{ $order->user_name }}</p>
                            <p class="mb-1"><strong>Điện thoại:</strong> {{ $order->user_phone }}</p>
                            <p class="mb-0"><strong>Địa chỉ:</strong> {{ $order->address }}, {{ $order->ward }}, {{ $order->district }}, {{ $order->province }}</p>
                        </div>

                        <div class="mb-4">
                            <h5 class="mb-2">Phương thức thanh toán</h5>
                            <p class="mb-0">
                                @switch($order->payment_method)
                                    @case('bank')
                                        <span class="badge bg-info">Chuyển khoản</span>
                                        @break
                                    @case('cod')
                                        <span class="badge bg-secondary">Thanh toán khi nhận hàng</span>
                                        @break
                                @endswitch
                            </p>
                        </div>

                        <div class="mb-4">
                            <h5 class="mb-2">Trạng thái thanh toán</h5>
                            <p class="mb-0">
                                @switch($order->payment_status)
                                    @case('pending')
                                        <span class="badge bg-warning">Chờ thanh toán</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-success">Đã thanh toán</span>
                                        @break
                                    @case('failed')
                                        <span class="badge bg-danger">Thanh toán thất bại</span>
                                        @break
                                @endswitch
                            </p>
                        </div>

                        <div class="mb-4">
                            <h5 class="mb-2">Trạng thái đơn hàng</h5>
                            <p class="mb-0">
                                <span class="badge bg-{{ $order->status->color ?? 'primary' }}">
                                    {{ $order->status->name ?? 'Đang xử lý' }}
                                </span>
                            </p>
                        </div>

                        <div class="border-top pt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tổng tiền hàng:</span>
                                <span>{{ number_format($order->total) }}đ</span>
                            </div>
                            @if($order->discount > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Giảm giá:</span>
                                    <span class="text-danger">-{{ number_format($order->discount) }}đ</span>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Tổng thanh toán:</span>
                                <span>{{ number_format($order->total_with_discount) }}đ</span>
                            </div>
                        </div>
                        {{-- Nút yêu cầu hủy --}}
                        @if (!in_array($order->status_id, [2, 4, 5]) && !$order->cancellation)
                            <form action="{{ route('client.order.cancel.request', $order->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn gửi yêu cầu hủy đơn hàng này?');">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100 mt-3">Yêu cầu hủy đơn hàng</button>
                            </form>
                        @elseif($order->cancellation)
                            <div class="alert alert-warning mt-3">Bạn đã gửi yêu cầu hủy đơn hàng này.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 