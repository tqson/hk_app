@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Lịch sử thanh toán - {{ $import->import_code }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('imports.show', $import) }}" class="btn btn-default">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h3 class="card-title">Thông tin hóa đơn</h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th style="width: 200px">Mã phiếu nhập:</th>
                                                <td>{{ $import->import_code }}</td>
                                            </tr>
                                            <tr>
                                                <th>Ngày tạo:</th>
                                                <td>{{ $import->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nhà cung cấp:</th>
                                                <td>{{ $import->supplier->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tổng cần trả:</th>
                                                <td>{{ number_format($import->final_amount, 0, ',', '.') }} VNĐ</td>
                                            </tr>
                                            <tr>
                                                <th>Đã thanh toán:</th>
                                                <td>{{ number_format($import->paid_amount, 0, ',', '.') }} VNĐ</td>
                                            </tr>
                                            <tr>
                                                <th>Còn nợ:</th>
                                                <td>{{ number_format($import->debt_amount, 0, ',', '.') }} VNĐ</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-info">
                                        <h3 class="card-title">Lịch sử thanh toán</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th style="width: 50px">STT</th>
                                                    <th>Ngày thanh toán</th>
                                                    <th>Mã hóa đơn</th>
                                                    <th>Số tiền đã thanh toán</th>
                                                    <th>Công nợ còn lại</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($paymentHistories as $key => $payment)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                                        <td>{{ $import->import_code }}</td>
                                                        <td>{{ number_format($payment->amount, 0, ',', '.') }} VNĐ</td>
                                                        <td>{{ number_format($payment->remaining_debt, 0, ',', '.') }}
                                                            VNĐ
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">Không có dữ liệu</td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('imports.show', $import) }}" class="btn btn-secondary">Quay lại</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
