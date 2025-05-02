@extends('admin.master')

@section('content')
<div class="content">
    <div class="row mb-4">
        <div class="col-12">
            <div class="mb-4">
                <h2 class="mb-2">Thống kê đơn hàng theo khách hàng</h2>
                <h5 class="text-body-tertiary fw-semibold">Tổng quan về hoạt động mua hàng của khách hàng</h5>
            </div>
        </div>
    </div>

    <!-- Chart Section - First Row -->
    <div class="row mb-4">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom border-light py-2">
                    <h6 class="mb-0">Tỷ lệ đơn hàng</h6>
                </div>
                <div class="card-body">
                    <div style="height: 250px;">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom border-light py-2">
                    <h6 class="mb-0">Thống kê theo tháng ({{ date('Y') }})</h6>
                </div>
                <div class="card-body">
                    <div style="height: 250px;">
                        <canvas id="monthlyStatsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section - Second Row -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom border-light py-2">
                    <h6 class="mb-0">Top 10 khách hàng có nhiều đơn nhất</h6>
                </div>
                <div class="card-body">
                    <div style="height: 250px;">
                        <canvas id="userOrdersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Statistics Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom border-light d-flex justify-content-between align-items-center py-2">
                    <h6 class="mb-0">Chi tiết theo tháng</h6>
                    <span class="badge bg-primary">{{ date('Y') }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-2">Tháng</th>
                                    <th class="text-center py-2">Tổng đơn</th>
                                    <th class="text-center py-2">Đơn thành công</th>
                                    <th class="text-center py-2">Đơn hủy</th>
                                    <th class="text-end py-2">Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyStats as $stat)
                                <tr>
                                    <td class="py-2">Tháng {{ $stat->month }}</td>
                                    <td class="text-center py-2">
                                        <span class="badge bg-primary">{{ $stat->total_orders }}</span>
                                    </td>
                                    <td class="text-center py-2">
                                        <span class="badge bg-success">{{ $stat->completed_orders }}</span>
                                    </td>
                                    <td class="text-center py-2">
                                        <span class="badge bg-danger">{{ $stat->cancelled_orders }}</span>
                                    </td>
                                    <td class="text-end py-2 fw-medium">{{ number_format($stat->total_revenue, 0, ',', '.') }}đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Statistics Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom border-light d-flex justify-content-between align-items-center py-2">
                    <h6 class="mb-0">Chi tiết theo khách hàng</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-2">Khách hàng</th>
                                    <th class="text-center py-2">Tổng đơn</th>
                                    <th class="text-end py-2">Tổng chi tiêu</th>
                                    <th class="text-center py-2">Trạng thái</th>
                                    <th class="text-center py-2">Đơn gần nhất</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userOrderStats as $user)
                                <tr>
                                    <td class="py-2">
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <i class="fas fa-user-circle fs-5 text-secondary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $user->name }}</div>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center py-2">
                                        <span class="badge bg-primary">{{ $user->total_orders }}</span>
                                    </td>
                                    <td class="text-end py-2 fw-medium">{{ number_format($user->total_spent, 0, ',', '.') }}đ</td>
                                    <td class="text-center py-2">
                                        @if($user->completed_orders > 0)
                                            <span class="badge bg-success">{{ $user->completed_orders }} thành công</span>
                                        @endif
                                        @if($user->cancelled_orders > 0)
                                            <span class="badge bg-danger ms-1">{{ $user->cancelled_orders }} hủy</span>
                                        @endif
                                    </td>
                                    <td class="text-center py-2">{{ date('d/m/Y', strtotime($user->last_order_date)) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center py-3">
                        {{ $userOrderStats->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Colors for charts
    const colors = {
        primary: '#4e73df',
        success: '#1cc88a',
        danger: '#e74a3b',
        warning: '#f6c23e',
        info: '#36b9cc'
    };

    // User Orders Chart
    const userData = @json($userOrderStats->take(10));
    new Chart(document.getElementById('userOrdersChart'), {
        type: 'bar',
        data: {
            labels: userData.map(user => user.name),
            datasets: [
                {
                    label: 'Đơn thành công',
                    data: userData.map(user => user.completed_orders),
                    backgroundColor: colors.success,
                    borderRadius: 4,
                    maxBarThickness: 20
                },
                {
                    label: 'Đơn đã hủy',
                    data: userData.map(user => user.cancelled_orders),
                    backgroundColor: colors.danger,
                    borderRadius: 4,
                    maxBarThickness: 20
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        padding: 10
                    }
                }
            },
            scales: {
                x: {
                    stacked: true,
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });

    // Calculate total orders by status
    const totalCompleted = userData.reduce((sum, user) => sum + user.completed_orders, 0);
    const totalCancelled = userData.reduce((sum, user) => sum + user.cancelled_orders, 0);
    const totalPending = userData.reduce((sum, user) => sum + (user.total_orders - user.completed_orders - user.cancelled_orders), 0);

    // Order Status Chart
    new Chart(document.getElementById('orderStatusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Thành công', 'Đã hủy', 'Đang xử lý'],
            datasets: [{
                data: [totalCompleted, totalCancelled, totalPending],
                backgroundColor: [colors.success, colors.danger, colors.warning],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 10,
                        font: {
                            size: 11
                        }
                    }
                }
            },
            cutout: '75%'
        }
    });

    // Monthly Statistics Chart
    const monthlyData = @json($monthlyStats);
    const monthNames = ['Th.1', 'Th.2', 'Th.3', 'Th.4', 'Th.5', 'Th.6',
                      'Th.7', 'Th.8', 'Th.9', 'Th.10', 'Th.11', 'Th.12'];

    new Chart(document.getElementById('monthlyStatsChart'), {
        type: 'line',
        data: {
            labels: monthlyData.map(item => monthNames[item.month - 1]),
            datasets: [
                {
                    label: 'Tổng đơn',
                    data: monthlyData.map(item => item.total_orders),
                    borderColor: colors.primary,
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2
                },
                {
                    label: 'Thành công',
                    data: monthlyData.map(item => item.completed_orders),
                    borderColor: colors.success,
                    backgroundColor: 'transparent',
                    borderDash: [5, 5],
                    tension: 0.4,
                    borderWidth: 2
                },
                {
                    label: 'Đã hủy',
                    data: monthlyData.map(item => item.cancelled_orders),
                    borderColor: colors.danger,
                    backgroundColor: 'transparent',
                    borderDash: [5, 5],
                    tension: 0.4,
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        padding: 10,
                        font: {
                            size: 11
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
