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

                        {{-- Hiện trạng thái hoàn tiền nếu có --}}
                        @if($order->status_id == 4 && $order->payment_method == 'bank' && $order->refunds()->exists())
                            <div class="mb-4">
                                <h5 class="mb-2">Trạng thái hoàn tiền</h5>
                                <p class="mb-0" id="refund-status">
                                    @php
                                        $refund = $order->refunds()->latest()->first();
                                    @endphp
                                    @if($refund && $refund->is_active == 0)
                                        <span class="badge bg-success">Đã hoàn tiền</span>
                                    @else
                                        <span class="badge bg-warning">Đang xử lý hoàn tiền</span>
                                    @endif
                                </p>
                            </div>
                        @endif

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
                                <button type="button" class="btn btn-danger w-100 mt-3" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                                    Hủy đơn hàng
                                </button>
                            @endif
                        </div>
                        
                        {{-- Nút yêu cầu hoàn tiền --}}
                        @if ($order->status_id == 4 && $order->payment_method == 'bank' && $order->payment_status == 'completed' && !$order->refunds()->exists())
                            <div id="refund-button-container">
                                <button type="button" class="btn btn-primary w-100 mt-3" data-bs-toggle="modal" data-bs-target="#refundModal">
                                    Gửi yêu cầu hoàn tiền
                                </button>
                            </div>
                        @endif
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
                        
                        // Hiển thị nút hoàn tiền nếu đơn hàng bị hủy, thanh toán bằng bank và đã thanh toán
                        if (data.status_id == 4) {
                            // Kiểm tra điều kiện hiển thị nút yêu cầu hoàn tiền
                            fetch(`/api/orders/${currentOrderId}/can-request-refund`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.can_request) {
                                        const refundContainer = document.getElementById('refund-button-container');
                                        if (!refundContainer) {
                                            // Tạo container nếu chưa có
                                            const newRefundContainer = document.createElement('div');
                                            newRefundContainer.id = 'refund-button-container';
                                            newRefundContainer.innerHTML = `
                                                <button type="button" class="btn btn-primary w-100 mt-3" data-bs-toggle="modal" data-bs-target="#refundModal">
                                                    Gửi yêu cầu hoàn tiền
                                                </button>
                                            `;
                                            
                                            // Thêm vào DOM
                                            const cardBody = document.querySelector('.card-body');
                                            if (cardBody) {
                                                cardBody.appendChild(newRefundContainer);
                                            }
                                        }
                                    }
                                })
                                .catch(error => console.error('Error checking refund status:', error));
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

<!-- Modal Hủy Đơn Hàng -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cancelOrderModalLabel">Xác nhận hủy đơn hàng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('client.order.cancel.request', $order->id) }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="cancelReason" class="form-label">Lý do hủy đơn hàng <span class="text-danger">*</span></label>
            <select class="form-select" id="cancelReason" name="reason" required>
              <option value="" selected disabled>-- Chọn lý do hủy --</option>
              <option value="Thay đổi ý định mua hàng">Thay đổi ý định mua hàng</option>
              <option value="Tìm thấy sản phẩm tốt hơn">Tìm thấy sản phẩm tốt hơn</option>
              <option value="Đặt nhầm sản phẩm">Đặt nhầm sản phẩm</option>
              <option value="Thời gian giao hàng quá lâu">Thời gian giao hàng quá lâu</option>
              <option value="Lý do khác">Lý do khác</option>
            </select>
          </div>
          <div class="mb-3" id="otherReasonContainer" style="display: none;">
            <label for="otherReason" class="form-label">Lý do khác</label>
            <textarea class="form-control" id="otherReason" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-danger" id="submitCancelBtn">Xác nhận hủy</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Xử lý hiển thị trường "lý do khác" khi chọn "Lý do khác"
    const reasonSelect = document.getElementById('cancelReason');
    const otherReasonContainer = document.getElementById('otherReasonContainer');
    const otherReasonInput = document.getElementById('otherReason');
    const submitBtn = document.getElementById('submitCancelBtn');
    
    reasonSelect.addEventListener('change', function() {
      if (this.value === 'Lý do khác') {
        otherReasonContainer.style.display = 'block';
        otherReasonInput.setAttribute('required', 'required');
      } else {
        otherReasonContainer.style.display = 'none';
        otherReasonInput.removeAttribute('required');
      }
    });
    
    // Khi nhấn nút submit, nếu chọn "Lý do khác" thì sẽ gán giá trị từ textarea vào select
    submitBtn.addEventListener('click', function(event) {
      if (reasonSelect.value === 'Lý do khác' && otherReasonInput.value.trim() !== '') {
        // Tạo một input ẩn để gửi lý do khác
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'reason';
        hiddenInput.value = otherReasonInput.value.trim();
        
        // Thêm input ẩn vào form
        this.form.appendChild(hiddenInput);
        
        // Loại bỏ select để tránh xung đột
        reasonSelect.disabled = true;
      }
    });
  });
</script>
@endpush

<!-- Modal Hoàn Tiền -->
<div class="modal fade" id="refundModal" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="refundModalLabel">Yêu cầu hoàn tiền</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('client.order.request.refund', $order->id) }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="alert alert-info">
            <p class="mb-0">Vui lòng cung cấp thông tin tài khoản ngân hàng để nhận tiền hoàn lại.</p>
          </div>
          <div class="mb-3">
            <label for="bank" class="form-label">Tên ngân hàng <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="bank" name="bank" required placeholder="VCB, Agribank, BIDV, ...">
          </div>
          <div class="mb-3">
            <label for="bank_number" class="form-label">Số tài khoản <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="bank_number" name="bank_number" required>
          </div>
          <div class="mb-3">
            <label for="bank_name" class="form-label">Tên chủ tài khoản <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="bank_name" name="bank_name" required>
          </div>
          <div class="mb-3">
            <label for="notes" class="form-label">Ghi chú (nếu có)</label>
            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-primary">Gửi yêu cầu hoàn tiền</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<!-- Script để hiển thị modal hoàn tiền tự động khi cần -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    @if(session('show_refund_modal'))
      // Hiển thị modal hoàn tiền
      var refundModal = new bootstrap.Modal(document.getElementById('refundModal'));
      refundModal.show();
    @endif

    // Kiểm tra trạng thái hoàn tiền định kỳ (60 giây một lần)
    const currentOrderId = {{ $order->id }};
    
    function checkRefundStatus() {
      if ($('#refund-status').length > 0) {
        fetch(`/api/orders/${currentOrderId}/refund-status`)
          .then(response => response.json())
          .then(data => {
            if (data.status === 'success' && data.has_refund) {
              // Cập nhật giao diện
              const statusElement = $('#refund-status');
              
              if (data.refund_status === 'completed') {
                statusElement.html('<span class="badge bg-success">Đã hoàn tiền</span>');
                
                // Ẩn nút yêu cầu hoàn tiền nếu có
                $('#refund-button-container').hide();
              } else {
                statusElement.html('<span class="badge bg-warning">Đang xử lý hoàn tiền</span>');
              }
            }
          })
          .catch(error => console.error('Error checking refund status:', error));
      }
    }
    
    // Kiểm tra lần đầu
    checkRefundStatus();
    
    // Thiết lập kiểm tra định kỳ
    setInterval(checkRefundStatus, 60000); // 60 giây
  });
</script>
@endpush 