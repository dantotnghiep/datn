@extends('admin.layouts.master')

@section('content')


    <div class="cr-main-content">
        <div class="container-fluid">
            <div class="container mt-5">
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
                    <div class="card-header">Thống kê đơn hàng</div>
                    <div class="card-body">
                        <canvas id="orderChart" height="100"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('orderChart').getContext('2d');
            const chartData = @json($chartDataFinal);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Completed', 'Cancelled', 'Failed'],
                    datasets: [{
                        label: 'Số lượng đơn hàng',
                        data: [chartData['Completed'], chartData['Cancelled'], chartData['Failed']],
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
