// Khởi tạo date range picker
$(document).ready(function() {
    // Khởi tạo date range picker
    $('#reportrange').daterangepicker({
        startDate: moment().subtract(29, 'days'),
        endDate: moment(),
        ranges: {
           'Hôm nay': [moment(), moment()],
           'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           '7 ngày qua': [moment().subtract(6, 'days'), moment()],
           '30 ngày qua': [moment().subtract(29, 'days'), moment()],
           'Tháng này': [moment().startOf('month'), moment().endOf('month')],
           'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {
            format: 'DD/MM/YYYY',
            applyLabel: 'Áp dụng',
            cancelLabel: 'Hủy',
            customRangeLabel: 'Tùy chỉnh',
        }
    }, function(start, end, label) {
        // Gọi API khi thay đổi date range
        fetchDashboardData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
    });

    // Gọi API lần đầu với 30 ngày gần nhất
    fetchDashboardData(
        moment().subtract(29, 'days').format('YYYY-MM-DD'),
        moment().format('YYYY-MM-DD')
    );
});

// Hàm gọi API dashboard
function fetchDashboardData(startDate, endDate) {
    // Hiển thị loading
    
    $.ajax({
        url: '/api/dashboard/data',
        method: 'GET',
        data: {
            start_date: startDate,
            end_date: endDate
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Ẩn thông báo lỗi nếu có
            $('#error-alert').hide();
            
            // Kiểm tra xem dữ liệu có phải là dữ liệu mẫu không
            if (response.is_demo_data) {
                $('#demo-data-alert').show();
            } else {
                $('#demo-data-alert').hide();
            }
            
            // Cập nhật các metrics
            updateMetrics(response.metrics);
            // Cập nhật biểu đồ
            updateCharts(response.chartData);
            // Cập nhật dữ liệu theo ngày
            updateDailyData(response.dailyData);
            // Cập nhật dữ liệu theo trạng thái
            updateStatusData(response.revenueByStatus);
            // Cập nhật bảng best sellers
            updateBestSellers(response.bestSellers);
            // Cập nhật bảng top products
            updateTopProducts(response.topProducts);
            // Cập nhật bảng recent orders
            updateRecentOrders(response.recentOrders);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching dashboard data:', error);
            // Hiển thị thông báo lỗi
            $('#error-message').text('Có lỗi xảy ra khi tải dữ liệu dashboard: ' + error);
            $('#error-alert').show();
        }
    });
}

// Hàm cập nhật các metrics
function updateMetrics(metrics) {
    $('#totalCustomers').text(metrics.totalCustomers || 0);
    $('#totalOrders').text(metrics.totalOrders || 0);
    $('#totalRevenue').text(formatCurrency(metrics.totalRevenue || 0));
    $('#totalRefunds').text(formatCurrency(metrics.totalRefunds || 0));
    $('#pendingRevenue').text(formatCurrency(metrics.pendingRevenue || 0));
    $('#netRevenue').text(formatCurrency(metrics.netRevenue || 0));
    $('#totalProfit').text(formatCurrency(metrics.totalProfit || 0));
    
    // Cập nhật overview
    $('#overview-orders').text(metrics.totalOrders || 0);
    $('#overview-revenue').text('$' + ((metrics.totalRevenue || 0) / 1000).toFixed(1) + 'k');
    $('#overview-pending').text('$' + ((metrics.pendingRevenue || 0) / 1000).toFixed(1) + 'k');
}

// Hàm cập nhật biểu đồ
function updateCharts(chartData) {
    if (window.revenueChart) {
        window.revenueChart.data.labels = chartData.labels;
        window.revenueChart.data.datasets[0].data = chartData.revenue;
        window.revenueChart.data.datasets[1].data = chartData.netRevenue;
        window.revenueChart.update();
    }

    if (window.orderChart) {
        window.orderChart.data.labels = chartData.labels;
        window.orderChart.data.datasets[0].data = chartData.orders;
        window.orderChart.update();
    }
}

// Hàm cập nhật dữ liệu theo ngày
function updateDailyData(dailyData) {
    if (window.dailyRevenueChart) {
        window.dailyRevenueChart.data.labels = dailyData.labels;
        window.dailyRevenueChart.data.datasets[0].data = dailyData.revenue;
        window.dailyRevenueChart.update();
    }

    if (window.dailyOrdersChart) {
        window.dailyOrdersChart.data.labels = dailyData.labels;
        window.dailyOrdersChart.data.datasets[0].data = dailyData.orders;
        window.dailyOrdersChart.update();
    }
}

// Hàm cập nhật dữ liệu theo trạng thái
function updateStatusData(statusData) {
    Object.keys(statusData).forEach(statusId => {
        const status = statusData[statusId];
        $(`#status-${statusId}-amount`).text(formatCurrency(status.amount));
        $(`#status-${statusId}-count`).text(status.count);
    });
}

// Hàm format tiền tệ
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
} 