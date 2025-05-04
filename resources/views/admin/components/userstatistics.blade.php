@extends('admin.master')

@section('content')
<div class="content">
    <div class="row mb-4">
        <div class="col-12">
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2">Thống kê đơn hàng theo khách hàng</h2>
                    <h5 class="text-body-tertiary fw-semibold">Tổng quan về hoạt động mua hàng của khách hàng</h5>
                </div>
                <div class="time-filter">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="date-range d-flex align-items-center">
                                    <div class="me-2">
                                        <label for="start_date" class="form-label mb-0">Từ ngày:</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control form-control-sm" value="{{ $startDate->format('Y-m-d') }}">
                                    </div>
                                    <div class="me-2">
                                        <label for="end_date" class="form-label mb-0">Đến ngày:</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control form-control-sm" value="{{ $endDate->format('Y-m-d') }}">
                                    </div>
                                    <div class="d-flex align-items-end">
                                        <button id="apply_date_filter" class="btn btn-sm btn-primary">Áp dụng</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading indicator -->
    <div id="loading_overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.7); z-index: 9999;">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Đang tải...</span>
            </div>
        </div>
    </div>

    <!-- Stats Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Tổng số đơn hàng</h6>
                    <div class="d-flex align-items-center">
                        <h3 class="mb-0" id="total_orders">{{ $totalOrders }}</h3>
                        <div class="ms-2">
                            <span class="badge bg-primary-subtle text-primary">{{ $newOrders }} mới</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Tổng doanh thu</h6>
                    <h3 class="mb-0 text-primary" id="total_revenue">{{ number_format($totalRevenue, 0, ',', '.') }}đ</h3>
                </div>
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
                    <h6 class="mb-0" id="stats_time_period">Thống kê theo {{ $periodTitle }}</h6>
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
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom border-light py-2">
                    <h6 class="mb-0">Top 5 sản phẩm bán chạy</h6>
                </div>
                <div class="card-body">
                    <div style="height: 250px;">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom border-light py-2">
                    <h6 class="mb-0">Doanh thu theo danh mục</h6>
                </div>
                <div class="card-body">
                    <div style="height: 250px;">
                        <canvas id="categoryRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Chart Section - Third Row -->
    <div class="row mb-4">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom border-light py-2">
                    <h6 class="mb-0">Phân loại khách hàng</h6>
                </div>
                <div class="card-body">
                    <div style="height: 250px;">
                        <canvas id="customerTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom border-light py-2">
                    <h6 class="mb-0">Thống kê khách hàng</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center p-3">
                                <h3 class="text-primary mb-1">{{ $customerStats['newCustomers'] }}</h3>
                                <p class="mb-0 text-muted">Khách hàng mới</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3">
                                <h3 class="text-success mb-1">{{ $customerStats['activeCustomers'] }}</h3>
                                <p class="mb-0 text-muted">Khách hàng hoạt động</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3">
                                <h3 class="text-info mb-1">{{ $customerStats['returningCustomers'] }}</h3>
                                <p class="mb-0 text-muted">Khách hàng quay lại</p>
                            </div>
                        </div>
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
                    <h6 class="mb-0">Chi tiết theo thời gian</h6>
                    <span class="badge bg-primary" id="stats_period_badge">{{ $periodTitle }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="time_period_stats_table">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-2">Thời gian</th>
                                    <th class="text-center py-2">Tổng đơn</th>
                                    <th class="text-center py-2">Đơn thành công</th>
                                    <th class="text-center py-2">Đơn hủy</th>
                                    <th class="text-end py-2">Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dailyStats as $stat)
                                <tr>
                                    <td class="py-2">{{ $stat->label }}</td>
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
                        <table class="table table-hover mb-0" id="user_stats_table">
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
                    <div class="d-flex justify-content-center py-3" id="pagination_container">
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

    // Biểu đồ tỷ lệ đơn hàng
    let orderStatusChart;
    
    function initOrderStatusChart(dataArray) {
        const ctx = document.getElementById('orderStatusChart');
        if (orderStatusChart) {
            orderStatusChart.destroy();
        }
        
        orderStatusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: dataArray.map(item => item.name),
                datasets: [{
                    data: dataArray.map(item => item.value),
                    backgroundColor: [colors.success, colors.danger, colors.warning, colors.info, colors.primary],
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
    }
    
    // Biểu đồ thống kê theo thời gian
    let statsChart;
    
    function initStatsChart(chartData) {
        const ctx = document.getElementById('monthlyStatsChart');
        if (statsChart) {
            statsChart.destroy();
        }
        
        statsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.map(item => item.label),
                datasets: [
                    {
                        label: 'Tổng đơn',
                        data: chartData.map(item => item.total_orders),
                        borderColor: colors.primary,
                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2
                    },
                    {
                        label: 'Thành công',
                        data: chartData.map(item => item.completed_orders),
                        borderColor: colors.success,
                        backgroundColor: 'transparent',
                        borderDash: [5, 5],
                        tension: 0.4,
                        borderWidth: 2
                    },
                    {
                        label: 'Đã hủy',
                        data: chartData.map(item => item.cancelled_orders),
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
    }
    
    // Biểu đồ top sản phẩm
    let topProductsChart;
    
    function initTopProductsChart(products) {
        const ctx = document.getElementById('topProductsChart');
        if (topProductsChart) {
            topProductsChart.destroy();
        }
        
        topProductsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: products.map(item => {
                    // Cắt ngắn tên sản phẩm quá dài
                    let name = item.name;
                    return name.length > 20 ? name.substring(0, 20) + '...' : name;
                }),
                datasets: [{
                    label: 'Đã bán',
                    data: products.map(item => item.value),
                    backgroundColor: colors.primary,
                    borderRadius: 4,
                    maxBarThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        grid: {
                            display: false
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
    }
    
    // Biểu đồ doanh thu theo danh mục
    let categoryRevenueChart;
    
    function initCategoryRevenueChart(categories) {
        const ctx = document.getElementById('categoryRevenueChart');
        if (categoryRevenueChart) {
            categoryRevenueChart.destroy();
        }
        
        categoryRevenueChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: categories.map(item => item.name),
                datasets: [{
                    data: categories.map(item => item.value),
                    backgroundColor: [
                        colors.primary,
                        colors.success,
                        colors.info,
                        colors.warning,
                        colors.danger,
                        '#6f42c1',
                        '#20c9a6',
                        '#fd7e14'
                    ],
                    borderWidth: 1,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 15,
                            font: {
                                size: 11
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.raw.toLocaleString() + 'đ';
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Biểu đồ khách hàng
    let customerChart;
    
    function initCustomerChart(customerData) {
        const ctx = document.getElementById('customerTypeChart');
        if (customerChart) {
            customerChart.destroy();
        }
        
        customerChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: customerData.map(item => item.name),
                datasets: [{
                    data: customerData.map(item => item.value),
                    backgroundColor: [
                        colors.info,
                        colors.success,
                        colors.primary
                    ],
                    borderWidth: 1,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 15,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Khởi tạo biểu đồ ban đầu
    @if(isset($orderStats))
        initOrderStatusChart(@json($orderStats));
    @endif
    
    @if(isset($dailyStats))
        initStatsChart(@json($dailyStats));
    @endif
    
    @if(isset($topProducts))
        initTopProductsChart(@json($topProducts));
    @endif
    
    @if(isset($categoryRevenue))
        initCategoryRevenueChart(@json($categoryRevenue));
    @endif
    
    @if(isset($customerStats) && isset($customerStats['chartData']))
        initCustomerChart(@json($customerStats['chartData']));
    @endif
    
    // Xử lý sự kiện lọc theo ngày
    document.getElementById('apply_date_filter').addEventListener('click', function() {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        
        if (!startDate || !endDate) {
            alert('Vui lòng chọn khoảng thời gian');
            return;
        }
        
        loadStatistics(startDate, endDate);
    });
    
    // Hàm tải dữ liệu thống kê
    function loadStatistics(startDate, endDate) {
        // Hiển thị loading
        document.getElementById('loading_overlay').style.display = 'block';
        
        // Tạo URL với tham số lọc
        let url = '{{ route("admin.statistics") }}?';
        
        if (startDate) {
            url += 'start_date=' + startDate;
        }
        
        if (endDate) {
            url += '&end_date=' + endDate;
        }
        
        // Thực hiện AJAX request
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Cập nhật bảng dữ liệu
            document.querySelector('#user_stats_table tbody').innerHTML = data.userOrderStatsHtml;
            document.querySelector('#time_period_stats_table tbody').innerHTML = data.dailyStatsHtml;
            
            // Cập nhật thống kê tổng quan
            document.getElementById('total_orders').textContent = data.totalOrders;
            document.getElementById('total_revenue').textContent = new Intl.NumberFormat('vi-VN').format(data.totalRevenue) + 'đ';
            
            // Cập nhật tiêu đề biểu đồ và badge
            document.getElementById('stats_time_period').textContent = 'Thống kê theo ' + data.periodTitle;
            document.getElementById('stats_period_badge').textContent = data.periodTitle;
            
            // Cập nhật biểu đồ
            initOrderStatusChart(data.orderStats);
            initStatsChart(data.dailyStats);
            
            if (data.topProducts) {
                initTopProductsChart(data.topProducts);
            }
            
            if (data.categoryRevenue) {
                initCategoryRevenueChart(data.categoryRevenue);
            }
            
            if (data.customerStats && data.customerStats.chartData) {
                initCustomerChart(data.customerStats.chartData);
            }
            
            // Ẩn loading
            document.getElementById('loading_overlay').style.display = 'none';
        })
        .catch(error => {
            console.error('Error loading statistics:', error);
            document.getElementById('loading_overlay').style.display = 'none';
            alert('Có lỗi xảy ra khi tải dữ liệu. Vui lòng thử lại.');
        });
    }
});
</script>
@endsection
