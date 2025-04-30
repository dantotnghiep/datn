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
                        <tbody id="orders-table-body">
                            @forelse($orders as $order)
                                <tr id="order-row-{{ $order->id }}">
                                    <td>{{ $order->order_number }}</td>    
                                    <td>{{ $order->user_name }}</td>
                                    <td>{{ $order->user_phone }}</td>
                                    <td>{{ number_format($order->total_with_discount) }}đ</td>
                                    <td id="order-status-{{ $order->id }}">
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enable Pusher logging for debugging
        Pusher.logToConsole = false;
        console.log('Initializing Pusher connection for client orders...');

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
            
            pusher.connection.bind('error', function(err) {
                console.error('Pusher connection error:', err);
            });

            // Subscribe to the channel
            const channel = pusher.subscribe('my-channel');
            
            // Listen for order status updates
            channel.bind('my-event', function(data) {
                console.log('Received order update:', data);
                
                // Check if this is an order update
                if (data.order_number) {
                    // Find the status cell for this order
                    const statusCell = document.getElementById(`order-status-${data.id}`);
                    
                    if (statusCell) {
                        console.log('Updating status for order:', data.order_number);
                        
                        // Get status display information
                        let statusName = data.status_name || 'Unknown';
                        let statusColor = 'primary';
                        
                        // Set color based on status
                        if (data.status_id == 1) statusColor = 'warning'; // Pending
                        else if (data.status_id == 2) statusColor = 'success'; // Completed
                        else if (data.status_id == 3) statusColor = 'info'; // Shipping
                        else if (data.status_id == 4) statusColor = 'danger'; // Cancelled
                        else if (data.status_id == 5) statusColor = 'danger'; // Refunded
                        
                        // Update the status display
                        statusCell.innerHTML = `
                            <span class="badge bg-${statusColor}">
                                ${statusName}
                            </span>
                        `;
                        
                        // Highlight the updated row
                        const orderRow = document.getElementById(`order-row-${data.id}`);
                        if (orderRow) {
                            orderRow.classList.add('bg-light-warning');
                            setTimeout(() => {
                                orderRow.classList.remove('bg-light-warning');
                            }, 3000);
                        }
                        
                        // Show notification
                        toast.info(`Đơn hàng #${data.order_number} đã được cập nhật thành: ${statusName}`);
                    }
                }
            });
            
        } catch (error) {
            console.error('Error initializing Pusher:', error);
        }
    });
</script>
@endpush 