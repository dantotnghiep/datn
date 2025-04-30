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
                            <p class="mb-0" id="payment-status">
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
                            <p class="mb-0" id="order-status-{{ $order->id }}">
                                @php
                                    $statusColors = [
                                        1 => 'warning',    // Chờ xử lý - vàng
                                        2 => 'success',    // Hoàn thành - xanh lá
                                        3 => 'info',       // Đang vận chuyển - xanh dương 
                                        4 => 'danger',     // Đã hủy - đỏ
                                        5 => 'secondary'   // Đã hoàn tiền - xám
                                    ];
                                    $statusColor = isset($statusColors[$order->status_id]) ? $statusColors[$order->status_id] : 'primary';
                                @endphp
                                <span class="badge bg-{{ $statusColor }}">
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
                        {{-- Nút hủy đơn hàng --}}
                        <div id="cancel-button-container">
                            @if (!in_array($order->status_id, [2, 4, 5]))
                                <form action="{{ route('client.order.cancel.request', $order->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?');">
                                    @csrf
                                    <button type="submit" class="btn btn-danger w-100 mt-3">Hủy đơn hàng</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enable Pusher logging for debugging
        Pusher.logToConsole = false;
        console.log('Initializing Pusher connection for order details...');

        // Get current order data
        const currentOrderId = {{ $order->id }};
        const currentOrderNumber = '{{ $order->order_number }}';
        
        // Get Pusher key and cluster from configuration
        const pusherKey = '{{ config("broadcasting.connections.pusher.key") }}';
        const pusherCluster = '{{ config("broadcasting.connections.pusher.options.cluster") }}';
        
        // Make sure we have necessary configuration
        if (!pusherKey) {
            console.error('Pusher key not configured');
            return;
        }

        try {
            // Initialize Pusher
            const pusher = new Pusher(pusherKey, {
                cluster: pusherCluster || 'ap1',
                forceTLS: true
            });
            
            // Connection monitoring
            pusher.connection.bind('connected', function() {
                console.log('Successfully connected to Pusher!');
            });

            // Subscribe to the channel
            const channel = pusher.subscribe('my-channel');
            
            // Listen for order status updates
            channel.bind('my-event', function(data) {
                console.log('Received order update:', data);
                
                // Check if this update is for the current order
                if (data.order_number && data.id == currentOrderId) {
                    console.log('Updating current order status');
                    
                    // Get status display information
                    let statusName = data.status_name || 'Unknown';
                    
                    // Set color based on status
                    const statusColors = {
                        1: 'warning',    // Chờ xử lý - vàng
                        2: 'success',    // Hoàn thành - xanh lá
                        3: 'info',       // Đang vận chuyển - xanh dương 
                        4: 'danger',     // Đã hủy - đỏ
                        5: 'secondary'   // Đã hoàn tiền - xám
                    };
                    
                    let statusColor = statusColors[data.status_id] || 'primary';
                    
                    // Update the status display
                    const statusElement = document.getElementById(`order-status-${data.id}`);
                    if (statusElement) {
                        statusElement.innerHTML = `
                            <span class="badge bg-${statusColor}">
                                ${statusName}
                            </span>
                        `;
                    }
                    
                    // Update payment status if order is completed
                    if (data.status_id == 2) {
                        const paymentStatusElement = document.getElementById('payment-status');
                        if (paymentStatusElement) {
                            paymentStatusElement.innerHTML = `
                                <span class="badge bg-success">Đã thanh toán</span>
                            `;
                        }
                    }
                    
                    // Hide cancel button if order is completed, cancelled or refunded
                    if (data.status_id == 2 || data.status_id == 4 || data.status_id == 5) {
                        const cancelContainer = document.getElementById('cancel-button-container');
                        if (cancelContainer) {
                            cancelContainer.innerHTML = '';
                        }
                    }
                    
                    // Show notification
                    toast.info(`Đơn hàng #${data.order_number} đã được cập nhật thành: ${statusName}`);
                }
            });
            
        } catch (error) {
            console.error('Error initializing Pusher:', error);
        }
    });
</script>
@endpush 