@extends('admin.layouts.master')

@section('content')


<div class="cr-main-content">
    <div class="container-fluid">

        <h1>Chi tiết khách hàng: {{ $customer->name }}</h1>

        <!-- Thông tin khách hàng -->
        <div class="card mb-4">
            <div class="card-header">Thông tin khách hàng</div>
            <div class="card-body">
                <p><strong>Tên:</strong> {{ $customer->name }}</p>
                <p><strong>Email:</strong> {{ $customer->email }}</p>
                <p><strong>Số điện thoại:</strong> {{ $customer->phone ?? 'N/A' }}</p>
                <p><strong>Trạng thái:</strong> {{ $customer->status === 'active' ? 'Hoạt động' : 'Bị khóa' }}</p>
            </div>
        </div>

        <!-- Thống kê đơn hàng -->
        <div class="row mb-4">
            <div class="col">
                <div class="card text-dark bg-primary-subtle">
                    <div class="card-body">
                        <h5 class="card-title">Đơn chờ xử lý</h5>
                        <p class="card-text">{{ $totalOrders['Pending'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col">

                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Đơn hoàn thành</h5>
                        <p class="card-text text-white">{{ $totalOrders['Completed'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Đơn đã hủy</h5>
                        <p class="card-text">{{ $totalOrders['Cancelled'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5 class="card-title">Đơn thất bại</h5>
                        <p class="card-text text-white">{{ $totalOrders['Failed'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Tổng tiền thanh toán</h5>
                        <p class="card-text">{{ number_format($totalPaid, 0, ',', '.') }} VNĐ</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ô đánh giá -->
        <div class="card mb-4">
            <div class="card-header">Đánh giá từ khách hàng</div>
            <div class="card-body">
                <p>Chưa có đánh giá nào.</p>
                <button class="btn btn-secondary" disabled>Xem chi tiết</button>
            </div>
        </div>

        <!-- Danh sách đơn hàng -->
        <div class="card mb-4">
            <div class="card-header">Danh sách đơn hàng</div>
            <div class="card-body">
                @if ($customer->orders->isEmpty())
                <p>Khách hàng này chưa có đơn hàng nào.</p>
                @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customer->orders as $order)
                        <tr>
                            <td>{{ $order->order_code }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</td>
                            <td>
                                @switch($order->status->status_name)
                                @case('Pending')
                                <span class="badge bg-secondary">Chờ xử lý</span>
                                @break
                                @case('Processing')
                                <span class="badge bg-info">Đang xử lý</span>
                                @break
                                @case('Shipping')
                                <span class="badge bg-primary">Đang giao</span>
                                @break
                                @case('Completed')
                                <span class="badge bg-success">Hoàn thành</span>
                                @break
                                @case('Failed')
                                <span class="badge bg-danger">Thất bại</span>
                                @break
                                @case('Cancelled')
                                <span class="badge bg-warning">Đã hủy</span>
                                @break
                                @endswitch
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>

        <!-- Biểu đồ -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Thống kê đơn hàng</span>
                <div>
                    <select class="form-select" onchange="location = this.value;">
                        <option value="{{ route('admin.users.clients.detail', [$customer->id, 'filter' => 'all']) }}" {{ $filter === 'all' ? 'selected' : '' }}>Tất cả</option>
                        <option value="{{ route('admin.users.clients.detail', [$customer->id, 'filter' => 'week']) }}" {{ $filter === 'week' ? 'selected' : '' }}>Tuần</option>
                        <option value="{{ route('admin.users.clients.detail', [$customer->id, 'filter' => 'month']) }}" {{ $filter === 'month' ? 'selected' : '' }}>Tháng</option>
                        <option value="{{ route('admin.users.clients.detail', [$customer->id, 'filter' => 'year']) }}" {{ $filter === 'year' ? 'selected' : '' }}>Năm</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <canvas id="orderChart" height="100"></canvas>
            </div>
        </div>

    </div>
</div>
<!-- Truyền dữ liệu từ PHP sang JavaScript -->
<script>
    var chartData = JSON.parse('{!! addslashes(json_encode($chartData)) !!}');
</script>


<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('orderChart');
        if (!ctx) {
            console.error('Canvas element #orderChart not found');
            return;
        }

        var myChart = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Completed', 'Cancelled', 'Failed'],
                datasets: [{
                    label: 'Số lượng đơn hàng',
                    data: [
                        chartData['Completed'] || 0,
                        chartData['Cancelled'] || 0,
                        chartData['Failed'] || 0
                    ],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                    borderColor: ['#28a745', '#ffc107', '#dc3545'],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endsection