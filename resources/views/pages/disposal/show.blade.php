@extends('layouts.app')

@section('styles')
    <style>
        .card-dashboard {
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #e74a3b 0%, #c0392b 100%);
            color: white;
            border-bottom: none;
            padding: 15px 20px;
        }

        .card-header .card-title {
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 0;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background-color: #f8f9fc;
            border-top: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            padding: 12px;
            color: #e74a3b;
        }

        .table tbody tr {
            transition: background-color 0.2s;
        }

        .table tbody tr:hover {
            background-color: #f8f9fc;
        }

        .table td {
            vertical-align: middle;
            padding: 12px;
        }

        .summary-box {
            background: #f8f9fc;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .summary-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 15px;
            color: #5a5c69;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .summary-label {
            font-weight: 500;
            color: #5a5c69;
        }

        .summary-value {
            font-weight: 600;
        }

        .total-amount {
            font-size: 1.2rem;
            font-weight: 700;
            color: #e74a3b;
        }

        .info-group {
            margin-bottom: 15px;
        }

        .info-label {
            font-weight: 600;
            color: #5a5c69;
            margin-bottom: 5px;
        }

        .info-value {
            color: #5a5c69;
        }

        .btn-action {
            border-radius: 50px;
            padding: 8px 25px;
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }

        .btn-back {
            background-color: #858796;
            border-color: #858796;
        }

        .btn-back:hover {
            background-color: #717384;
            border-color: #717384;
        }

        .badge-status {
            padding: 5px 10px;
            border-radius: 30px;
            font-weight: 500;
            font-size: 0.8rem;
        }

        .badge-completed {
            background-color: #1cc88a;
            color: white;
        }

        .batch-info {
            font-size: 0.8rem;
            color: #6c757d;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Chi tiết phiếu xuất hủy</h1>
            <a href="{{ route('disposal.index') }}" class="btn btn-back btn-action text-white">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card card-dashboard shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-white">Danh sách sản phẩm hủy</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th style="width: 50px">STT</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Lô - HSD</th>
                                    <th>ĐVT</th>
                                    <th>SL hủy</th>
                                    <th>Giá hủy</th>
                                    <th>Lý do hủy</th>
                                    <th>Thành tiền</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($disposal->items as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $item->product->name }}</td>
                                        <td>
                                            {{ $item->batch->batch_number }}
                                            <div class="batch-info">
                                                NSX: {{ date('d/m/Y', strtotime($item->batch->manufacturing_date)) }} -
                                                HSD: {{ date('d/m/Y', strtotime($item->batch->expiry_date)) }}
                                            </div>
                                        </td>
                                        <td>{{ $item->product->unit }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-right">{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                                        <td>{{ $item->reason }}</td>
                                        <td class="text-right font-weight-bold">{{ number_format($item->quantity * $item->price, 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-dashboard shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-white">Thông tin phiếu hủy</h6>
                    </div>
                    <div class="card-body">
                        <div class="info-group">
                            <div class="info-label">Mã phiếu hủy:</div>
                            <div class="info-value font-weight-bold">{{ $disposal->invoice_code }}</div>
                        </div>

                        <div class="info-group">
                            <div class="info-label">Ngày tạo:</div>
                            <div class="info-value">{{ date('d/m/Y H:i', strtotime($disposal->created_at)) }}</div>
                        </div>

{{--                        <div class="info-group">--}}
{{--                            <div class="info-label">Người tạo:</div>--}}
{{--                            <div class="info-value">{{ $disposal->user->name ?? 'N/A' }}</div>--}}
{{--                        </div>--}}

                        <div class="info-group">
                            <div class="info-label">Trạng thái:</div>
                            <div class="info-value">
                                <span class="badge badge-status badge-completed">Đã hoàn thành</span>
                            </div>
                        </div>

                        <div class="info-group">
                            <div class="info-label">Ghi chú:</div>
                            <div class="info-value">{{ $disposal->note ?? 'Không có ghi chú' }}</div>
                        </div>

                        <div class="summary-box">
                            <div class="summary-title">Tổng kết</div>
                            <div class="summary-item">
                                <span class="summary-label">Tổng số sản phẩm:</span>
                                <span class="summary-value">{{ $disposal->items->count() }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Tổng số lượng:</span>
                                <span class="summary-value">{{ $disposal->items->sum('quantity') }}</span>
                            </div>
                            <hr>
                            <div class="summary-item">
                                <span class="summary-label">Tổng giá trị hủy:</span>
                                <span class="summary-value total-amount">{{ number_format($disposal->total_amount, 0, ',', '.') }} VNĐ</span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('disposal.index') }}" class="btn btn-back btn-action btn-block text-white">
                                <i class="fas fa-arrow-left"></i> Quay lại danh sách
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

