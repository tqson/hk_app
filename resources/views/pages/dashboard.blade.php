@extends('layouts.master')

@section('title', 'Dashboard - HK LOVE')

@section('page-title', 'Dashboard')

@section('header-actions')
    <div class="filter-form">
        <form action="{{ route('dashboard') }}" method="GET" class="d-flex align-items-center flex-wrap">
            <!-- Date range filter -->
            <div class="form-group me-2 mb-2">
                <label for="start_date" class="me-2">Từ:</label>
                <input type="text" class="form-control datepicker" id="start_date" name="start_date" value="{{ $startDate ?? '' }}">
            </div>
            <div class="form-group me-2 mb-2">
                <label for="end_date" class="me-2">Đến:</label>
                <input type="text" class="form-control datepicker" id="end_date" name="end_date" value="{{ $endDate ?? '' }}">
            </div>

            <!-- Month filter -->
            <div class="form-group me-2 mb-2">
                <label for="month" class="me-2">Tháng:</label>
                <select class="form-select" id="month" name="month">
                    <option value="">Chọn tháng</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ (request('month') == $i) ? 'selected' : '' }}>Tháng {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <!-- Year filter -->
            <div class="form-group me-2 mb-2">
                <label for="year" class="me-2">Năm:</label>
                <select class="form-select" id="year" name="year">
                    <option value="">Chọn năm</option>
                    @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}" {{ (request('year') == $i) ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <button type="submit" class="btn btn-primary mb-2">Lọc</button>
        </form>
    </div>
@endsection

@section('content')
    <div class="row">
        <!-- Today's Revenue -->
        <div class="col-md-3">
            <div class="card stats-card bg-primary">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stats-value">{{ number_format($todayRevenue ?? 0, 0, ',', '.') }}đ</div>
                    <div class="stats-label">Doanh thu hôm nay</div>
                </div>
            </div>
        </div>

        <!-- Current Week Revenue -->
        <div class="col-md-3">
            <div class="card stats-card bg-success">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                    <div class="stats-value">{{ number_format($currentWeekRevenue ?? 0, 0, ',', '.') }}đ</div>
                    <div class="stats-label">Doanh thu tuần này</div>
                </div>
            </div>
        </div>

        <!-- Current Month Revenue -->
        <div class="col-md-3">
            <div class="card stats-card bg-warning">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stats-value">{{ number_format($currentMonthRevenue ?? 0, 0, ',', '.') }}đ</div>
                    <div class="stats-label">Doanh thu tháng này</div>
                </div>
            </div>
        </div>

        <!-- Total Revenue in Date Range -->
        <div class="col-md-3">
            <div class="card stats-card bg-danger">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stats-value">{{ number_format($totalRevenue ?? 0, 0, ',', '.') }}đ</div>
                    <div class="stats-label">Tổng doanh thu</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Revenue Chart -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Doanh thu theo ngày</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="dailyRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly and Monthly Revenue Charts -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Doanh thu theo tuần</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="weeklyRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Doanh thu theo tháng</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="monthlyRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Daily Revenue Chart - Changed to bar chart
            const dailyChartCtx = document.getElementById('dailyRevenueChart').getContext('2d');
            const dailyRevenueChart = new Chart(dailyChartCtx, {
                type: 'bar', // Changed from 'line' to 'bar'
                data: {
                    labels: {!! json_encode(array_keys($chartData->toArray() ?? [])) !!},
                    datasets: [{
                        label: 'Doanh thu',
                        data: {!! json_encode(array_values($chartData->toArray() ?? [])) !!},
                        backgroundColor: 'rgba(17, 124, 192, 0.7)',
                        borderColor: '#117CC0',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
                                }
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.raw);
                                }
                            }
                        },
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Weekly Revenue Chart (unchanged, but ensuring empty data is displayed)
            const weeklyChartCtx = document.getElementById('weeklyRevenueChart').getContext('2d');
            const weeklyRevenueChart = new Chart(weeklyChartCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($weeklyChartData->toArray() ?? [])) !!},
                    datasets: [{
                        label: 'Doanh thu',
                        data: {!! json_encode(array_values($weeklyChartData->toArray() ?? [])) !!},
                        backgroundColor: 'rgba(105, 219, 124, 0.7)',
                        borderColor: '#40c057',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
                                }
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.raw);
                                }
                            }
                        },
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Monthly Revenue Chart (unchanged, but ensuring empty data is displayed)
            const monthlyChartCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
            const monthlyRevenueChart = new Chart(monthlyChartCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($monthlyChartData->toArray() ?? [])) !!},
                    datasets: [{
                        label: 'Doanh thu',
                        data: {!! json_encode(array_values($monthlyChartData->toArray() ?? [])) !!},
                        backgroundColor: 'rgba(255, 212, 59, 0.7)',
                        borderColor: '#fab005',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
                                }
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.raw);
                                }
                            }
                        },
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
@endsection
