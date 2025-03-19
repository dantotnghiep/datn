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
                                                class="badge bg-{{ $order->status_id == 1 ? 'warning' : ($order->status_id == 2 ? 'info' : ($order->status_id == 3 ? 'danger' : 'success')) }}">
                                                {{ $order->status->status_name ?? 'Processing' }}
                                            </span>
                                        </td>
                                        <td>{{ $order->payment_method }}</td>
                                        <td class="d-flex justify-content-start align-content-center">
                                            @if ($order->status_id != 3 && $order->status_id != 4)
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
    // Khởi tạo Pusher thay vì Echo
    const pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
        cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
        encrypted: true,
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        }
    });

    // Đăng ký kênh private
    const channel = pusher.subscribe('private-orders.{{ Auth::id() }}');

    // Debug để kiểm tra kết nối
    pusher.connection.bind('connected', () => {
        console.log('Connected to Pusher');
    });

    channel.bind('pusher:subscription_succeeded', () => {
        console.log('Subscribed to orders channel');
    });

    channel.bind('pusher:subscription_error', (error) => {
        console.error('Subscription error:', error);
    });

    // Lắng nghe sự kiện OrderStatusUpdated
    channel.bind('OrderStatusUpdated', function(data) {
        console.log('Order status updated:', data);
        updateOrderStatus(data.order);
        
        // Hiển thị thông báo dựa vào trạng thái
        if (data.order.status_id == 3) {
            showNotification(`Đơn hàng #${data.order.order_code} đã được hủy`, 'warning');
        } else {
            showNotification(`Đơn hàng #${data.order.order_code} đã được cập nhật trạng thái thành ${data.order.status.status_name}`, 'info');
        }
    });

    // Không cần lắng nghe OrderCancelled riêng nữa vì đã xử lý trong OrderStatusUpdated
    // channel.bind('OrderCancelled', function(data) {
    //     console.log('Order cancelled:', data);
    //     updateOrderStatus(data.order);
    //     
    //     // Hiển thị thông báo
    //     showNotification(`Đơn hàng #${data.order.order_code} đã được hủy`, 'warning');
    // });

    // Sửa lại phần xử lý nút hủy đơn hàng
    $('.cancel-order-btn').click(function() {
        if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
            return;
        }

        const orderId = $(this).data('order-id');
        const button = $(this);
        
        $.ajax({
            url: `/orders/${orderId}/cancel`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    const orderRow = button.closest('tr');
                    const statusBadge = orderRow.find('#status-badge-' + orderId);
                    
                    statusBadge.removeClass('bg-warning bg-info bg-success')
                             .addClass('bg-danger')
                             .text('Đã hủy');
                    
                    button.remove();
                    
                    showNotification('Đơn hàng đã được hủy thành công', 'success');
                } else {
                    showNotification(response.message || 'Có lỗi xảy ra khi hủy đơn hàng', 'danger');
                }
            },
            error: function(xhr) {
                console.log('Error:', xhr);
                let message = 'Có lỗi xảy ra khi hủy đơn hàng';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showNotification(message, 'danger');
            }
        });
    });
});

function updateOrderStatus(orderData) {
    const statusBadge = $(`#status-badge-${orderData.id}`);
    if (statusBadge.length) {
        // Xóa tất cả các class bg-*
        statusBadge.removeClass('bg-warning bg-info bg-danger bg-success');
        
        // Thêm class mới dựa vào status_id
        let newClass = '';
        switch(parseInt(orderData.status_id)) {
            case 1: newClass = 'bg-warning'; break;
            case 2: newClass = 'bg-info'; break;
            case 3: newClass = 'bg-danger'; break;
            case 4: newClass = 'bg-success'; break;
            default: newClass = 'bg-warning';
        }
        
        statusBadge.addClass(newClass);
        statusBadge.text(orderData.status.status_name);
        
        // Ẩn nút hủy nếu đơn hàng đã hoàn thành hoặc đã hủy
        if (orderData.status_id == 3 || orderData.status_id == 4) {
            $(`#order-row-${orderData.id}`).find('.cancel-order-btn').remove();
        }
    }
}

function showNotification(message, type = 'info') {
    // Xóa thông báo cũ nếu có
    $('#notification-container').empty();
    
    const notification = $(`
        <div class="alert alert-${type} alert-dismissible fade show">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `);
    
    $('#notification-container').append(notification);
    
    // Tăng thời gian hiển thị thông báo
    setTimeout(() => {
        notification.fadeOut(500, function() {
            $(this).remove();
        });
    }, 3000);
}
</script>
@endpush
