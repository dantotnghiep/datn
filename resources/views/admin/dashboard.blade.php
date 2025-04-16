@extends('admin.layouts.master')

@section('content')
<div class="cr-main-content">
    <div class="container-fluid">

        <div class="cr-page-title">
            <div class="cr-breadcrumb">
                <h5>eCommerce</h5>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li>eCommerce</li>
                </ul>
            </div>
            <div class="cr-tools">
                <div class="date-picker-container">
                    <div class="cr-date-range" title="Date">
                        <i class="ri-calendar-line"></i>
                        <span>{{ now()->format('M d, Y') }} - {{ now()->format('M d, Y') }}</span>
                    </div>
                    <div class="date-range-dropdown">
                        <ul>
                            <li><button class="active">Today</button></li>
                            <li><button>Yesterday</button></li>
                            <li><button>Last 7 Days</button></li>
                            <li><button>Last 30 Days</button></li>
                            <li><button>This Month</button></li>
                            <li><button>Last Month</button></li>
                            <li><button>Custom Range</button></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($error))
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
            </div>
        </div>
        @endif

        @if(isset($usingDemoData) && $usingDemoData)
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning">
                    <i class="ri-information-line"></i> Đang hiển thị một số dữ liệu mẫu do chưa có đủ dữ liệu thực tế.
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-xl-6 col-md-4">
                <div class="cr-card metric-card">
                    <div class="cr-card-content">
                        <div class="metric-icon customer-icon">
                            <i class="ri-shield-user-line"></i>
                        </div>
                        <div class="metric-details">
                            <h6>Customers</h6>
                            <h3>{{ number_format($totalCustomers) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-4">
                <div class="cr-card metric-card">
                    <div class="cr-card-content">
                        <div class="metric-icon order-icon">
                            <i class="ri-shopping-bag-3-line"></i>
                        </div>
                        <div class="metric-details">
                            <h6>Orders</h6>
                            <h3>{{ number_format($totalOrders) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-4">
                <div class="cr-card metric-card">
                    <div class="cr-card-content">
                        <div class="metric-icon revenue-icon">
                            <i class="ri-money-dollar-circle-line"></i>
                        </div>
                        <div class="metric-details">
                            <h6>Actual Revenue</h6>
                            <h3>${{ number_format($totalRevenue, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-4">
                <div class="cr-card metric-card">
                    <div class="cr-card-content">
                        <div class="metric-icon cancel-icon">
                            <i class="ri-close-circle-line"></i>
                        </div>
                        <div class="metric-details">
                            <h6>Cancelled Orders</h6>
                            <h3>${{ number_format($totalCancelled, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-4">
                <div class="cr-card metric-card">
                    <div class="cr-card-content">
                        <div class="metric-icon pending-icon">
                            <i class="ri-time-line"></i>
                        </div>
                        <div class="metric-details">
                            <h6>Pending Revenue</h6>
                            <h3>${{ number_format($pendingRevenue, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>

        <div class="row">
            <div class="col-xxl-8 col-xl-12">
                <div class="cr-card revenue-overview">
                    <div class="cr-card-header header-575">
                        <h4 class="cr-card-title">Revenue Overview</h4>
                        <div class="header-tools">
                            <a href="javascript:void(0)" class="m-r-10 cr-full-card" title="Full Screen">
                                <i class="ri-fullscreen-line"></i>
                            </a>
                            <div class="cr-date-range date">
                                <span>{{ now()->format('M d, Y') }} - {{ now()->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="cr-card-content">
                        <div class="cr-chart-header">
                            <div class="block">
                                <h6>Orders</h6>
                                <h5>{{ $totalOrders }}</h5>
                            </div>
                            <div class="block">
                                <h6>Actual Revenue</h6>
                                <h5>{{ $totalRevenue < 0 ? '-' : '' }}${{ number_format(abs($totalRevenue/1000), 1) }}k
                                </h5>
                            </div>
                            <div class="block">
                                <h6>Pending Revenue</h6>
                                <h5>{{ $pendingRevenue < 0 ? '-' : '' }}${{ number_format(abs($pendingRevenue/1000), 1) }}k
                                </h5>
                            </div>
                            
                        </div>
                        <div class="cr-chart-content">
                            <div id="revenueChart" style="min-height: 365px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-6 col-md-12">
                <div class="cr-card" id="daily-revenue">
                    <div class="cr-card-header">
                        <h4 class="cr-card-title">Daily Revenue Analysis</h4>
                        <div class="header-tools">
                            <div class="cr-date-range dots">
                                <span>{{ now()->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="cr-card-content">
                        <div class="cr-chart-content">
                            <div id="dailyRevenueChart" style="min-height: 350px; width: 100%;"></div>
                        </div>
                        <div class="cr-chart-header-2">
                            <div class="block">
                                <h6>Peak Day</h6>
                                <h5><span id="peak-day"></span> <span id="peak-value" class="value"></span></h5>
                            </div>
                            <div class="block">
                                <h6>Daily Avg</h6>
                                <h5><span id="average-revenue" class="value"></span></h5>
                            </div>
                            <div class="block">
                                <h6>Lowest Day</h6>
                                <h5><span id="lowest-day"></span> <span id="lowest-value" class="value"></span></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-6 col-xl-12">
                <div class="cr-card" id="best_seller_tbl">
                    <div class="cr-card-header">
                        <h4 class="cr-card-title">Best Sellers</h4>
                        <div class="header-tools">
                            <a href="javascript:void(0)" class="m-r-10 cr-full-card" title="Full Screen"><i
                                    class="ri-fullscreen-line"></i></a>
                            <div class="cr-date-range dots">
                                <span></span>
                            </div>
                        </div>
                    </div>
                    <div class="cr-card-content card-default">
                        <div class="best-seller-table">
                            <div class="table-responsive">
                                <table id="best_seller_data_table" class="table">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Products</th>
                                            <th>Stock</th>
                                            <th>Sales</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bestSellers as $category)
                                        <tr>
                                            <td>
                                                <img class="cat-thumb"
                                                    src="{{ $category->image ?? '/be/assets/img/clients/1.jpg' }}"
                                                    alt="Category Image">
                                                <span class="name">{{ $category->name }}</span>
                                            </td>
                                            <td>
                                                <span class="cat">
                                                    @foreach($category->products->take(3) as $product)
                                                    <a
                                                        href="{{ route('admin.product.edit', $product->id) }}">{{ $product->name }}</a>
                                                    @endforeach
                                                </span>
                                            </td>
                                            <td>{{ $category->products->sum(function($product) { 
                                                    return $product->variations->sum('stock'); 
                                                }) }}</td>
                                            <td>${{ number_format($category->sales_count * 100, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6 col-xl-12">
                <div class="cr-card" id="top_product_tbl">
                    <div class="cr-card-header">
                        <h4 class="cr-card-title">Top Products</h4>
                        <div class="header-tools">
                            <a href="javascript:void(0)" class="m-r-10 cr-full-card" title="Full Screen"><i
                                    class="ri-fullscreen-line"></i></a>
                            <div class="cr-date-range dots">
                                <span></span>
                            </div>
                        </div>
                    </div>
                    <div class="cr-card-content card-default">
                        <div class="top-product-table">
                            <div class="table-responsive">
                                <table id="top_product_data_table" class="table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Orders</th>
                                            <th>Stock</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topProducts as $product)
                                        <tr>
                                            <td>
                                                <img class="cat-thumb"
                                                    src="{{ asset(Storage::url($product->images->first()->url ?? '')) ?? '/be/assets/img/product/1.jpg' }}"
                                                    alt="Product Image">
                                                <span class="name">{{ $product->name }}</span>
                                            </td>
                                            <td>${{ number_format($product->variations->min('price'), 2) }}</td>
                                            <td>{{ $product->ordered_count }}</td>
                                            <td>{{ $product->variations->sum('stock') }}</td>
                                            <td>${{ number_format($product->ordered_count * $product->variations->min('price'), 2) }}
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
        </div>
        <div class="row">
            <div class="col-xxl-12 col-xl-12">
                <div class="cr-card" id="status-revenue">
                    <div class="cr-card-header">
                        <h4 class="cr-card-title">Doanh số theo trạng thái đơn hàng</h4>
                        <div class="header-tools">
                            <a href="javascript:void(0)" class="m-r-10 cr-full-card" title="Full Screen"><i
                                    class="ri-fullscreen-line"></i></a>
                        </div>
                    </div>
                    <div class="cr-card-content">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Trạng thái</th>
                                                <th>Số lượng đơn hàng</th>
                                                <th>Doanh số</th>
                                                <th>Tỷ lệ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $totalAmount = array_sum(array_column($revenueByStatus, 'amount'));
                                            $totalCount = array_sum(array_column($revenueByStatus, 'count'));
                                            @endphp
                                            @foreach($orderStatuses as $status)
                                            @php
                                            $statusData = $revenueByStatus[$status->id];
                                            $percentage = $totalAmount > 0 ? ($statusData['amount'] / $totalAmount) *
                                            100 : 0;
                                            $countPercentage = $totalCount > 0 ? ($statusData['count'] / $totalCount) *
                                            100 : 0;
                                            @endphp
                                            <tr>
                                                <td>{{ $status->id }}</td>
                                                <td>{{ $status->status_name }}</td>
                                                <td>
                                                    {{ $statusData['count'] }}
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: {{ $countPercentage }}%;"
                                                            aria-valuenow="{{ $countPercentage }}" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                                <td>${{ number_format($statusData['amount'], 2) }}</td>
                                                <td>
                                                    {{ number_format($percentage, 1) }}%
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: {{ $percentage }}%;"
                                                            aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                            <tr class="table-active">
                                                <td colspan="2"><strong>Tổng cộng</strong></td>
                                                <td><strong>{{ $totalCount }}</strong></td>
                                                <td><strong>${{ number_format($totalAmount, 2) }}</strong></td>
                                                <td><strong>100%</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div id="statusPieChart" style="min-height: 350px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-12 col-xl-12">
                <div class="cr-card" id="ordertbl">
                    <div class="cr-card-header">
                        <h4 class="cr-card-title">Recent Orders</h4>
                        <div class="header-tools">
                            <a href="javascript:void(0)" class="m-r-10 cr-full-card" title="Full Screen"><i
                                    class="ri-fullscreen-line"></i></a>
                            <div class="cr-date-range dots">
                                <span></span>
                            </div>
                        </div>
                    </div>
                    <div class="cr-card-content card-default">
                        <div class="order-table">
                            <div class="table-responsive">
                                <table id="recent_order_data_table" class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Product</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentOrders as $order)
                                        <tr>
                                            <td class="token">{{ $order->order_code }}</td>
                                            <td>
                                                @if($order->items->isNotEmpty() && $order->items->first()->variation &&
                                                $order->items->first()->variation->product)
                                                <img class="cat-thumb"
                                                    src="{{ asset(Storage::url($order->items->first()->variation->product->images->first()->url ?? '')) ?? '/be/assets/img/product/1.jpg' }}"
                                                    alt="Product Image">
                                                <span
                                                    class="name">{{ $order->items->first()->variation->product->name ?? 'Product' }}</span>
                                                @else
                                                <span class="name">No product details</span>
                                                @endif
                                            </td>
                                            <td>{{ $order->user_name ?? ($order->user->name ?? 'Guest') }}</td>
                                            <td>${{ number_format($order->total_amount, 2) }}</td>
                                            <td class="{{ strtolower($order->status->status_name ?? 'unknown') }}">
                                                {{ $order->status->status_name ?? 'Unknown' }}
                                            </td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Lấy dữ liệu thực tế từ PHP
const realData = {
    totalCustomers: {{ $totalCustomers ?? 0 }},
    totalOrders: {{ $totalOrders ?? 0 }},
    totalRevenue: {{ $totalRevenue ?? 0 }},
    totalRefunds: {{ $totalRefunds ?? 0 }},
    totalCancelled: {{ $totalCancelled ?? 0 }},
    pendingRevenue: {{ $pendingRevenue ?? 0 }},
    netRevenue: {{ $netRevenue ?? 0 }},
    totalExpenses: {{ $totalExpenses ?? 0 }},
    totalProfit: {{ $totalProfit ?? 0 }},
    orderGrowth: {{ $orderGrowth ?? 0 }},
    revenueGrowth: {{ $revenueGrowth ?? 0 }},
    chartLabels: {!! isset($chartLabels) ? json_encode($chartLabels) : '[]' !!},
    revenueChartData: {!! isset($revenueChartData) ? json_encode($revenueChartData) : '[]' !!},
    orderCountChartData: {!! isset($orderCountChartData) ? json_encode($orderCountChartData) : '[]' !!},
    expenseChartData: {!! isset($expenseChartData) ? json_encode($expenseChartData) : '[]' !!},
    profitChartData: {!! isset($profitChartData) ? json_encode($profitChartData) : '[]' !!}
};

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        initCharts();
        setupDateRangePicker();

        // Cập nhật số liệu tóm tắt ban đầu từ dữ liệu thực
        updateSummaryStatistics(realData);
    }, 500); // Delay initialization to ensure all resources are loaded
});

// Cập nhật số liệu tóm tắt từ dữ liệu thực
function updateSummaryStatistics(data) {
    // Cập nhật thông tin tổng quát
    const metricCards = document.querySelectorAll('.metric-card');

    if (metricCards.length >= 6) {
        // Cập nhật Customers
        let metricDetails = metricCards[0].querySelector('.metric-details');
        metricDetails.querySelector('h3').textContent = data.totalCustomers.toLocaleString();

        // Cập nhật Orders
        metricDetails = metricCards[1].querySelector('.metric-details');
        metricDetails.querySelector('h3').textContent = data.totalOrders.toLocaleString();

        // Cập nhật Actual Revenue
        metricDetails = metricCards[2].querySelector('.metric-details');
        metricDetails.querySelector('h3').textContent = '$' + data.totalRevenue.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        // Cập nhật Cancelled Orders
        metricDetails = metricCards[3].querySelector('.metric-details');
        metricDetails.querySelector('h3').textContent = '$' + data.totalCancelled.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        // Cập nhật Pending Revenue
        metricDetails = metricCards[4].querySelector('.metric-details');
        metricDetails.querySelector('h3').textContent = '$' + data.pendingRevenue.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        // Cập nhật Net Revenue
        metricDetails = metricCards[5].querySelector('.metric-details');
        metricDetails.querySelector('h3').textContent = '$' + data.netRevenue.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // Cập nhật Revenue Overview header
    document.querySelector('.cr-chart-header .block:nth-child(1) h5').innerHTML =
        data.totalOrders.toLocaleString();

    // Cập nhật Actual Revenue
    document.querySelector('.cr-chart-header .block:nth-child(2) h5').innerHTML =
        `${data.totalRevenue < 0 ? '-' : ''}$${(Math.abs(data.totalRevenue)/1000).toFixed(1)}k`;

    // Cập nhật Pending Revenue
    document.querySelector('.cr-chart-header .block:nth-child(3) h5').innerHTML =
        `${data.pendingRevenue < 0 ? '-' : ''}$${(Math.abs(data.pendingRevenue)/1000).toFixed(1)}k`;

    // Cập nhật Net Revenue
    document.querySelector('.cr-chart-header .block:nth-child(4) h5').innerHTML =
        `${data.netRevenue < 0 ? '-' : ''}$${(Math.abs(data.netRevenue)/1000).toFixed(1)}k`;
}

// Định nghĩa data theo khoảng thời gian
const timeRangeData = {
    // FIX: Hiện tại tất cả các khoảng thời gian đều dùng cùng một dữ liệu
    // Trong môi trường sản xuất, dữ liệu sẽ được tải thông qua AJAX
    
    // Dữ liệu mặc định cho "Hôm nay"
    today: {
        totalOrders: {{ $totalOrders ?? 0 }},
        totalOrderValue: {{ $totalOrderValue ?? 0 }},
        totalRevenue: {{ $totalRevenue ?? 0 }},
        totalRefunds: {{ $totalRefunds ?? 0 }},
        totalCancelled: {{ $totalCancelled ?? 0 }},
        pendingRevenue: {{ $pendingRevenue ?? 0 }},
        netRevenue: {{ $netRevenue ?? 0 }},
        totalExpenses: {{ $totalExpenses ?? 0 }},
        totalProfit: {{ $totalProfit ?? 0 }},
        orderGrowth: {{ $orderGrowth ?? 0 }},
        revenueGrowth: {{ $revenueGrowth ?? 0 }}
    }
};

// Sao chép dữ liệu mặc định cho các khoảng thời gian khác
// Trong môi trường sản xuất, mỗi khoảng thời gian sẽ có dữ liệu riêng từ API
timeRangeData.yesterday = { ...timeRangeData.today };
timeRangeData.last7days = { ...timeRangeData.today };
timeRangeData.last30days = { ...timeRangeData.today };
timeRangeData.thisMonth = { ...timeRangeData.today };
timeRangeData.lastMonth = { ...timeRangeData.today };

function updateDateDisplay(range, displayElement) {
    const today = new Date();
    let startDate, endDate;

    switch (range) {
        case 'today':
            startDate = endDate = today;
            break;
        case 'yesterday':
            startDate = endDate = new Date(today);
            startDate.setDate(today.getDate() - 1);
            break;
        case 'last7days':
            endDate = today;
            startDate = new Date(today);
            startDate.setDate(today.getDate() - 6);
            break;
        case 'last30days':
            endDate = today;
            startDate = new Date(today);
            startDate.setDate(today.getDate() - 29);
            break;
        case 'thismonth':
            startDate = new Date(today.getFullYear(), today.getMonth(), 1);
            endDate = today;
            break;
        case 'lastmonth':
            startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            endDate = new Date(today.getFullYear(), today.getMonth(), 0);
            break;
        default:
            startDate = endDate = today;
    }

    const formatDate = (date) => {
        return date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
    };

    displayElement.textContent = `${formatDate(startDate)} - ${formatDate(endDate)}`;
}

function updateDashboardData(range) {
    // FIX: Cập nhật dữ liệu tóm tắt dựa trên khoảng thời gian đã chọn
    updateSummaryStatistics(timeRangeData[range] || timeRangeData['today']);
    
    // Hiển thị thông báo cho người dùng về việc API chưa được triển khai
    const notification = document.createElement('div');
    notification.className = 'alert alert-info alert-dismissible fade show';
    notification.innerHTML = `
        <strong>Thông báo:</strong> Đã chọn dữ liệu cho khoảng thời gian: <strong>${range}</strong>. 
        Trong phiên bản hoàn chỉnh, dữ liệu sẽ được tải qua API.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Chèn thông báo vào trang
    const existingNotification = document.querySelector('.alert-info');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    document.querySelector('.cr-page-title').insertAdjacentElement('afterend', notification);
    
    // Tự động ẩn thông báo sau 5 giây
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 5000);

    // Lưu ý: Trong phiên bản hoàn chỉnh, chúng ta sẽ gửi AJAX request để lấy dữ liệu thực tế
    // cho khoảng thời gian đã chọn, sau đó cập nhật lại biểu đồ
    // 
    // Ví dụ:
    // $.ajax({
    //     url: '/admin/dashboard/data',
    //     data: { range: range },
    //     success: function(response) {
    //         // Cập nhật biểu đồ với dữ liệu mới từ response
    //         updateSummaryStatistics(response.summary);
    //         // Cập nhật các biểu đồ
    //         if (window.revenueChart) {
    //             window.revenueChart.updateOptions({
    //                 series: [{...}],
    //                 xaxis: {categories: response.chartLabels}
    //             });
    //         }
    //     }
    // });
}

function initRevenueChart() {
    let options = {
        series: [{
                name: 'Gross Revenue',
                type: 'line',
                data: realData.revenueChartData.map(val => parseFloat(val))
            },
            {
                name: 'Net Revenue',
                type: 'line',
                data: realData.revenueChartData.map((val, index) => {
                    // Calculate net revenue (revenue minus expense)
                    return Math.max(0, parseFloat(val) - parseFloat(realData.expenseChartData[index] ||
                        0));
                })
            },
            {
                name: 'Orders',
                type: 'line',
                data: realData.orderCountChartData.map(val => parseInt(val))
            }
        ],
        chart: {
            height: 350,
            type: 'line',
            stacked: false,
            toolbar: {
                show: false
            },
            fontFamily: 'Inter, sans-serif',
            background: 'transparent'
        },
        colors: ['#6571ff', '#4bc0c0', '#36cfff'],
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                if (typeof val === 'number') {
                    return val.toFixed(1);
                }
                return val;
            },
            style: {
                fontSize: '10px'
            }
        },
        stroke: {
            curve: 'smooth',
            width: [3, 3, 3]
        },
        grid: {
            borderColor: '#e2e5ec',
            strokeDashArray: 4,
            xaxis: {
                lines: {
                    show: true
                }
            },
            yaxis: {
                lines: {
                    show: true
                }
            }
        },
        markers: {
            size: 4,
            hover: {
                size: 6
            }
        },
        xaxis: {
            categories: realData.chartLabels,
            labels: {
                style: {
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            title: {
                text: "Revenue ($)"
            },
            labels: {
                formatter: function(val) {
                    if (val < 0) {
                        return '-$' + Math.abs(val).toFixed(1) + 'k';
                    }
                    return '$' + val.toFixed(1) + 'k';
                }
            }
        },
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function(val, {
                    seriesIndex
                }) {
                    if (seriesIndex === 2) { // Orders series
                        return val + " orders";
                    }
                    if (val < 0) {
                        return "-$" + Math.abs(val).toLocaleString() + "k";
                    }
                    return "$" + val.toLocaleString() + "k";
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'center',
            offsetY: 0
        }
    };

    var chart = new ApexCharts(document.getElementById('revenueChart'), options);
    chart.render();
    return chart;
}

function setupDateRangePicker() {
    const dateRangeContainer = document.querySelector('.date-picker-container');
    if (dateRangeContainer) {
        const dateRangeButton = dateRangeContainer.querySelector('.cr-date-range');
        const dropdown = dateRangeContainer.querySelector('.date-range-dropdown');
        const dateRangeDisplay = dateRangeButton.querySelector('span');

        // Toggle dropdown
        dateRangeButton.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!dateRangeContainer.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Set active date range when option clicked
        const dateOptions = dropdown.querySelectorAll('button');
        dateOptions.forEach(option => {
            option.addEventListener('click', function() {
                // FIX: Chuẩn hóa range format để so khớp với thuộc tính của timeRangeData
                const rangeText = this.textContent.trim();
                const range = rangeText.toLowerCase().replace(/\s+/g, '');

                // Update active option
                dateOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');

                // Update date display
                updateDateDisplay(range, dateRangeDisplay);

                // FIX: Đảm bảo tên khoảng thời gian khớp với thuộc tính của đối tượng timeRangeData
                let timeRangeKey = range;
                if (range === 'thismonth') timeRangeKey = 'thisMonth';
                if (range === 'lastmonth') timeRangeKey = 'lastMonth';
                
                // Update dashboard data based on selected time range
                updateDashboardData(timeRangeKey); 

                // Close dropdown
                dropdown.classList.remove('show');
            });
        });
    }
}

function initCharts() {
    // Check if chart containers exist
    const revenueChartEl = document.getElementById('revenueChart');
    const dailyRevenueChartEl = document.getElementById('dailyRevenueChart');
    const statusPieChartEl = document.getElementById('statusPieChart');

    if (revenueChartEl) {
        // Initialize with default data (last 30 days)
        window.revenueChart = initRevenueChart();
    }

    if (dailyRevenueChartEl) {
        window.dailyRevenueChart = initDailyRevenueChart();
    }

    if (statusPieChartEl) {
        window.statusPieChart = initStatusPieChart();
    }
}

function initDailyRevenueChart() {
    try {
        // Sample data for daily revenue (đơn vị: nghìn đô)
        var dailyRevenueData = {!! isset($dailyRevenue) ? json_encode($dailyRevenue) : '[3100, 4200, 4500, 5200, 5700, 8200, 6500]' !!};
        var dailyOrdersData = {!! isset($dailyOrders) ? json_encode($dailyOrders) : '[12, 19, 22, 27, 30, 45, 35]' !!};
        var weekDays = {!! isset($dayNames) ? json_encode($dayNames) : '["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"]' !!};

        // Cập nhật các số liệu thống kê doanh thu theo ngày
        const peakDayIndex = dailyRevenueData.indexOf(Math.max(...dailyRevenueData));
        const lowestDayIndex = dailyRevenueData.indexOf(Math.min(...dailyRevenueData));
        const peakDay = weekDays[peakDayIndex];
        const lowestDay = weekDays[lowestDayIndex];
        const peakDayValue = dailyRevenueData[peakDayIndex];
        const lowestDayValue = dailyRevenueData[lowestDayIndex];
        const avgValue = dailyRevenueData.reduce((a, b) => a + b, 0) / dailyRevenueData.length;

        // Cập nhật thông tin hiển thị Peak Day, Daily Avg, Lowest Day
        setTimeout(() => {
            document.getElementById('peak-day').textContent = peakDay;
            document.getElementById('peak-value').textContent = '$' + peakDayValue.toLocaleString();

            document.getElementById('average-revenue').textContent = '$' + avgValue.toLocaleString();

            document.getElementById('lowest-day').textContent = lowestDay;
            document.getElementById('lowest-value').textContent = '$' + lowestDayValue.toLocaleString();
        }, 100);

        var options = {
            series: [{
                name: 'Revenue',
                type: 'line',
                data: dailyRevenueData
            }, {
                name: 'Orders',
                type: 'line',
                data: dailyOrdersData
            }],
            chart: {
                height: 320,
                type: 'line',
                stacked: false,
                toolbar: {
                    show: false
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val, {
                    seriesIndex
                }) {
                    if (seriesIndex === 0) {
                        return '$' + val.toLocaleString();
                    }
                    return val;
                },
                style: {
                    fontSize: '10px'
                }
            },
            stroke: {
                curve: 'smooth',
                width: [3, 3]
            },
            colors: ['#6571ff', '#36cfff'],
            grid: {
                borderColor: '#e2e5ec',
                strokeDashArray: 4,
                xaxis: {
                    lines: {
                        show: true
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            markers: {
                size: 4,
                hover: {
                    size: 6
                }
            },
            xaxis: {
                categories: weekDays,
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            yaxis: [{
                    title: {
                        text: "Revenue ($)"
                    },
                    labels: {
                        formatter: function(val) {
                            return '$' + val.toLocaleString();
                        }
                    }
                },
                {
                    opposite: true,
                    title: {
                        text: "Orders"
                    },
                    labels: {
                        formatter: function(val) {
                            return val;
                        }
                    }
                }
            ],
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function(val, {
                        seriesIndex
                    }) {
                        if (seriesIndex === 1) {
                            return val + ' orders';
                        }
                        return '$' + val.toLocaleString();
                    }
                }
            },
            legend: {
                position: 'top'
            }
        };

        var chart = new ApexCharts(document.getElementById('dailyRevenueChart'), options);
        chart.render();
        return chart;
    } catch (error) {
        console.error('Error initializing Daily Revenue Chart:', error);
        return null;
    }
}

function initStatusPieChart() {
    // Lấy dữ liệu doanh số theo trạng thái
    const statusData = @json($revenueByStatus);
    const orderStatuses = @json($orderStatuses);

    const series = [];
    const labels = [];
    const colors = ['#6571ff', '#4bc0c0', '#36cfff', '#ff6b6b', '#ffb800', '#2fc781', '#a55eea', '#fd7e14'];

    // Chuẩn bị dữ liệu cho biểu đồ
    orderStatuses.forEach((status, index) => {
        if (statusData[status.id] && statusData[status.id].amount > 0) {
            series.push(parseFloat(statusData[status.id].amount));
            labels.push(status.status_name);
        }
    });

    // FIX: Tính tổng doanh thu để tính toán phần trăm chính xác
    const totalAmount = series.reduce((sum, amount) => sum + amount, 0);
    
    // FIX: Tạo dữ liệu hiển thị tỉ lệ phần trăm
    const formattedLabels = labels.map((label, index) => {
        const percentage = ((series[index] / totalAmount) * 100).toFixed(1);
        return `${label} (${percentage}%)`;
    });

    let options = {
        series: series,
        chart: {
            type: 'pie',
            height: 350,
            fontFamily: 'Inter, sans-serif',
        },
        labels: formattedLabels, // FIX: Sử dụng labels đã định dạng để hiển thị phần trăm
        colors: colors,
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            fontSize: '14px',
            markers: {
                width: 12,
                height: 12,
                radius: 6,
            },
            itemMargin: {
                horizontal: 10,
                vertical: 0
            }
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    // FIX: Hiển thị cả số tiền và phần trăm trong tooltip
                    const percentage = ((val / totalAmount) * 100).toFixed(1);
                    return `$${parseFloat(val).toLocaleString()} (${percentage}% of total)`;
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function(val, opts) {
                // FIX: Hiển thị phần trăm chính xác
                const value = parseFloat(series[opts.seriesIndex]);
                const percentage = ((value / totalAmount) * 100).toFixed(1);
                return `${percentage}%`;
            },
            style: {
                fontSize: '12px',
                fontWeight: '500',
            },
            dropShadow: {
                enabled: false
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    height: 300
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    const chart = new ApexCharts(document.getElementById('statusPieChart'), options);
    chart.render();
    return chart;
}
</script>

<style>
/* Updated dashboard styles to match design in image */
.card-metrics .cr-card {
    border-radius: 8px;
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.card-metrics .cr-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.metric-card .cr-card-content {
    display: flex;
    align-items: center;
    padding: 20px;
}

.metric-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 16px;
    font-size: 1.5rem;
}

.customer-icon {
    background-color: rgba(255, 107, 107, 0.15);
    color: #ff6b6b;
}

.order-icon {
    background-color: rgba(101, 113, 255, 0.15);
    color: #6571ff;
}

.revenue-icon {
    background-color: rgba(47, 199, 129, 0.15);
    color: #2fc781;
}

.sales-icon {
    background-color: rgba(65, 105, 225, 0.15);
    color: #4169e1;
}

.cancel-icon {
    background-color: rgba(255, 107, 107, 0.15);
    color: #ff6b6b;
}

.pending-icon {
    background-color: rgba(255, 184, 0, 0.15);
    color: #ffb800;
}

.expense-icon {
    background-color: rgba(255, 184, 0, 0.15);
    color: #ffb800;
}

.refund-icon {
    background-color: rgba(255, 107, 107, 0.15);
    color: #ff6b6b;
}

.net-icon {
    background-color: rgba(75, 192, 192, 0.15);
    color: #4bc0c0;
}

.metric-details {
    flex: 1;
}

.metric-details h6 {
    color: #6c757d;
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
    font-weight: 500;
}

.metric-details h3 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #343a40;
}

.growth {
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 4px;
}

.growth.up {
    color: #2fc781;
}

.growth.down {
    color: #ff6b6b;
}

.date-picker-container {
    position: relative;
}

.cr-date-range {
    display: flex;
    align-items: center;
    cursor: pointer;
    background: white;
    padding: 6px 12px;
    border-radius: 4px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.date-range-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    width: 200px;
    z-index: 100;
    padding: 10px 0;
    display: none;
}

.date-range-dropdown.show {
    display: block;
}

.date-range-dropdown ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.date-range-dropdown li {
    padding: 0;
}

.date-range-dropdown button {
    display: block;
    width: 100%;
    text-align: left;
    padding: 8px 16px;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 14px;
    color: #495057;
    transition: all 0.2s;
}

.date-range-dropdown button:hover {
    background-color: #f8f9fa;
}

.date-range-dropdown button.active {
    background-color: #e9ecef;
    font-weight: 500;
    color: #343a40;
}

.cr-card {
    border-radius: 8px;
    overflow: hidden;
}

.cr-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    background-color: white;
    border-bottom: 1px solid #f1f3f4;
}

.cr-card-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0;
    color: #343a40;
}

#revenueChart,
#dailyRevenueChart {
    margin-top: 10px;
}

.cr-chart-header {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    padding: 15px 10px;
}

.cr-chart-header .block {
    flex: 1;
    min-width: 120px;
}

.cr-chart-header h6,
.cr-chart-header-2 h6 {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 4px;
}

.cr-chart-header h5,
.cr-chart-header-2 h5 {
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.cr-chart-header span,
.cr-chart-header-2 span {
    font-size: 0.75rem;
    margin-left: 6px;
    padding: 2px 6px;
    border-radius: 3px;
    display: inline-flex;
    align-items: center;
}

.up {
    color: #2fc781;
}

.down {
    color: #ff6b6b;
}
</style>