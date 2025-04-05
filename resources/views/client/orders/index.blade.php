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
                                                class="badge bg-{{ $order->status_id == 1 ? 'warning' : ($order->status_id == 2 ? 'info' : ($order->status_id == 3 ? 'success' : 'danger')) }}">
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
        // Khởi tạo Pusher
        const pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
            cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            encrypted: true
        });

        // Subscribe vào channel
        const channel = pusher.subscribe('orders.admin');

        // Lắng nghe sự kiện cập nhật trạng thái
        channel.bind('OrderStatusUpdated', function(data) {
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
            
            // Ẩn nút hủy nếu đơn hàng đã bị hủy hoặc hoàn thành
            if (order.status_id == 3 || order.status_id == 4) {
                orderRow.find('.cancel-order-btn').remove();
            }
        });

        // Xử lý nút hủy đơn hàng
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
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {   
                    if (response.success) {
                        // Cập nhật UI ngay lập tức
                        const statusBadge = $(`#status-badge-${orderId}`);
                        statusBadge.removeClass('bg-warning bg-info bg-success')
                                 .addClass('bg-danger')
                                 .text('Hủy');
                        button.remove();
                    }
                },
                error: function(xhr) {
                    alert('Có lỗi xảy ra khi hủy đơn hàng');
                }
            });
        });
    });
</script>
@endpush
