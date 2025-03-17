@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Chi tiết hóa đơn #HD{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</h5>
                        <a href="{{ route('sales.invoices') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p><strong>Ngày tạo:</strong> {{ Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y H:i') }}</p>
                                <p><strong>Người tạo:</strong> {{ $invoice->user->full_name }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Phương thức thanh toán:</strong>
                                    @if($invoice->payment_method == 'cash')
                                        Tiền mặt
                                    @elseif($invoice->payment_method == 'transfer')
                                        Chuyển khoản
                                    @elseif($invoice->payment_method == 'mixed')
                                        Kết hợp
                                    @endif
                                </p>
                                <p><strong>Ghi chú:</strong> {{ $invoice->notes ?? 'Không có' }}</p>
                            </div>
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-hover" id="cartTable">
                                <thead class="thead-light">
                                <tr>
                                    <th width="5%">STT</th>
                                    <th width="25%">Tên sản phẩm</th>
                                    <th width="10%">Đơn vị</th>
                                    <th width="15%">Số lượng</th>
                                    <th width="15%">Đơn giá</th>
                                    <th width="15%">Thành tiền</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($invoice->details as $index => $detail)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $detail->product->name }}</td>
                                        <td>{{ $detail->product->unit }}</td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td class="text-right">{{ number_format($detail->price, 0, ',', '.') }} VND</td>
                                        <td class="text-right">{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }} VND</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">Tổng tiền:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control-plaintext text-right" value="{{ number_format($invoice->total_amount, 0, ',', '.') }} VND" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">Chiết khấu:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control-plaintext text-right" value="{{ number_format($invoice->discount, 0, ',', '.') }} VND" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label font-weight-bold">Khách phải trả:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control-plaintext text-right font-weight-bold" value="{{ number_format($invoice->total_amount - $invoice->discount, 0, ',', '.') }} VND" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
