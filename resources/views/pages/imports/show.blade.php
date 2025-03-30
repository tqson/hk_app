@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Chi tiết hóa đơn nhập hàng</h3>
                        <div class="card-tools">
                            <a href="{{ route('imports.index') }}" class="btn btn-default">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
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
                                                <th>Ngày nhận hàng dự kiến:</th>
                                                <td>{{ $import->expected_date->format('d/m/Y') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nhà cung cấp:</th>
                                                <td>{{ $import->supplier->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tổng tiền hàng:</th>
                                                <td>{{ number_format($import->total_amount, 0, ',', '.') }} VNĐ</td>
                                            </tr>
                                            <tr>
                                                <th>VAT:</th>
                                                <td>{{ number_format($import->vat, 0, ',', '.') }} VNĐ</td>
                                            </tr>
                                            <tr>
                                                <th>Giảm giá:</th>
                                                <td>{{ $import->discount_percent }}%
                                                    ({{ number_format($import->total_amount * $import->discount_percent / 100, 0, ',', '.') }}
                                                    VNĐ)
                                                </td>
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

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-success">
                                        <h3 class="card-title">Thông tin nhà cung cấp</h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th style="width: 200px">Tên nhà cung cấp:</th>
                                                <td>{{ $import->supplier->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Mã số thuế:</th>
                                                <td>{{ $import->supplier->tax_code ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Địa chỉ:</th>
                                                <td>{{ $import->supplier->address ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Số điện thoại:</th>
                                                <td>{{ $import->supplier->phone ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email:</th>
                                                <td>{{ $import->supplier->email ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Người liên hệ:</th>
                                                <td>{{ $import->supplier->contact_person ?? 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-info">
                                        <h3 class="card-title">Chi tiết sản phẩm</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th style="width: 50px">STT</th>
                                                    <th>Tên sản phẩm</th>
                                                    <th>Lô - NSX - HSD</th>
                                                    <th>Đơn vị</th>
                                                    <th>Số lượng</th>
                                                    <th>Giá nhập</th>
                                                    <th>Tổng tiền</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($import->items as $key => $item)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $item->product->name }}</td>
                                                        <td>
                                                            @if($item->productBatch)
                                                                {{ $item->productBatch->batch_number }} -
                                                                NSX: {{ $item->productBatch->manufacturing_date->format('d/m/Y') }}
                                                                -
                                                                HSD: {{ $item->productBatch->expiry_date->format('d/m/Y') }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td>{{ $item->product->unit }}</td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>{{ number_format($item->import_price, 0, ',', '.') }}VNĐ
                                                        </td>
                                                        <td>{{ number_format($item->total_price, 0, ',', '.') }}VNĐ
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">Không có dữ liệu</td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th colspan="6" class="text-right">Tổng tiền:</th>
                                                    <th>{{ number_format($import->total_amount, 0, ',', '.') }} VNĐ</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-warning">
                                        <h3 class="card-title">Lịch sử thanh toán</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th style="width: 50px">STT</th>
                                                    <th>Ngày thanh toán</th>
                                                    <th>Số tiền thanh toán</th>
                                                    <th>Công nợ còn lại</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($import->paymentHistories as $key => $payment)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                                        <td>{{ number_format($payment->amount, 0, ',', '.') }} VNĐ</td>
                                                        <td>{{ number_format($payment->remaining_debt, 0, ',', '.') }}
                                                            VNĐ
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">Không có dữ liệu</td>
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
                                <a href="{{ route('imports.index') }}" class="btn btn-secondary">Quay lại</a>
                                @if($import->debt_amount > 0)
                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                            data-target="#paymentModal"
                                            data-import-id="{{ $import->id }}"
                                            data-import-code="{{ $import->import_code }}"
                                            data-total-amount="{{ $import->final_amount }}"
                                            data-paid-amount="{{ $import->paid_amount }}"
                                            data-debt-amount="{{ $import->debt_amount }}">
                                        <i class="fas fa-money-bill"></i> Cập nhật thanh toán
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cập nhật thanh toán -->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Cập nhật thanh toán</h5>
                    <button type="button" class="close border-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="paymentForm" method="POST" action="{{ route('imports.update-payment', $import) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Mã phiếu nhập:</label>
                            <input type="text" class="form-control" value="{{ $import->import_code }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Tổng tiền cần thu (VNĐ):</label>
                            <input type="text" class="form-control"
                                   value="{{ number_format($import->final_amount, 0, ',', '.') }} VNĐ" readonly>
                        </div>
                        <div class="form-group">
                            <label>Đã thanh toán (VNĐ):</label>
                            <input type="text" class="form-control"
                                   value="{{ number_format($import->paid_amount, 0, ',', '.') }} VNĐ" readonly>
                        </div>
                        <div class="form-group">
                            <label>Công nợ hiện tại:</label>
                            <input type="text" class="form-control"
                                   value="{{ number_format($import->debt_amount, 0, ',', '.') }} VNĐ" readonly>
                        </div>
                        <div class="form-group">
                            <label for="payment_amount">Số tiền thanh toán (VNĐ):</label>
                            <input type="number" class="form-control" id="payment_amount" name="payment_amount"
                                   min="1" max="{{ $import->debt_amount }}" required>
                            <small class="text-danger" id="payment_error"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <a href="{{ route('imports.payment-history', $import) }}" class="btn btn-info">Xem lịch sử thanh
                            toán</a>
                        <button type="submit" class="btn btn-primary" id="confirmPaymentBtn">Xác nhận thanh toán
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#payment_amount').on('input', function () {
                const payment = parseFloat($(this).val()) || 0;
                const debtAmount = {{ $import->debt_amount }};

                console.log('show', payment);
                console.log(debtAmount);
                if (payment > debtAmount) {
                    $('#payment_error').text('Số tiền thanh toán không được vượt quá công nợ hiện tại');
                    $('#confirmPaymentBtn').prop('disabled', true);
                } else if (payment <= 0) {
                    $('#payment_error').text('Số tiền thanh toán phải lớn hơn 0');
                    $('#confirmPaymentBtn').prop('disabled', true);
                } else {
                    $('#payment_error').text('');
                    $('#confirmPaymentBtn').prop('disabled', false);
                }
            });
        });
    </script>
@endsection

