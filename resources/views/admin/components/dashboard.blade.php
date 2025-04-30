@extends('admin.master')

@section('content')
<div class="content">
    <!-- Header Stats Cards -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="mb-8">
                <h2 class="mb-2">Dashboard</h2>
                <h5 class="text-body-tertiary fw-semibold">Tổng quan về hoạt động kinh doanh của bạn</h5>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="fs-4 text-primary">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <h5 class="card-title mb-0 ms-2">Đơn hàng</h5>
                        </div>
                        <div class="badge bg-primary-subtle text-primary rounded-pill">{{ $newOrders }} mới</div>
                    </div>
                    <h2 class="mb-0">{{ number_format($totalOrders) }}</h2>
                    <p class="card-text text-body-tertiary">Tổng số đơn hàng</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="fs-4 text-success">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <h5 class="card-title mb-0 ms-2">Doanh thu</h5>
                        </div>
                    </div>
                    <h2 class="mb-0">{{ number_format($totalRevenue, 0, ',', '.') }}đ</h2>
                    <p class="card-text text-body-tertiary">Tổng doanh thu đơn hoàn thành</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="fs-4 text-warning">
                                <i class="fas fa-users"></i>
                            </div>
                            <h5 class="card-title mb-0 ms-2">Khách hàng</h5>
                        </div>
                    </div>
                    <h2 class="mb-0">{{ number_format($totalUsers) }}</h2>
                    <p class="card-text text-body-tertiary">Tổng số người dùng</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="fs-4 text-info">
                                <i class="fas fa-box"></i>
                            </div>
                            <h5 class="card-title mb-0 ms-2">Sản phẩm</h5>
                        </div>
                        <div class="badge bg-danger-subtle text-danger rounded-pill">{{ $lowStockProducts }} sắp hết</div>
                    </div>
                    <h2 class="mb-0">{{ number_format($totalProducts) }}</h2>
                    <p class="card-text text-body-tertiary">Tổng số sản phẩm</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row - Order Stats and Revenue Charts -->
    <div class="row mb-5">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom border-light">
                    <h5 class="mb-0">Doanh thu thực tế vs Dự kiến</h5>
                </div>
                <div class="card-body">
                    <div style="height: 350px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom border-light">
                    <h5 class="mb-0">Trạng thái đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div style="height: 300px;" class="d-flex justify-content-center align-items-center">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                    <div class="mt-3">
                        @foreach($orderStats as $stat)
                        <div class="d-flex align-items-center mb-2">
                            <div class="bullet-item bg-{{ $stat->status == 'Completed' ? 'success' : ($stat->status == 'Pending' ? 'warning' : ($stat->status == 'Cancelled' ? 'danger' : 'primary')) }} me-2" style="width:8px;height:8px;border-radius:50%;"></div>
                            <h6 class="text-body fw-semibold flex-1 mb-0">{{ $stat->status }}</h6>
                            <h6 class="text-body fw-semibold mb-0">{{ $stat->percentage }}%</h6>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Third Row - Top Products and Categories -->
    <div class="row mb-5">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom border-light">
                    <h5 class="mb-0">Top 5 sản phẩm bán chạy</h5>
                </div>
                <div class="card-body">
                    <div style="height: 250px;">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom border-light">
                    <h5 class="mb-0">Doanh thu theo danh mục</h5>
                </div>
                <div class="card-body">
                    <div style="height: 250px;">
                        <canvas id="categoryRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom border-light">
                    <h5 class="mb-0">Phân loại khách hàng</h5>
                </div>
                <div class="card-body">
                    <div style="height: 250px;">
                        <canvas id="customerTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fourth Row - Top Categories and Recent Orders -->
    <div class="row mb-5">
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom border-light">
                    <h5 class="mb-0">Top danh mục sản phẩm</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Danh mục</th>
                                    <th class="text-center">Số sản phẩm</th>
                                    <th class="text-center">Tồn kho</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topCategories as $category)
                                <tr>
                                    <td class="fw-bold">{{ $category['name'] }}</td>
                                    <td class="text-center">{{ number_format($category['products_count']) }}</td>
                                    <td class="text-center">{{ number_format($category['products_sum_stock']) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom border-light">
                    <h5 class="mb-0">Đơn hàng gần đây</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Khách hàng</th>
                                    <th class="text-center">Số sản phẩm</th>
                                    <th class="text-end">Tổng tiền</th>
                                    <th class="text-center">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>#{{ $order->order_number }}</td>
                                    <td>{{ $order->user_name ?? 'Khách vãng lai' }}</td>
                                    <td class="text-center">{{ $order->items->count() }}</td>
                                    <td class="text-end">{{ number_format($order->total, 0, ',', '.') }}đ</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $order->status->name == 'Completed' ? 'success' : ($order->status->name == 'Pending' ? 'warning' : ($order->status->name == 'Cancelled' ? 'danger' : 'primary')) }}">
                                            {{ $order->status->name }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fifth Row - Inventory Trend and Customer Purchase Data -->
    <div class="row mb-5">
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom border-light">
                    <h5 class="mb-0">Xu hướng tồn kho và bán hàng</h5>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="inventoryTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom border-light">
                    <h5 class="mb-0">Phân tích mua hàng</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <div class="fw-semibold">Giá trị đơn hàng trung bình</div>
                            <div class="fw-bold text-success">{{ number_format($userPurchaseData['avgOrderValue'], 0, ',', '.') }}đ</div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <div class="fw-semibold">Đơn hàng trong ngày</div>
                            <div class="fw-bold">{{ $userPurchaseData['orderFrequency']['daily'] }}</div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <div class="fw-semibold">Đơn hàng trong tuần</div>
                            <div class="fw-bold">{{ $userPurchaseData['orderFrequency']['weekly'] }}</div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <div class="fw-semibold">Đơn hàng trong tháng</div>
                            <div class="fw-bold">{{ $userPurchaseData['orderFrequency']['monthly'] }}</div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3">Top khách hàng</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Tên khách hàng</th>
                                    <th class="text-center">Số đơn</th>
                                    <th class="text-end">Tổng chi tiêu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userPurchaseData['topCustomers'] as $customer)
                                <tr>
                                    <td>{{ $customer->name }}</td>
                                    <td class="text-center">{{ $customer->order_count }}</td>
                                    <td class="text-end">{{ number_format($customer->total_spent, 0, ',', '.') }}đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sixth Row - Marketing Metrics and Additional Business Insights -->
    <div class="row mb-5">
        <!-- Khuyến mãi đang hoạt động -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom border-light">
                    <h5 class="mb-0">Khuyến mãi đang hoạt động</h5>
                </div>
                <div class="card-body">
                    @if(isset($activePromotionsList) && count($activePromotionsList) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã KM</th>
                                        <th>Tên khuyến mãi</th>
                                        <th class="text-center">Giảm giá</th>
                                        <th class="text-center">Ngày kết thúc</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activePromotionsList as $promotion)
                                    <tr>
                                        <td class="fw-bold">{{ $promotion->code }}</td>
                                        <td>{{ $promotion->name }}</td>
                                        <td class="text-center">{{ $promotion->discount_type == 'percentage' ? $promotion->discount_value . '%' : number_format($promotion->discount_value, 0, ',', '.') . 'đ' }}</td>
                                        <td class="text-center">{{ date('d/m/Y', strtotime($promotion->expires_at)) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3 fs-1 text-body-tertiary">
                                <i class="fas fa-tag"></i>
                            </div>
                            <h6 class="mb-0">Không có khuyến mãi đang hoạt động</h6>
                            <p class="text-body-tertiary mt-2">Tạo khuyến mãi để thu hút khách hàng ngay bây giờ</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sản phẩm sắp hết hàng -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom border-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sản phẩm sắp hết hàng</h5>
                    <span class="badge bg-danger rounded-pill">{{ $lowStockProducts }} sản phẩm</span>
                </div>
                <div class="card-body">
                    @if(isset($lowStockProductsList) && count($lowStockProductsList) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th class="text-center">Biến thể</th>
                                        <th class="text-center">Tồn kho</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lowStockProductsList as $product)
                                    <tr>
                                        <td class="fw-bold">{{ $product->product_name }}</td>
                                        <td class="text-center">{{ $product->attributes }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $product->stock <= 5 ? 'bg-danger' : 'bg-warning' }}">{{ $product->stock }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $lowStockProductsList->links('pagination::bootstrap-4') }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3 fs-1 text-body-tertiary">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <h6 class="mb-0">Tất cả sản phẩm đều có đủ tồn kho</h6>
                            <p class="text-body-tertiary mt-2">Bạn không có sản phẩm nào sắp hết hàng</p>
                        </div>
                    @endif
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
        info: '#36b9cc',
        warning: '#f6c23e',
        danger: '#e74a3b',
        secondary: '#858796',
        light: '#f8f9fc',
        dark: '#5a5c69'
    };

    // 1. Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    const revenueData = @json($sixMonthsData);

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueData.map(item => item.month),
            datasets: [
                {
                    label: 'Doanh thu thực tế',
                    data: revenueData.map(item => item.actual),
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderColor: colors.primary,
                    borderWidth: 2,
                    pointBackgroundColor: colors.primary,
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.1
                },
                {
                    label: 'Doanh thu dự kiến',
                    data: revenueData.map(item => item.projected),
                    borderColor: colors.secondary,
                    borderWidth: 2,
                    borderDash: [5, 5],
                    pointBackgroundColor: colors.secondary,
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + 'đ';
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.raw.toLocaleString() + 'đ';
                        }
                    }
                }
            }
        }
    });

    // 2. Order Status Chart
    const orderStatusCtx = document.getElementById('orderStatusChart');
    const orderStatusData = @json($orderStatusData);

    new Chart(orderStatusCtx, {
        type: 'doughnut',
        data: {
            labels: orderStatusData.map(item => item.name),
            datasets: [{
                data: orderStatusData.map(item => item.value),
                backgroundColor: [
                    colors.success,
                    colors.warning,
                    colors.danger,
                    colors.info,
                    colors.secondary
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // 3. Top Products Chart
    const topProductsCtx = document.getElementById('topProductsChart');
    const topProductsData = @json($topProducts);

    new Chart(topProductsCtx, {
        type: 'bar',
        data: {
            labels: topProductsData.map(item => {
                // Truncate long product names
                let name = item.name;
                return name.length > 20 ? name.substring(0, 20) + '...' : name;
            }),
            datasets: [{
                label: 'Đã bán',
                data: topProductsData.map(item => item.value),
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

    // 4. Category Revenue Chart
    const categoryRevenueCtx = document.getElementById('categoryRevenueChart');
    const categoryRevenueData = @json($categoryRevenue);

    new Chart(categoryRevenueCtx, {
        type: 'pie',
        data: {
            labels: categoryRevenueData.map(item => item.name),
            datasets: [{
                data: categoryRevenueData.map(item => item.value),
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

    // 5. Customer Type Chart
    const customerTypeCtx = document.getElementById('customerTypeChart');
    const customerData = @json($customerData);

    new Chart(customerTypeCtx, {
        type: 'pie',
        data: {
            labels: customerData.map(item => item.name),
            datasets: [{
                data: customerData.map(item => item.value),
                backgroundColor: [
                    colors.info,
                    colors.success,
                    colors.secondary
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

    // 6. Inventory Trend Chart
    const inventoryTrendCtx = document.getElementById('inventoryTrendChart');
    const inventoryData = @json($inventoryTrend);

    new Chart(inventoryTrendCtx, {
        type: 'bar',
        data: {
            labels: inventoryData.map(item => item.month),
            datasets: [
                {
                    label: 'Nhập kho',
                    data: inventoryData.map(item => item.newStock),
                    backgroundColor: colors.info,
                    borderRadius: 4,
                    maxBarThickness: 20
                },
                {
                    label: 'Bán ra',
                    data: inventoryData.map(item => item.soldItems),
                    backgroundColor: colors.success,
                    borderRadius: 4,
                    maxBarThickness: 20
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    }
                }
            }
        }
    });
});
</script>
@endsection
