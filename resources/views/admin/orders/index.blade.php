@extends('admin.layouts.master')

@section('content')
    <div class="cr-main-content">
        <div class="container-fluid">
            <h1 class="h3 mb-4 text-gray-800">Quản lý Đơn Hàng</h1>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tất cả Đơn Hàng</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Mã Đơn Hàng</th>
                                    <th>Khách Hàng</th>
                                    <th>Ngày</th>
                                    <th>Tổng Tiền</th>
                                    <th>Trạng Thái</th>
                                    <th>Thao Tác</th>
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
                                                class="badge bg-{{ $order->status_id == 1 ? 'warning' : ($order->status_id == 2 ? 'info' : ($order->status_id == 3 ? 'success' : 'danger')) }}">
                                                {{ $order->status->status_name ?? 'Đang xử lý' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($order->status_id == 1)
                                                <button type="button" class="btn btn-sm btn-success update-status-btn"
                                                    data-order-id="{{ $order->id }}" data-status-id="2">
                                                    Xác nhận
                                                </button>
                                            @endif
                                            @if ($order->status_id == 2)
                                                <button type="button" class="btn btn-sm btn-info update-status-btn"
                                                    data-order-id="{{ $order->id }}" data-status-id="3">
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
            // Khởi tạo Pusher
            const pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
                cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
                encrypted: true
            });

            // Subscribe vào channel
            const channel = pusher.subscribe('orders.admin');

            // Lắng nghe sự kiện cập nhật trạng thái
            channel.bind('OrderStatusUpdated', function(data) {
                updateOrderUI(data.order);
            });

            // Xử lý tất cả các nút cập nhật trạng thái
            $(document).on('click', '.update-status-btn', function() {
                const orderId = $(this).data('order-id');
                const statusId = $(this).data('status-id');
                updateOrderStatus(orderId, statusId);
            });
        });

        // Hàm cập nhật UI đơn hàng
        function updateOrderUI(order) {
            const orderId = order.id;
            const orderRow = $(`#order-row-${orderId}`);
            const statusBadge = orderRow.find(`#status-badge-${orderId}`);
            
            // Xóa tất cả class cũ
            statusBadge.removeClass('bg-warning bg-info bg-danger bg-success');
            
            // Cấu hình cho từng trạng thái
            const statusConfig = {
                1: { class: 'bg-warning', button: '<button type="button" class="btn btn-sm btn-success update-status-btn" data-order-id="' + orderId + '" data-status-id="2">Xác nhận</button>' },
                2: { class: 'bg-info', button: '<button type="button" class="btn btn-sm btn-info update-status-btn" data-order-id="' + orderId + '" data-status-id="3">Hoàn thành</button>' },
                3: { class: 'bg-success', button: '' },
                4: { class: 'bg-danger', button: '' }
            };

            const config = statusConfig[order.status_id];
            
            // Cập nhật badge và button
            statusBadge.addClass(config.class).text(order.status.status_name);
            
            const actionCell = orderRow.find('td:last');
            const viewButton = `<a href="/admin/orders/${orderId}" class="btn btn-sm btn-primary">Xem chi tiết</a>`;
            actionCell.html(config.button + ' ' + viewButton);
            
            // Hiển thị thông báo
            showNotification(
                'Cập nhật trạng thái',
                `Đơn hàng #${order.order_code} đã được cập nhật thành ${order.status.status_name}`,
                'info'
            );
        }

        // Hàm cập nhật trạng thái đơn hàng
        function updateOrderStatus(orderId, statusId) {
            $.ajax({
                url: `/admin/orders/${orderId}/update-status`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                data: { status_id: statusId },
                success: function(response) {
                    if (!response.success) {
                        showNotification('Lỗi', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Có lỗi xảy ra khi cập nhật trạng thái';
                    showNotification('Lỗi', message, 'error');
                }
            });
        }

        // Hàm hiển thị thông báo
        function showNotification(title, message, type = 'info') {
            const notification = $(` 
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <strong>${title}</strong> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);

            $('#admin-notifications').prepend(notification);

            setTimeout(() => {
                notification.fadeOut(300, function() { $(this).remove(); });
            }, 5000);
        }
    </script>
@endpush
