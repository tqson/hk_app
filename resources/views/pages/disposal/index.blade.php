@extends('layouts.app')

@section('styles')
    <style>
        :root {
            --primary-color: #117CC0;
            --primary-hover: #0e6ba8;
            --primary-light: #e6f7ff;
            --danger-color: #ff4d4f;
            --danger-hover: #ff7875;
            --text-color: #262626;
            --text-secondary: #595959;
            --border-color: #d9d9d9;
            --border-radius: 4px;
            --box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            --background-color: #f0f2f5;
        }

        body {
            background-color: var(--background-color);
        }

        .page-header {
            margin-bottom: 24px;
            padding: 16px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 20px;
            font-weight: 500;
            color: var(--text-color);
            margin: 0;
        }

        .ant-card {
            background: #fff;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 24px;
            border: 1px solid var(--border-color);
        }

        .ant-card-head {
            border-bottom: 1px solid var(--border-color);
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ant-card-head-title {
            font-size: 16px;
            font-weight: 500;
            color: var(--text-color);
            margin: 0;
        }

        .ant-card-body {
            padding: 24px;
        }

        .ant-statistic {
            display: flex;
            align-items: center;
        }

        .ant-statistic-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-right: 16px;
            color: white;
        }

        .ant-statistic-content {
            flex: 1;
        }

        .ant-statistic-title {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 4px;
        }

        .ant-statistic-value {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-color);
        }

        .ant-btn {
            border-radius: var(--border-radius);
            font-size: 14px;
            height: 32px;
            padding: 0 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            border: 1px solid transparent;
            box-shadow: none;
            font-weight: 400;
        }

        .ant-btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .ant-btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .ant-btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
            color: white;
        }

        .ant-btn-danger:hover {
            background-color: var(--danger-hover);
            border-color: var(--danger-hover);
        }

        .ant-btn-sm {
            height: 24px;
            padding: 0 7px;
            font-size: 12px;
        }

        .ant-btn i {
            margin-right: 6px;
        }

        .ant-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        .ant-table-thead > tr > th {
            background-color: #fafafa;
            color: var(--text-secondary);
            font-weight: 500;
            text-align: left;
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
            transition: background 0.3s ease;
        }

        .ant-table-tbody > tr > td {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .ant-table-tbody > tr:hover > td {
            background-color: var(--primary-light);
        }

        .ant-table-tbody > tr:last-child > td {
            border-bottom: none;
        }

        .ant-input {
            padding: 4px 11px;
            font-size: 14px;
            line-height: 1.5;
            color: var(--text-color);
            background-color: #fff;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            transition: all 0.3s;
            height: 32px;
        }

        .ant-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(17, 124, 192, 0.2);
            outline: 0;
        }

        .ant-input-search {
            display: flex;
        }

        .ant-input-search .ant-input {
            border-radius: var(--border-radius) 0 0 var(--border-radius);
        }

        .ant-input-search .ant-btn {
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
        }

        .ant-form-item {
            margin-bottom: 16px;
        }

        .ant-form-item-label {
            margin-bottom: 8px;
        }

        .ant-form-item-label > label {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .ant-pagination {
            display: flex;
            justify-content: flex-end;
            margin-top: 16px;
        }

        .ant-pagination-item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            margin-right: 8px;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            background-color: #fff;
            transition: all 0.3s;
        }

        .ant-pagination-item a {
            color: var(--text-color);
            text-decoration: none;
        }

        .ant-pagination-item-active {
            border-color: var(--primary-color);
            background-color: var(--primary-color);
        }

        .ant-pagination-item-active a {
            color: white;
        }

        .ant-empty {
            text-align: center;
            padding: 40px 0;
        }

        .ant-empty-image {
            height: 100px;
            margin-bottom: 16px;
        }

        .ant-empty-image i {
            font-size: 64px;
            color: #d9d9d9;
        }

        .ant-empty-description {
            color: var(--text-secondary);
            font-size: 14px;
            margin-bottom: 16px;
        }

        .filter-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .date-range-filter {
            display: flex;
            align-items: center;
        }

        .date-range-filter .ant-form-item {
            margin-bottom: 0;
            margin-right: 16px;
            display: flex;
            align-items: center;
        }

        .date-range-filter label {
            margin-right: 8px;
            margin-bottom: 0;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 12px;
            font-weight: 500;
            border-radius: 10px;
        }

        .badge-primary {
            background-color: var(--primary-light);
            color: var(--primary-color);
        }

        .badge-danger {
            background-color: #fff1f0;
            color: var(--danger-color);
        }

        .text-danger {
            color: var(--danger-color) !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Danh sách phiếu xuất hủy</h1>
            <a href="{{ route('disposal.create') }}" class="ant-btn ant-btn-danger">
                <i class="fas fa-trash-alt"></i> Tạo phiếu xuất hủy
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-6">
                <div class="ant-card">
                    <div class="ant-card-body">
                        <div class="ant-statistic">
                            <div class="ant-statistic-icon" style="background-color: var(--danger-color);">
                                <i class="fas fa-trash-alt"></i>
                            </div>
                            <div class="ant-statistic-content">
                                <div class="ant-statistic-title">Tổng giá trị đã hủy</div>
                                <div class="ant-statistic-value">{{ number_format($totalDisposalValue, 0, ',', '.') }} VNĐ</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="ant-card">
                    <div class="ant-card-body">
                        <div class="ant-statistic">
                            <div class="ant-statistic-icon" style="background-color: var(--primary-color);">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="ant-statistic-content">
                                <div class="ant-statistic-title">Số lượng sản phẩm đã hủy</div>
                                <div class="ant-statistic-value">{{ number_format($totalDisposalItems, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="ant-card">
            <div class="ant-card-head">
                <div class="ant-card-head-title">Danh sách phiếu xuất hủy</div>
            </div>
            <div class="ant-card-body">
                <!-- Filters -->
                <div class="filter-row">
                    <div class="col-md-5 p-0">
                        <form action="{{ route('disposal.index') }}" method="GET" class="ant-input-search">
                            <input type="text" name="search" class="ant-input" placeholder="Tìm kiếm theo mã phiếu..." value="{{ request('search') }}">
                            <button class="ant-btn ant-btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                    <div class="col-md-7 p-0">
                        <form action="{{ route('disposal.index') }}" method="GET" class="date-range-filter">
                            <div class="ant-form-item">
                                <label for="start_date">Từ:</label>
                                <input type="date" id="start_date" name="start_date" class="ant-input" value="{{ request('start_date') }}">
                            </div>
                            <div class="ant-form-item">
                                <label for="end_date">Đến:</label>
                                <input type="date" id="end_date" name="end_date" class="ant-input" value="{{ request('end_date') }}">
                            </div>
                            <button type="submit" class="ant-btn ant-btn-primary">
                                <i class="fas fa-filter"></i> Lọc
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Table -->
                @if($disposals->count() > 0)
                    <div class="table-responsive">
                        <table class="ant-table">
                            <thead>
                            <tr>
                                <th style="width: 60px">STT</th>
                                <th>Mã phiếu</th>
                                <th>Ngày xuất hủy</th>
                                <th>Tổng giá trị hủy</th>
                                <th>Người tạo</th>
                                <th style="width: 100px">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($disposals as $key => $disposal)
                                <tr>
                                    <td>{{ $disposals->firstItem() + $key }}</td>
                                    <td>
                                        <span class="badge badge-danger">{{ $disposal->disposal_code }}</span>
                                    </td>
                                    <td>{{ $disposal->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-danger" style="font-weight: 600">{{ number_format($disposal->total_amount, 0, ',', '.') }} VNĐ</td>
                                    <td>{{ $disposal->user->name ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('disposal.show', $disposal) }}" class="ant-btn ant-btn-primary ant-btn-sm">
                                            <i class="fas fa-eye"></i> Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="ant-pagination">
                        {{ $disposals->appends(request()->query())->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="ant-empty">
                        <div class="ant-empty-image">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                        <p class="ant-empty-description">Không có phiếu xuất hủy nào</p>
                        <div>
                            <a href="{{ route('disposal.create') }}" class="ant-btn ant-btn-danger">
                                <i class="fas fa-plus"></i> Tạo phiếu xuất hủy
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
