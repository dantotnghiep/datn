@extends('admin.layouts.master')

@section('content')
    <div class="cr-main-content">
        <div class="container-fluid">
            <h1 class="h3 mb-4 text-gray-800">Orders Management</h1>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">All Orders</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr id="order-row-{{ $order->id }}">
                                        <td>{{ $order->order_code }}</td>
                                        <td>{{ $order->user_name }}</td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>{{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            <span id="status-badge-{{ $order->id }}"
                                                class="badge bg-{{ $order->status_id == 1 ? 'warning' : ($order->status_id == 2 ? 'info' : ($order->status_id == 3 ? 'danger' : 'success')) }}">
                                                {{ $order->status->status_name ?? 'Processing' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($order->status_id == 1)
                                                <button type="button" class="btn btn-sm btn-success confirm-order-btn"
                                                    data-order-id="{{ $order->id }}">
                                                    Xác nhận
                                                </button>
                                            @endif
                                            @if ($order->status_id == 2)
                                                <button type="button" class="btn btn-sm btn-info complete-order-btn"
                                                    data-order-id="{{ $order->id }}">
                                                    Hoàn thành
                                                </button>
                                            @endif
                                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                                class="btn btn-sm btn-primary">Xem chi tiết</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification container -->
    <div id="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

    <!-- Thêm container cho thông báo -->
    <div id="admin-notifications" class="position-fixed" style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
    </div>
@endsection

@push('scripts')
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        $(document).ready(function() {
            // Enable pusher logging - don't include this in production
            Pusher.logToConsole = true;

            // Khởi tạo Pusher với cấu hình đầy đủ
            const pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
                cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
                encrypted: true,
                authEndpoint: '/broadcasting/auth', // Thêm endpoint xác thực
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }
            });

            // Thay đổi từ private channel sang public channel
            const channel = pusher.subscribe('orders.admin'); // Bỏ 'private-' prefix

            // Debug connection
            pusher.connection.bind('connected', function() {
                console.log('Connected to Pusher');
            });

            channel.bind('pusher:subscription_succeeded', function() {
                console.log('Successfully subscribed to channel');
            });

            // Phần code xử lý sự kiện giữ nguyên
            channel.bind('OrderStatusUpdated', function(data) {
                console.log('Received OrderStatusUpdated event:', data);
                
                const orderId = data.order.id;
                const orderRow = $(`#order-row-${orderId}`);
                const statusBadge = orderRow.find(`#status-badge-${orderId}`);
                
                // Xóa tất cả các class bg cũ
                statusBadge.removeClass('bg-warning bg-info bg-danger bg-success');
                
                // Thêm class mới dựa vào status
                let newClass = '';
                let buttonHtml = '';
                
                switch(parseInt(data.order.status_id)) {
                    case 1: // Chờ xác nhận
                        newClass = 'bg-warning';
                        buttonHtml = `
                            <button type="button" class="btn btn-sm btn-success confirm-order-btn" data-order-id="${orderId}">
                                Xác nhận
                            </button>
                        `;
                        break;
                    case 2: // Đang giao
                        newClass = 'bg-info';
                        buttonHtml = `
                            <button type="button" class="btn btn-sm btn-info complete-order-btn" data-order-id="${orderId}">
                                Hoàn thành
                            </button>
                        `;
                        break;
                    case 3: // Đã hủy
                        newClass = 'bg-danger';
                        buttonHtml = '';
                        break;
                    case 4: // Hoàn thành
                        newClass = 'bg-success';
                        buttonHtml = '';
                        break;
                }
                
                // Cập nhật UI
                statusBadge.addClass(newClass).text(data.order.status.status_name);
                
                // Cập nhật nút
                const actionCell = orderRow.find('td:last');
                const viewButton = `<a href="/admin/orders/${orderId}" class="btn btn-sm btn-primary">Xem chi tiết</a>`;
                actionCell.html(buttonHtml + ' ' + viewButton);
                
                // Hiển thị thông báo
                showNotification(
                    'Cập nhật trạng thái', 
                    `Đơn hàng #${data.order.order_code} đã được cập nhật thành ${data.order.status.status_name}`,
                    'info'
                );
            });

            // Xử lý nút xác nhận và hoàn thành
            $(document).on('click', '.confirm-order-btn, .complete-order-btn', function() {
                const orderId = $(this).data('order-id');
                const statusId = $(this).hasClass('confirm-order-btn') ? 2 : 4;
                const button = $(this);
                
                $.ajax({
                    url: `/admin/orders/${orderId}/update-status`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    data: {
                        status_id: statusId
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log('Order status updated successfully');
                        } else {
                            showNotification('Lỗi', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        let message = 'Có lỗi xảy ra khi cập nhật trạng thái';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        showNotification('Lỗi', message, 'error');
                    }
                });
            });
        });

        function showNotification(title, message, type = 'info') {
            const notification = $(`
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <strong>${title}</strong> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);

            $('#admin-notifications').prepend(notification);

            setTimeout(() => {
                notification.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 5000);
        }
    </script>
@endpush
