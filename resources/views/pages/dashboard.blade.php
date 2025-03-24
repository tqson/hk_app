@extends('layouts.app')

@section('title', 'Dashboard - HK LOVE')

@section('page-title', 'Dashboard')

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

    <!-- Filter Form -->
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

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Cảnh báo thuốc hết hạn</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="expiryFilterDropdown"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-filter fa-sm fa-fw text-gray-400 mr-1"></i>
                            <span id="currentFilter">Tháng này</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                             aria-labelledby="expiryFilterDropdown">
                            <div class="dropdown-header">Tìm kiếm theo tháng:</div>
                            <a class="dropdown-item active" href="#" data-filter="current">Tháng này</a>
                            <a class="dropdown-item" href="#" data-filter="three">3 tháng sau</a>
                            <a class="dropdown-item" href="#" data-filter="six">6 tháng sau</a>
                            <a class="dropdown-item" href="#" data-filter="expired">Đã hết hạn</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="expiryTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th style="width: 5%">STT</th>
                                <th style="width: 30%">Tên sản phẩm</th>
                                <th style="width: 10%">ĐVT</th>
                                <th style="width: 15%">Số lô</th>
                                <th style="width: 15%">Hạn sử dụng</th>
                                <th style="width: 15%">Số ngày còn lại</th>
                                <th style="width: 10%">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($expiringProducts as $index => $batch)
                                <tr class="{{ $batch->expiry_date->isPast() ? 'table-danger' : ($batch->expiry_date->diffInDays(now()) <= 30 ? 'table-warning' : '') }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $batch->product->name }}</td>
                                    <td>{{ $batch->product->unit }}</td>
                                    <td>{{ $batch->batch_number }}</td>
                                    <td>{{ $batch->expiry_date->format('d/m/Y') }}</td>
                                    <td>
                                        @if($batch->expiry_date->isPast() || $batch->expiry_date->diffInDays(now()) == 0)
                                            <span class="badge badge-danger">Đã hết hạn</span>
                                        @else
                                            <span class="badge {{ $batch->expiry_date->diffInDays(now()) <= 30 ? 'badge-warning' : 'badge-info' }}">
                                                {{ $batch->expiry_date->diffInDays(now()) }} ngày
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('products.show', $batch->product_id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Không có sản phẩm nào sắp hết hạn</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lấy các phần tử
            const monthSelect = document.getElementById('month');
            const yearSelect = document.getElementById('year');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            // Hàm định dạng ngày thành dd/mm/yyyy
            function formatDate(date) {
                const day = date.getDate().toString().padStart(2, '0');
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const year = date.getFullYear();
                return `${day}-${month}-${year}`;
            }

            // Hàm lấy ngày đầu tiên của tháng
            function getFirstDayOfMonth(year, month) {
                return new Date(year, month - 1, 1);
            }

            // Hàm lấy ngày cuối cùng của tháng
            function getLastDayOfMonth(year, month) {
                return new Date(year, month, 0);
            }

            // Hàm lấy ngày đầu tiên của năm
            function getFirstDayOfYear(year) {
                return new Date(year, 0, 1);
            }

            // Hàm lấy ngày cuối cùng của năm
            function getLastDayOfYear(year) {
                return new Date(year, 11, 31);
            }

            // Hàm cập nhật ngày dựa trên tháng và năm đã chọn
            function updateDates() {
                const selectedMonth = monthSelect.value;
                const selectedYear = yearSelect.value;

                // Nếu cả tháng và năm đều được chọn
                if (selectedMonth && selectedYear) {
                    const firstDay = getFirstDayOfMonth(selectedYear, selectedMonth);
                    const lastDay = getLastDayOfMonth(selectedYear, selectedMonth);

                    startDateInput.value = formatDate(firstDay);
                    endDateInput.value = formatDate(lastDay);
                }
                // Nếu chỉ năm được chọn
                else if (selectedYear && !selectedMonth) {
                    const firstDay = getFirstDayOfYear(selectedYear);
                    const lastDay = getLastDayOfYear(selectedYear);

                    startDateInput.value = formatDate(firstDay);
                    endDateInput.value = formatDate(lastDay);
                }
                // Nếu chỉ tháng được chọn (sử dụng năm hiện tại)
                else if (selectedMonth && !selectedYear) {
                    const currentYear = new Date().getFullYear();
                    const firstDay = getFirstDayOfMonth(currentYear, selectedMonth);
                    const lastDay = getLastDayOfMonth(currentYear, selectedMonth);

                    startDateInput.value = formatDate(firstDay);
                    endDateInput.value = formatDate(lastDay);

                    // Tự động chọn năm hiện tại trong dropdown
                    yearSelect.value = currentYear;
                }
                // Nếu không có gì được chọn, sử dụng tháng hiện tại
                else if (!selectedMonth && !selectedYear) {
                    const today = new Date();
                    const currentYear = today.getFullYear();
                    const currentMonth = today.getMonth() + 1;

                    const firstDay = getFirstDayOfMonth(currentYear, currentMonth);
                    const lastDay = getLastDayOfMonth(currentYear, currentMonth);

                    startDateInput.value = formatDate(firstDay);
                    endDateInput.value = formatDate(lastDay);
                }
            }

            // Thêm sự kiện change cho select tháng và năm
            monthSelect.addEventListener('change', updateDates);
            yearSelect.addEventListener('change', updateDates);

            // Kiểm tra nếu form được load với tháng/năm đã chọn
            if ((monthSelect.value || yearSelect.value) && (!startDateInput.value || !endDateInput.value)) {
                updateDates();
            }

            // Nếu không có ngày nào được chọn khi trang tải, đặt mặc định là tháng hiện tại
            if (!startDateInput.value && !endDateInput.value && !monthSelect.value && !yearSelect.value) {
                const today = new Date();
                const currentYear = today.getFullYear();
                const currentMonth = today.getMonth() + 1;

                // Cập nhật giá trị select
                monthSelect.value = currentMonth;
                yearSelect.value = currentYear;

                // Cập nhật ngày
                updateDates();
            }

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


        $(document).ready(function() {
            // Xử lý sự kiện khi chọn bộ lọc
            $('.dropdown-item').click(function(e) {
                e.preventDefault();

                // Cập nhật trạng thái active
                $('.dropdown-item').removeClass('active');
                $(this).addClass('active');

                // Cập nhật text hiển thị
                $('#currentFilter').text($(this).text());

                // Lấy giá trị filter
                const filter = $(this).data('filter');

                // Gọi AJAX để lấy dữ liệu mới
                $.ajax({
                    url: '{{ route("dashboard.expiry-filter") }}',
                    type: 'GET',
                    data: { filter: filter },
                    success: function(response) {
                        // Cập nhật bảng với dữ liệu mới
                        updateExpiryTable(response.data);
                    },
                    error: function(xhr) {
                        console.error('Lỗi khi lọc dữ liệu:', xhr);
                    }
                });
            });

            // Hàm cập nhật bảng
            function updateExpiryTable(data) {
                const tbody = $('#expiryTable tbody');
                tbody.empty();

                if (data.length === 0) {
                    tbody.append('<tr><td colspan="7" class="text-center">Không có sản phẩm nào sắp hết hạn</td></tr>');
                    return;
                }

                data.forEach((batch, index) => {
                    const isPast = new Date(batch.expiry_date) < new Date();
                    const daysLeft = batch.days_left;
                    const rowClass = isPast ? 'table-danger' : (daysLeft <= 30 ? 'table-warning' : '');

                    const badgeClass = isPast ? 'badge-danger' : (daysLeft <= 30 ? 'badge-warning' : 'badge-info');
                    const badgeText = isPast ? 'Đã hết hạn' : `${daysLeft} ngày`;

                    const row = `
                <tr class="${rowClass}">
                    <td>${index + 1}</td>
                    <td>${batch.product.name}</td>
                    <td>${batch.product.unit}</td>
                    <td>${batch.batch_number}</td>
                    <td>${formatDate(batch.expiry_date)}</td>
                    <td>
                        <span class="badge ${badgeClass}">
                            ${badgeText}
                        </span>
                    </td>
                    <td>
                        <a href="/products/${batch.product_id}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
            `;

                    tbody.append(row);
                });
            }

            // Hàm format ngày tháng
            function formatDate(dateString) {
                const date = new Date(dateString);
                const day = date.getDate().toString().padStart(2, '0');
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const year = date.getFullYear();
                return `${day}/${month}/${year}`;
            }
        });
    </script>

@endsection
