<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - HK LOVE</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Flatpickr for date picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #1890ff;
            --border-radius: 2px;
            --box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f0f2f5;
            padding: 20px;
        }

        .dashboard-title {
            margin-bottom: 24px;
            color: rgba(0, 0, 0, 0.85);
        }

        .card {
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 24px;
            border: none;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #f0f0f0;
            padding: 16px 24px;
            font-weight: 500;
            color: rgba(0, 0, 0, 0.85);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-body {
            padding: 24px;
        }

        .stats-card {
            background-color: var(--primary-color);
            color: white;
        }

        .stats-card .card-body {
            padding: 24px;
        }

        .stats-value {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .stats-label {
            font-size: 14px;
            opacity: 0.85;
        }

        .filter-form {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-form .form-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 0;
        }

        .filter-form label {
            margin-bottom: 0;
            white-space: nowrap;
        }

        .form-control, .btn {
            border-radius: var(--border-radius);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(24, 144, 255, 0.2);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: #40a9ff;
            border-color: #40a9ff;
        }

        .chart-container {
            position: relative;
            height: 400px;
        }

        .period-selector {
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <h1 class="dashboard-title">Bảng điều khiển</h1>

    <!-- Summary Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card stats-card bg-primary">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stats-value">{{ number_format($todayRevenue, 0, ',', '.') }} VNĐ</div>
                    <div class="stats-label">Doanh thu hôm nay</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card bg-success">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                    <div class="stats-value">{{ number_format($currentWeekRevenue, 0, ',', '.') }} VNĐ</div>
                    <div class="stats-label">Doanh thu tuần này</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card bg-warning">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stats-value">{{ number_format($currentMonthRevenue, 0, ',', '.') }} VNĐ</div>
                    <div class="stats-label">Doanh thu tháng này</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card bg-danger">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stats-value">{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</div>
                    <div class="stats-label">Tổng doanh thu</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Báo cáo doanh thu</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard') }}" method="GET" class="filter-form">
                <div class="form-group">
                    <label for="start_date">Từ ngày:</label>
                    <input type="text" class="form-control date-picker" id="start_date" name="start_date"
                           value="{{ $startDate }}">
                </div>
                <div class="form-group">
                    <label for="end_date">Đến ngày:</label>
                    <input type="text" class="form-control date-picker" id="end_date" name="end_date"
                           value="{{ $endDate }}">
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Lọc dữ liệu</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Card -->
    <div class="row">
        <div class="col-md-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-value">{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</div>
                    <div class="stats-label">Tổng doanh thu</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Revenue Chart -->
    <div class="card">
        <div class="card-header">
            <div>
                <h5 class="mb-0">Biểu đồ doanh thu</h5>
                <small class="text-muted">Dữ liệu từ {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
                    đến {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</small>
            </div>

            <div class="period-selector">
                <select class="form-select" id="chartPeriodSelector">
                    <option value="daily">Theo ngày</option>
                    <option value="weekly">Theo tuần</option>
                    <option value="monthly">Theo tháng</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/vn.js"></script>

<script>
    // Initialize date pickers
    flatpickr(".date-picker", {
        dateFormat: "Y-m-d",
        locale: "vn"
    });

    // Chart data
    // Chart data
    const dailyData = {
        labels: {!! json_encode(array_keys($chartData->toArray())) !!},
        datasets: [{
            label: 'Doanh thu theo ngày',
            data: {!! json_encode(array_values($chartData->toArray())) !!},
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    };

    const weeklyData = {
        labels: {!! json_encode(array_keys($weeklyChartData->toArray())) !!},
        datasets: [{
            label: 'Doanh thu theo tuần',
            data: {!! json_encode(array_values($weeklyChartData->toArray())) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };

    const monthlyData = {
        labels: {!! json_encode(array_keys($monthlyChartData->toArray())) !!},
        datasets: [{
            label: 'Doanh thu theo tháng',
            data: {!! json_encode(array_values($monthlyChartData->toArray())) !!},
            backgroundColor: 'rgba(255, 159, 64, 0.6)',
            borderColor: 'rgba(255, 159, 64, 1)',
            borderWidth: 1
        }]
    };

    // Chart options
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function (value) {
                        return value.toLocaleString('vi-VN') + ' VNĐ';
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function (context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        label += new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND'
                        }).format(context.parsed.y);
                        return label;
                    }
                }
            }
        }
    };

    // Initialize chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    let revenueChart = new Chart(ctx, {
        type: 'bar',
        data: dailyData,
        options: chartOptions
    });

    function hasData(chartData) {
        return chartData.datasets[0].data.some(value => value > 0);
    }

    // Hiển thị thông báo khi không có dữ liệu
    function showNoDataMessage(ctx, message) {
        // Xóa biểu đồ hiện tại nếu có
        if (revenueChart) {
            revenueChart.destroy();
        }

        // Xóa canvas
        ctx.canvas.height = 400;

        // Vẽ thông báo
        ctx.font = '20px Arial';
        ctx.fillStyle = '#666';
        ctx.textAlign = 'center';
        ctx.fillText(message, ctx.canvas.width / 2, ctx.canvas.height / 2);
    }

    // Cập nhật hàm xử lý khi thay đổi loại biểu đồ
    document.getElementById('chartPeriodSelector').addEventListener('change', function() {
        const period = this.value;
        const ctx = document.getElementById('revenueChart').getContext('2d');

        if (revenueChart) {
            revenueChart.destroy();
        }

        let chartData;
        switch (period) {
            case 'weekly':
                chartData = weeklyData;
                break;
            case 'monthly':
                chartData = monthlyData;
                break;
            default:
                chartData = dailyData;
        }

        // Kiểm tra xem có dữ liệu không
        if (hasData(chartData)) {
            revenueChart = new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: chartOptions
            });
        } else {
            showNoDataMessage(ctx, 'Không có dữ liệu trong khoảng thời gian này');
        }
    });

    // Handle period selector change
    document.getElementById('chartPeriodSelector').addEventListener('change', function () {
        const period = this.value;

        if (revenueChart) {
            revenueChart.destroy();
        }

        let chartData;
        switch (period) {
            case 'weekly':
                chartData = weeklyData;
                break;
            case 'monthly':
                chartData = monthlyData;
                break;
            default:
                chartData = dailyData;
        }

        revenueChart = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: chartOptions
        });
    });

    document.querySelectorAll('.stats-card').forEach(card => {
        card.addEventListener('mouseenter', function () {
            this.style.transition = 'all 0.3s ease';
        });

        card.addEventListener('mouseleave', function () {
            this.style.transition = 'all 0.3s ease';
        });
    });
</script>
</body>
</html>
