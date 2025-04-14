@extends('client.layouts.master')
@section('content')
    <div class="px-5 ml-110 mt-100">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">My Orders</h2>               

                @if ($orders->isEmpty())
                    <div class="alert alert-info">
                        You haven't placed any orders yet.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Payment Method</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr id="order-row-{{ $order->id }}">
                                        <td>{{ $order->order_code }}</td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>${{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            <span id="status-badge-{{ $order->id }}"
                                                class="badge bg-{{ $order->status_id == 1 ? 'warning' : ($order->status_id == 2 ? 'info' : ($order->status_id == 4 ? 'danger' : 'success')) }}">
                                                {{ $order->status->status_name ?? 'Processing' }}
                                            </span>
                                        </td>
                                        <td>{{ $order->payment_method }}</td>
                                        <td class="d-flex justify-content-start align-content-center">
                                            @if ($order->status_id == 1 || $order->status_id == 2)
                                                <button type="button" class="btn btn-sm btn-danger cancel-order-btn" 
                                                        data-order-id="{{ $order->id }}">
                                                    Hủy đơn hàng
                                                </button>
                                            @endif
                                            <a href="{{ route('orders.show', $order->id) }}"
                                                class="btn btn-sm btn-primary ms-2">View Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{-- {{ $orders->links() }} --}}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Notification container -->
    <div id="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>
@endsection

@push('scripts')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    $(document).ready(function() {
        // Log Pusher configuration for debugging
        
        // Khởi tạo Pusher
        const pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
            cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            encrypted: true
        });
        
        // Debug connection status
        pusher.connection.bind('connected', function() {
            console.log('Pusher connected successfully');
        });
        
        pusher.connection.bind('error', function(err) {
            console.error('Pusher connection error:', err);
        });

        // Subscribe vào channel
        const channel = pusher.subscribe('orders.admin');
        
        // Debug subscription status
        channel.bind('subscription_succeeded', function() {
            console.log('Successfully subscribed to orders.admin channel');
        });
        
        channel.bind('subscription_error', function(err) {
            console.error('Error subscribing to orders.admin channel:', err);
        });

        // Lắng nghe sự kiện cập nhật trạng thái
        channel.bind('OrderStatusUpdated', function(data) {
            console.log('OrderStatusUpdated event received:', data);
            const order = data.order;
            const orderId = order.id;
            const orderRow = $(`#order-row-${orderId}`);
            const statusBadge = orderRow.find(`#status-badge-${orderId}`);
            
            // Cập nhật trạng thái
            statusBadge.removeClass('bg-warning bg-info bg-danger bg-success');
            
            let newClass = '';
            switch(parseInt(order.status_id)) {
                case 1: newClass = 'bg-warning'; break;
                case 2: newClass = 'bg-info'; break;
                case 3: newClass = 'bg-success'; break;
                case 4: newClass = 'bg-danger'; break;
            }
            
            statusBadge.addClass(newClass).text(order.status.status_name);
            
            // Ẩn nút hủy nếu đơn hàng không còn ở trạng thái chờ xác nhận hoặc đang vận chuyển
            if (order.status_id != 1 && order.status_id != 2) {
                orderRow.find('.cancel-order-btn').hide();
            }
            
            // Hiển thị thông báo
            showNotification('Cập nhật đơn hàng', 'Đơn hàng ' + order.order_code + ' đã được cập nhật');
        });

            // Xử lý nút hủy đơn hàng
            $('.cancel-order-btn').click(function() {
                if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
                    return;
                }

            const orderId = $(this).data('order-id');
            const button = $(this);
            
            console.log('Sending cancel request for order:', orderId);
            
            $.ajax({
                url: `{{ url('orders') }}/${orderId}/cancel`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    console.log('Cancel order response:', response);
                    if (response.success) {
                        // Cập nhật UI ngay lập tức
                        const statusBadge = $(`#status-badge-${orderId}`);
                        statusBadge.removeClass('bg-warning bg-info bg-success')
                                 .addClass('bg-danger')
                                 .text('Đã hủy');
                        button.hide();
                        
                        // Hiển thị thông báo
                        showNotification('Hủy đơn hàng', 'Đơn hàng đã được hủy thành công', 'success');
                    } else {
                        showNotification('Lỗi', response.message || 'Có lỗi xảy ra', 'error');
                    }
                },
                error: function(xhr) {
                    console.error('Cancel order error:', xhr);
                    const message = xhr.responseJSON?.message || 'Có lỗi xảy ra khi hủy đơn hàng';
                    showNotification('Lỗi', message, 'error');
                }
            });
        });
        
        // Hàm hiển thị thông báo
        function showNotification(title, message, type = 'info') {
            const notification = $(`
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <strong>${title}:</strong> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);

            $('#notification-container').append(notification);

            setTimeout(() => {
                notification.fadeOut(300, function() { $(this).remove(); });
            }, 5000);
        }
    });
</script>
@endpush
