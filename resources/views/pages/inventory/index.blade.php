@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Kiểm kê kho</h1>

        <!-- Filters and Search -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tìm kiếm và lọc</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('inventory.index') }}" method="GET" class="mb-0">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="search">Tìm kiếm sản phẩm:</label>
                            <input type="text" class="form-control" id="search" name="search"
                                   placeholder="Nhập tên sản phẩm..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="stock_status">Trạng thái tồn kho:</label>
                            <select class="form-control" id="stock_status" name="stock_status">
                                <option value="">Tất cả</option>
                                <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>Còn tồn</option>
                                <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Hết tồn</option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                            <a href="{{ route('disposal.create') }}" class="btn btn-success">
                                <i class="fas fa-trash-alt"></i> Tạo phiếu hủy
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Danh sách tồn kho</h6>
                <a href="{{ route('disposal.index') }}" class="btn btn-info btn-sm">
                    <i class="fas fa-list"></i> Danh sách phiếu hủy
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th width="30%">STT / Tên sản phẩm</th>
                            <th width="10%">Đơn vị</th>
                            <th width="20%">Số lô</th>
                            <th width="15%">Hạn sử dụng</th>
                            <th width="15%">Số lượng tồn kho</th>
{{--                            <th width="10%">Thao tác</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            // Tính toán số thứ tự bắt đầu dựa trên trang hiện tại và số lượng hiển thị mỗi trang
                            $startNumber = ($products->currentPage() - 1) * $products->perPage() + 1;
                            $currentNumber = $startNumber;
                            $currentProductId = null;
                        @endphp

                        @forelse($products as $product)
                            @if($product->batches->count() > 0)
                                @php
                                    $batchesWithStock = $product->batches->filter(function($batch) {
                                        return $batch->quantity > 0;
                                    });
                                    $rowCount = $batchesWithStock->count();
                                @endphp

                                @if($rowCount > 0)
                                    @foreach($batchesWithStock as $index => $batch)
                                        <tr class="{{ $index === 0 ? 'main-row' : 'sub-row' }}">
                                            @if($index === 0)
                                                <td rowspan="{{ $rowCount }}" class="align-middle">
                                                    <div class="d-flex">
                                                        <span class="me-2 font-weight-bold">{{ $currentNumber++ }}.</span>
                                                        <span>{{ $product->name }}</span>
                                                    </div>
                                                </td>
                                                <td rowspan="{{ $rowCount }}" class="align-middle">{{ $product->unit }}</td>
                                            @endif
                                            <td>{{ $batch->batch_number }}</td>
                                            <td>
                                                {{ $batch->expiry_date->format('d/m/Y') }}
                                                @if($batch->expiry_date->isPast())
                                                    <span class="badge badge-danger">Hết hạn</span>
                                                @elseif($batch->expiry_date->diffInMonths(now()) <= 3)
                                                    <span class="badge badge-warning">Sắp hết hạn</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="font-weight-bold {{ $batch->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ $batch->quantity }}
                                                </span>
                                            </td>
{{--                                            <td>--}}
{{--                                                <a href="{{ route('disposal.create') }}?product_id={{ $product->id }}&batch_id={{ $batch->id }}" class="btn btn-danger btn-sm">--}}
{{--                                                    <i class="fas fa-trash-alt"></i> Hủy--}}
{{--                                                </a>--}}
{{--                                            </td>--}}
                                        </tr>
                                    @endforeach
                                @endif
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-box-open fa-3x text-gray-300 mb-3"></i>
                                        <h5 class="text-gray-700">Không tìm thấy sản phẩm nào trong kho</h5>
                                        <p class="text-gray-500">Hãy thêm sản phẩm mới hoặc thay đổi bộ lọc tìm kiếm</p>
                                    </div>
                                </td>
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
                            <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $products->url(1) }}" aria-label="First">
                                    <span aria-hidden="true">&laquo;&laquo;</span>
                                </a>
                            </li>
                            <!-- Previous Page -->
                            <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $products->previousPageUrl() }}"
                                   aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            <!-- Page Numbers -->
                            @php
                                $currentPage = $products->currentPage();
                                $lastPage = $products->lastPage();
                                $startPage = max($currentPage - 2, 1);
                                $endPage = min($currentPage + 2, $lastPage);
                            @endphp

                            @for ($i = $startPage; $i <= $endPage; $i++)
                                <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            <!-- Next Page -->
                            <li class="page-item {{ $products->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $products->nextPageUrl() }}" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            <!-- Last Page -->
                            <li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $products->url($lastPage) }}" aria-label="Last">
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
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Auto submit form when changing perPage
            $('#perPage, #stock_status').change(function() {
                $(this).closest('form').submit();
            });
        });

        // Function to change page size
        function changePageSize(size) {
            const url = new URL(window.location.href);
            url.searchParams.set('perPage', size);
            window.location.href = url.toString();
        }
    </script>
@endsection
