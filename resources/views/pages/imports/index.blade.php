@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách hóa đơn nhập hàng</h3>
                        <div class="card-tools">
                            <a href="{{ route('imports.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tạo hóa đơn nhập hàng
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Tìm kiếm -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <form action="{{ route('imports.index') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                               placeholder="Tìm kiếm theo mã hóa đơn..."
                                               value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Thống kê tổng nợ -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="info-box bg-info">
                                    <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Tổng nợ NCC</span>
                                        <span
                                            class="info-box-number">{{ number_format($totalAmount, 0, ',', '.') }} VNĐ</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box bg-success">
                                    <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Đã thanh toán</span>
                                        <span
                                            class="info-box-number">{{ number_format($totalPaid, 0, ',', '.') }} VNĐ</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box bg-warning">
                                    <span class="info-box-icon"><i class="fas fa-exclamation-circle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Số tiền cần trả</span>
                                        <span
                                            class="info-box-number">{{ number_format($totalDebt, 0, ',', '.') }} VNĐ</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bảng danh sách hóa đơn -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th style="width: 50px">STT</th>
                                    <th>Mã phiếu nhập</th>
                                    <th>Ngày giao dịch</th>
                                    <th>Nhà cung cấp</th>
                                    <th>Tổng phải trả</th>
                                    <th>Đã trả</th>
                                    <th>Còn nợ</th>
                                    <th style="width: 150px">Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($imports as $key => $import)
                                    <tr>
                                        <td>{{ $imports->firstItem() + $key }}</td>
                                        <td>{{ $import->import_code }}</td>
                                        <td>{{ $import->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $import->supplier->name }}</td>
                                        <td>{{ number_format($import->final_amount, 0, ',', '.') }} VNĐ</td>
                                        <td>{{ number_format($import->paid_amount, 0, ',', '.') }} VNĐ</td>
                                        <td>{{ number_format($import->debt_amount, 0, ',', '.') }} VNĐ</td>
                                        <td>
                                            <a href="{{ route('imports.show', $import) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Chi tiết
                                            </a>
                                            @if($import->debt_amount > 0)
                                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                                        data-target="#paymentModal"
                                                        data-import-id="{{ $import->id }}"
                                                        data-import-code="{{ $import->import_code }}"
                                                        data-total-amount="{{ $import->final_amount }}"
                                                        data-paid-amount="{{ $import->paid_amount }}"
                                                        data-debt-amount="{{ $import->debt_amount }}">
                                                    <i class="fas fa-money-bill"></i> Thanh toán
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Không có dữ liệu</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Phân trang -->
                        <div class="mt-3">
                            {{ $imports->appends(request()->query())->links() }}
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="paymentForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Mã phiếu nhập:</label>
                            <input type="text" class="form-control" id="import_code" readonly>
                        </div>
                        <div class="form-group">
                            <label>Tổng tiền cần thu (VNĐ):</label>
                            <input type="text" class="form-control" id="total_amount" readonly>
                        </div>
                        <div class="form-group">
                            <label>Đã thanh toán (VNĐ):</label>
                            <input type="text" class="form-control" id="paid_amount" readonly>
                        </div>
                        <div class="form-group">
                            <label>Công nợ hiện tại:</label>
                            <input type="text" class="form-control" id="debt_amount" readonly>
                        </div>
                        <div class="form-group">
                            <label for="payment_amount">Số tiền thanh toán (VNĐ):</label>
                            <input type="number" class="form-control" id="payment_amount" name="payment_amount" min="0"
                                   step="1000" required>
                            <small class="text-danger" id="payment_error"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-info" id="viewHistoryBtn">Xem lịch sử thanh toán</button>
                        <button type="submit" class="btn btn-primary" id="confirmPaymentBtn">Xác nhận thanh toán
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#paymentModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var importId = button.data('import-id');
                var importCode = button.data('import-code');
                var totalAmount = button.data('total-amount');
                var paidAmount = button.data('paid-amount');
                var debtAmount = button.data('debt-amount');

                var modal = $(this);
                modal.find('#import_code').val(importCode);
                modal.find('#total_amount').val(totalAmount.toLocaleString('vi-VN') + ' VNĐ');
                modal.find('#paid_amount').val(paidAmount.toLocaleString('vi-VN') + ' VNĐ');
                modal.find('#debt_amount').val(debtAmount.toLocaleString('vi-VN') + ' VNĐ');

                // Set form action
                $('#paymentForm').attr('action', '/imports/' + importId + '/update-payment');

                // Set history button link
                $('#viewHistoryBtn').attr('data-import-id', importId);
            });

            $('#payment_amount').on('input', function () {
                var payment = parseFloat($(this).val()) || 0;
                var debtAmount = parseFloat($('#paymentModal').find('button').data('debt-amount')) || 0;

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

            $('#viewHistoryBtn').click(function () {
                var importId = $(this).attr('data-import-id');
                window.location.href = '/imports/' + importId + '/payment-history';
            });
        });
    </script>
@endpush
