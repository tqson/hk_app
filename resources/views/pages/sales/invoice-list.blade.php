@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Hóa đơn</h5>
                    </div>

                    <div class="card-body">
                        <!-- Form tìm kiếm -->
                        <form method="GET" action="{{ route('sales.invoices') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date_from">Từ ngày:</label>
                                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date_to">Đến ngày:</label>
                                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">Tìm kiếm</button>
                                    <a href="{{ route('sales.invoices') }}" class="btn btn-secondary">Đặt lại</a>
                                </div>
                            </div>
                        </form>

                        <!-- Bảng danh sách hóa đơn -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                <tr>
                                    <th width="5%">STT</th>
                                    <th width="15%">Mã hóa đơn</th>
                                    <th width="20%">Ngày giao dịch</th>
                                    <th width="20%" class="text-right">Tổng phải trả</th>
                                    <th width="20%" class="text-right">Khách thanh toán</th>
                                    <th width="10%">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($invoices as $index => $invoice)
                                    <tr>
                                        <td>{{ $index + $invoices->firstItem() }}</td>
                                        <td>HD{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y H:i') }}</td>
                                        <td class="text-right">{{ number_format($invoice->total_amount - $invoice->discount, 0, ',', '.') }} VND</td>
                                        <td class="text-right">
                                            @php
                                                $paymentMethod = '';
                                                if ($invoice->payment_method == 'cash') {
                                                    $paymentMethod = 'Tiền mặt';
                                                } elseif ($invoice->payment_method == 'transfer') {
                                                    $paymentMethod = 'Chuyển khoản';
                                                } elseif ($invoice->payment_method == 'mixed') {
                                                    $paymentMethod = 'Kết hợp';
                                                }
                                            @endphp
                                            {{ $paymentMethod }}
                                        </td>
                                        <td>
                                            <a href="{{ route('sales.invoice-detail', $invoice->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Không có dữ liệu</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Phân trang -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="pagination-nav">
                                <ul class="pagination mb-0">
                                    <!-- First Page -->
                                    <li class="page-item {{ $invoices->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $invoices->url(1) }}" aria-label="First">
                                            <span aria-hidden="true">&laquo;&laquo;</span>
                                        </a>
                                    </li>
                                    <!-- Previous Page -->
                                    <li class="page-item {{ $invoices->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $invoices->previousPageUrl() }}"
                                           aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>

                                    <!-- Page Numbers -->
                                    @php
                                        $currentPage = $invoices->currentPage();
                                        $lastPage = $invoices->lastPage();
                                        $startPage = max($currentPage - 2, 1);
                                        $endPage = min($currentPage + 2, $lastPage);
                                    @endphp

                                    @for ($i = $startPage; $i <= $endPage; $i++)
                                        <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $invoices->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                    <!-- Next Page -->
                                    <li class="page-item {{ $invoices->hasMorePages() ? '' : 'disabled' }}">
                                        <a class="page-link" href="{{ $invoices->nextPageUrl() }}" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                    <!-- Last Page -->
                                    <li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $invoices->url($lastPage) }}" aria-label="Last">
                                            <span aria-hidden="true">&raquo;&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="pagination-size-selector">
                                <span class="me-2">Hiển thị:</span>
                                <select id="page-size" class="form-control form-control-sm d-inline-block"
                                        style="width: auto;" onchange="changePageSize(this.value)">
                                    <option
                                        value="10" {{ request('perPage') == 10 || !request('perPage') ? 'selected' : '' }}>
                                        10
                                    </option>
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
@endsection

@section('scripts')
    <script>
        function changePageSize(perPage) {
            // Lấy URL hiện tại
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);

            // Cập nhật hoặc thêm tham số perPage
            params.set('perPage', perPage);

            // Đặt lại trang về 1 khi thay đổi số lượng hiển thị
            params.set('page', 1);

            // Cập nhật URL và tải lại trang
            url.search = params.toString();
            window.location.href = url.toString();
        }
    </script>
@endsection
