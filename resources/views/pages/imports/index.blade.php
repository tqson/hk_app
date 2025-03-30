@extends('layouts.app')

@section('styles')
    <style>
        .icon-circle {
            height: 40px;
            width: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-gradient-primary {
            background: linear-gradient(to right, #4e73df, #224abe);
        }

        .bg-gradient-success {
            background: linear-gradient(to right, #1cc88a, #13855c);
        }

        .bg-gradient-warning {
            background: linear-gradient(to right, #f6c23e, #dda20a);
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /*.card:hover {*/
        /*    transform: translateY(-5px);*/
        /*    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;*/
        /*}*/

        .rounded-top {
            border-top-left-radius: 0.35rem !important;
            border-top-right-radius: 0.35rem !important;
        }
    </style>
@endsection

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
                        <!-- Filters and Search -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Tìm kiếm và lọc</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('imports.index') }}" method="GET" class="mb-0">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="search">Tìm kiếm:</label>
                                            <input type="search" class="form-control" id="search" name="search"
                                                   placeholder="Mã nhập hàng hoặc nhà cung cấp" value="{{ request('search') }}">
                                        </div>

                                        <div class="col-md-2 mb-3">
                                            <label for="date_from">Từ ngày:</label>
                                            <input type="date" class="form-control" id="date_from" name="date_from"
                                                   value="{{ request('date_from') }}">
                                        </div>

                                        <div class="col-md-2 mb-3">
                                            <label for="date_to">Đến ngày:</label>
                                            <input type="date" class="form-control" id="date_to" name="date_to"
                                                   value="{{ request('date_to') }}">
                                        </div>

                                        <div class="col-md-2 mb-3">
                                            <label for="payment_status">Trạng thái thanh toán:</label>
                                            <select class="form-control" id="payment_status" name="payment_status">
                                                <option value="">Tất cả</option>
                                                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                                <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Còn nợ</option>
                                            </select>
                                        </div>

                                        <div class="col mb-3 d-flex align-items-end gap-3">
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fas fa-search"></i>
                                            </button>

                                            <a href="{{ route('imports.index') }}" class="btn btn-secondary btn-block">
                                                <i class="fas fa-redo"></i>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Thống kê tổng nợ -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-body p-0">
                                        <div class="d-flex align-items-center p-3 bg-gradient-primary text-white rounded-top">
                                            <div class="icon-circle bg-white text-primary mr-3">
                                                <i class="fas fa-money-bill-wave fa-lg"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 font-weight-bold">Tổng nợ NCC</h6>
                                            </div>
                                        </div>
                                        <div class="p-3 text-center">
                                            <h3 class="font-weight-bold mb-0">{{ number_format($totalAmount, 0, ',', '.') }} <small>VNĐ</small></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-body p-0">
                                        <div class="d-flex align-items-center p-3 bg-gradient-success text-white rounded-top">
                                            <div class="icon-circle bg-white text-success mr-3">
                                                <i class="fas fa-check-circle fa-lg"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 font-weight-bold">Đã thanh toán</h6>
                                            </div>
                                        </div>
                                        <div class="p-3 text-center">
                                            <h3 class="font-weight-bold mb-0 text-success">{{ number_format($totalPaid, 0, ',', '.') }} <small>VNĐ</small></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-body p-0">
                                        <div class="d-flex align-items-center p-3 bg-gradient-warning text-white rounded-top">
                                            <div class="icon-circle bg-white text-warning mr-3">
                                                <i class="fas fa-exclamation-circle fa-lg"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 font-weight-bold">Số tiền cần trả</h6>
                                            </div>
                                        </div>
                                        <div class="p-3 text-center">
                                            <h3 class="font-weight-bold mb-0 text-warning">{{ number_format($totalDebt, 0, ',', '.') }} <small>VNĐ</small></h3>
                                        </div>
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

                                                <input type="hidden" id="debt_amount" value="{{ $import->debt_amount }}">
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

                        <!-- Ant Design style pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="pagination-nav">
                                <ul class="pagination mb-0">
                                    <!-- First Page -->
                                    <li class="page-item {{ $imports->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $imports->url(1) }}" aria-label="First">
                                            <span aria-hidden="true">&laquo;&laquo;</span>
                                        </a>
                                    </li>
                                    <!-- Previous Page -->
                                    <li class="page-item {{ $imports->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $imports->previousPageUrl() }}"
                                           aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>

                                    <!-- Page Numbers -->
                                    @php
                                        $currentPage = $imports->currentPage();
                                        $lastPage = $imports->lastPage();
                                        $startPage = max($currentPage - 2, 1);
                                        $endPage = min($currentPage + 2, $lastPage);
                                    @endphp

                                    @for ($i = $startPage; $i <= $endPage; $i++)
                                        <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $imports->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                    <!-- Next Page -->
                                    <li class="page-item {{ $imports->hasMorePages() ? '' : 'disabled' }}">
                                        <a class="page-link" href="{{ $imports->nextPageUrl() }}" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                    <!-- Last Page -->
                                    <li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $imports->url($lastPage) }}" aria-label="Last">
                                            <span aria-hidden="true">&raquo;&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="pagination-size-selector">
                                <span class="me-2">Hiển thị:</span>
                                <select id="page-size" class="form-control form-control-sm d-inline-block"
                                        style="width: auto;" onchange="changePageSize(this.value)">
                                    <option value="10" {{ request('perPage') == 10 || !request('perPage') ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ request('perPage') == 20 ? 'selected' : '' }}>20</option>
                                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                                <span class="ms-2">/ trang</span>
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
        <input type="hidden" id="debt_amount_data">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Cập nhật thanh toán</h5>
                    <button type="button" class="close border-0" data-dismiss="modal" aria-label="Close">
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
                            <input type="number" class="form-control" id="payment_amount" name="payment_amount" min="1"
                                   required>
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

@section('scripts')
    <script>

        function changePageSize(perPage) {
            const url = new URL(window.location.href);
            url.searchParams.set('perPage', perPage);
            window.location.href = url.toString();
        }

        $(document).ready(function () {
            $('#paymentModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var importId = button.data('import-id');
                var importCode = button.data('import-code');
                var totalAmount = button.data('total-amount');
                var paidAmount = button.data('paid-amount');
                var debtAmount = button.data('debt-amount');
                console.log('debtAmount', debtAmount);

                var modal = $(this);
                modal.find('#import_code').val(importCode);
                modal.find('#total_amount').val(totalAmount.toLocaleString('vi-VN') + ' VNĐ');
                modal.find('#paid_amount').val(paidAmount.toLocaleString('vi-VN') + ' VNĐ');
                modal.find('#debt_amount').val(debtAmount.toLocaleString('vi-VN') + ' VNĐ');

                // Lưu giá trị debt_amount vào một data attribute của input để dễ truy cập
                modal.find('#debt_amount_data').data('value', debtAmount);

                // Set form action
                $('#paymentForm').attr('action', '/imports/' + importId + '/update-payment');

                // Set history button link
                $('#viewHistoryBtn').attr('data-import-id', importId);
            });

            $('#payment_amount').on('input', function () {
                var payment = parseFloat($(this).val()) || 0;
                // Lấy giá trị debt_amount từ data attribute của input
                var debtAmount = parseFloat($('#debt_amount_data').data('value')) || 0;

                console.log('payment', payment);
                console.log('debtAmount', debtAmount);

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

            // Thêm định dạng tiền tệ cho payment_amount
            $('#payment_amount').on('blur', function() {
                var value = parseFloat($(this).val()) || 0;
                if (value > 0) {
                    $(this).val(value.toLocaleString('vi-VN'));
                }
            });

            $('#payment_amount').on('focus', function() {
                var value = $(this).val().replace(/[^\d]/g, '') || '';
                $(this).val(value);
            });

            $('#viewHistoryBtn').click(function () {
                var importId = $(this).attr('data-import-id');
                window.location.href = '/imports/' + importId + '/payment-history';
            });
        });
    </script>
@endsection
