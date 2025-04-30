{{-- Thêm style cho notification --}}
<style>
    .notification-badge {
        position: absolute;
        top: -3px;
        right: -3px;
        padding: 2px 4px;
        border-radius: 50%;
        background: red;
        color: white;
        font-size: 8px;
        min-width: 15px;
        height: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .notification-dropdown {
        position: absolute !important;
        right: 0 !important;
        left: auto !important;
        top: 100% !important;
        min-width: 280px;
        max-height: 350px;
        overflow-y: auto;
        margin-top: 10px !important;
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border-radius: 0.5rem;
    }
    .notification-item {
        padding: 8px 12px;
        border-bottom: 1px solid #eee;
    }
    .notification-item:last-child {
        border-bottom: none;
    }
    .notification-item:hover {
        background-color: #f8f9fa;
    }
    .notification-btn {
        font-size: 16px;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background-color 0.2s;
        position: relative;
    }
    .notification-btn:hover {
        background-color: rgba(0,0,0,0.05);
    }
    .notification-wrapper {
        position: relative;
        margin-right: 10px;
    }
    .dropdown-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #eee;
        font-weight: 600;
        padding: 10px 15px;
    }
    .notification-empty {
        padding: 20px;
        text-align: center;
        color: #6c757d;
    }
    .notification-content {
        max-height: 300px;
        overflow-y: auto;
    }
    .notification-item-content {
        margin-right: 10px;
    }
    .notification-item-title {
        font-weight: 500;
        margin-bottom: 2px;
    }
    .notification-item-info {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 2px;
    }
</style>

<div class="notification-wrapper">
    <button class="notification-btn btn btn-link text-body p-0" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell"></i>
        <span class="notification-badge" id="notificationCount"></span>
    </button>
    
    <div class="dropdown-menu notification-dropdown" aria-labelledby="notificationDropdown">
        <div class="dropdown-header">Yêu cầu hủy đơn hàng</div>
        <div class="notification-content" id="notificationList"></div>
    </div>
</div>

<script>
    // Hàm lấy danh sách yêu cầu hủy đơn
    function fetchCancellationRequests() {
        fetch('/admin/orders/cancellation-requests')
            .then(response => response.json())
            .then(data => {
                const notificationList = document.getElementById('notificationList');
                const notificationCount = document.getElementById('notificationCount');
                
                // Cập nhật số lượng thông báo
                notificationCount.textContent = data.length;
                if (data.length === 0) {
                    notificationCount.style.display = 'none';
                } else {
                    notificationCount.style.display = 'flex';
                }

                // Cập nhật danh sách thông báo
                notificationList.innerHTML = '';
                if (data.length === 0) {
                    notificationList.innerHTML = '<div class="notification-empty">Không có yêu cầu hủy đơn nào</div>';
                } else {
                    data.forEach(request => {
                        const item = document.createElement('div');
                        item.className = 'notification-item';
                        item.innerHTML = `
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="notification-item-content">
                                    <div class="notification-item-title">Đơn hàng #${request.order.order_number}</div>
                                    <div class="notification-item-info">Khách hàng: ${request.order.user_name}</div>
                                    <div class="notification-item-info">${request.created_at}</div>
                                </div>
                                <button class="btn btn-sm btn-danger px-2 py-1" onclick="confirmCancelOrder(${request.order.id})">
                                    Xác nhận
                                </button>
                            </div>
                        `;
                        notificationList.appendChild(item);
                    });
                }
            });
    }

    // Gọi API mỗi 30 giây
    fetchCancellationRequests();
    setInterval(fetchCancellationRequests, 30000);

    // Hàm xác nhận hủy đơn
    function confirmCancelOrder(orderId) {
        if (confirm('Bạn có chắc chắn muốn xác nhận hủy đơn hàng này?')) {
            fetch(`/admin/orders/${orderId}/confirm-cancel`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Đã xác nhận hủy đơn hàng');
                    fetchCancellationRequests(); // Cập nhật lại danh sách
                } else {
                    alert('Có lỗi xảy ra: ' + data.message);
                }
            });
        }
    }
</script> 