@extends('layouts.app')

@section('styles')
    <style>
        .card-dashboard {
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
        }

        .card-dashboard:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            border-bottom: none;
            padding: 15px 20px;
        }

        .card-header .card-title {
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 0;
        }

        .info-box {
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            overflow: hidden;
        }

        .info-box:hover {
            transform: translateY(-5px);
        }

        .info-box-icon {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }

        .info-box-content {
            padding: 15px;
        }

        .info-box-text {
            font-weight: 600;
            font-size: 1rem;
            text-transform: uppercase;
        }

        .info-box-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 5px;
        }

        .bg-info {
            background: linear-gradient(135deg, #36b9cc 0%, #1a8a98 100%) !important;
        }

        .bg-success {
            background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%) !important;
        }

        .bg-warning {
            background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%) !important;
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
            color: #4e73df;
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

        .badge-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.8rem;
        }

        .badge-paid {
            background-color: #1cc88a;
            color: white;
        }

        .badge-debt {
            background-color: #f6c23e;
            color: white;
        }

        .search-form .input-group {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 30px;
            overflow: hidden;
        }

        .search-form .form-control {
            border: none;
            padding: 12px 20px;
            height: auto;
        }

        .search-form .btn {
            padding: 0 20px;
            border: none;
        }

        .btn-action {
            border-radius: 50px;
            padding: 5px 15px;
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            margin: 0 2px;
        }

        .btn-view {
            background-color: #4e73df;
            border-color: #4e73df;
        }

        .btn-view:hover {
            background-color: #2e59d9;
            border-color: #2e59d9;
        }

        .btn-pay {
            background-color: #1cc88a;
            border-color: #1cc88a;
        }

        .btn-pay:hover {
            background-color: #169b6b;
            border-color: #169b6b;
        }

        .pagination {
            justify-content: center;
        }

        .page-item.active .page-link {
            background-color: #4e73df;
            border-color: #4e73df;
        }

        .page-link {
            color: #4e73df;
        }

        .empty-state {
            text-align: center;
            padding: 40px 0;
        }

        .empty-state i {
            font-size: 5rem;
            color: #d1d3e2;
            margin-bottom: 20px;
        }

        .empty-state h4 {
            color: #5a5c69;
            font-weight: 600;
        }

        .empty-state p {
            color: #858796;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Danh sách hóa đơn nhập hàng</h1>
            <a href="{{ route('imports.create') }}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
                <span class="text">Tạo hóa đơn nhập hàng</span>
            </a>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-money-bill-wave"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Tổng nợ NCC</span>
                        <span class="info-box-number">{{ number_format($totalAmount, 0, ',', '.') }} VNĐ</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Đã thanh toán</span>
                        <span class="info-box-number">{{ number_format($totalPaid, 0, ',', '.') }} VNĐ</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Số tiền cần trả</span>
                        <span class="info-box-number">{{ number_format($totalDebt, 0, ',', '.') }} VNĐ</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-dashboard shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-white">Danh sách hóa đơn</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-white"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Tùy chọn:</div>
                        <a class="dropdown-item" href="{{ route('imports.index') }}?status=all">Tất cả hóa đơn</a>
                        <a class="dropdown-item" href="{{ route('imports.index') }}?status=debt">Còn nợ</a>
                        <a class="dropdown-item" href="{{ route('imports.index') }}?status=paid">Đã thanh toán</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('imports.index') }}?sort=newest">Mới nhất</a>
                        <a class="dropdown-item" href="{{ route('imports.index') }}?sort=oldest">Cũ nhất</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <form action="{{ route('imports.index') }}" method="GET" class="search-form">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo mã hóa đơn, nhà cung cấp..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary" id="btn-today">Hôm nay</button>
                            <button type="button" class="btn btn-outline-primary" id="btn-week">Tuần này</button>
                            <button type="button" class="btn btn-outline-primary" id="btn-month">Tháng này</button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 50px">STT</th>
                            <th>Mã phiếu nhập</th>
                            <th>Ngày giao dịch</th>
                            <th>Nhà cung cấp</th>
                            <th>Tổng phải trả</th>
                            <th>Đã trả</th>
                            <th>Còn nợ</th>
                            <th>Trạng thái</th>
                            <th style="width: 150px">Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($imports as $key => $import)
                            <tr>
                                <td class="text-center">{{ $imports->firstItem() + $key }}</td>
                                <td>
                                    <span class="font-weight-bold">{{ $import->import_code }}</span>
                                </td>
                                <td>{{ $import->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="d-block">{{ $import->supplier->name }}</span>
                                    <small class="text-muted">{{ $import->supplier->phone ?? 'N/A' }}</small>
                                </td>
                                <td class="font-weight-bold text-primary">{{ number_format($import->final_amount, 0, ',', '.') }} VNĐ</td>
                                <td class="text-success">{{ number_format($import->paid_amount, 0, ',', '.') }} VNĐ</td>
                                <td class="text-warning">{{ number_format($import->debt_amount, 0, ',', '.') }} VNĐ</td>
                                <td>
                                    @if($import->debt_amount <= 0)
                                        <span class="badge badge-status badge-paid">Đã thanh toán</span>
                                    @else
                                        <span class="badge badge-status badge-debt">Còn nợ</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('imports.show', $import) }}" class="btn btn-sm btn-view btn-action">
                                        <i class="fas fa-eye"></i> Chi tiết
                                    </a>
                                    @if($import->debt_amount > 0)
                                        <button type="button" class="btn btn-sm btn-pay btn-action" data-toggle="modal" data-target="#paymentModal"
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
                                <td colspan="9">
                                    <div class="empty-state">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                        <h4>Không có hóa đơn nào</h4>
                                        <p>Chưa có hóa đơn nhập hàng nào được tạo.</p>
                                        <a href="{{ route('imports.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tạo hóa đơn nhập hàng
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $imports->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thanh toán -->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="paymentModalLabel">Cập nhật thanh toán</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="paymentForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Mã phiếu nhập:</label>
                            <input type="text" class="form-control" id="import_code" readonly>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Tổng tiền cần trả:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="total_amount" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Đã thanh toán:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="paid_amount" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Công nợ hiện tại:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="debt_amount" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="payment_amount" class="font-weight-bold">Số tiền thanh toán:</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="payment_amount" name="payment_amount" min="0.01" step="1000" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>
                            <small class="text-danger" id="payment_error"></small>
                        </div>
                        <div class="form-group">
                            <label for="payment_method" class="font-weight-bold">Phương thức thanh toán:</label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="cash">Tiền mặt</option>
                                <option value="bank_transfer">Chuyển khoản</option>
                                <option value="card">Thẻ tín dụng/ghi nợ</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="payment_note" class="font-weight-bold">Ghi chú:</label>
                            <textarea class="form-control" id="payment_note" name="payment_note" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <a href="#" class="btn btn-info" id="view_history_btn">
                            <i class="fas fa-history"></i> Xem lịch sử thanh toán
                        </a>
                        <button type="submit" class="btn btn-primary" id="confirmPaymentBtn">
                            <i class="fas fa-check"></i> Xác nhận thanh toán
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Xử lý hiển thị modal thanh toán
            $('#paymentModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var importId = button.data('import-id');
                var importCode = button.data('import-code');
                var totalAmount = button.data('total-amount');
                var paidAmount = button.data('paid-amount');
                var debtAmount = button.data('debt-amount');

                var modal = $(this);
                modal.find('#import_code').val(importCode);
                modal.find('#total_amount').val(numberFormat(totalAmount));
                modal.find('#paid_amount').val(numberFormat(paidAmount));
                modal.find('#debt_amount').val(numberFormat(debtAmount));
                modal.find('#payment_amount').attr('max', debtAmount);
                modal.find('#payment_amount').val(debtAmount);

                // Cập nhật action của form
                modal.find('#paymentForm').attr('action', '/imports/' + importId + '/update-payment');

                // Cập nhật link xem lịch sử thanh toán
                modal.find('#view_history_btn').attr('href', '/imports/' + importId + '/payment-history');
            });

            // Xử lý validate số tiền thanh toán
            $('#payment_amount').on('input', function() {
                var payment = parseFloat($(this).val()) || 0;
                var debtAmount = parseFloat($('#debt_amount').val().replace(/\./g, '').replace(',', '.')) || 0;

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

            // Xử lý nút lọc theo thời gian
            $('#btn-today').click(function() {
                window.location.href = '{{ route("imports.index") }}?date=today';
            });

            $('#btn-week').click(function() {
                window.location.href = '{{ route("imports.index") }}?date=week';
            });

            $('#btn-month').click(function() {
                window.location.href = '{{ route("imports.index") }}?date=month';
            });

            // Hàm format số
            function numberFormat(number) {
                return new Intl.NumberFormat('vi-VN').format(number);
            }
        });
    </script>
@endpush
