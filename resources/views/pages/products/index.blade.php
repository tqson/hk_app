@extends('layouts.master')

@section('title', 'Quản lý sản phẩm - HK LOVE')

@section('page-title', 'Quản lý sản phẩm')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách sản phẩm</h3>
                        <div class="card-tools">
                            <a href="{{ route('products.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Thêm sản phẩm mới
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Search and Total Records Count -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <form action="{{ route('products.index') }}" method="GET" class="form-inline">
                                    <div class="input-group">
                                        <input type="search" name="search" class="form-control me-3"
                                               placeholder="Tìm kiếm theo tên sản phẩm" value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="submit">
                                                <i class="fas fa-search"></i> Tìm kiếm
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 text-end">
                            <span class="text-muted">
                                Tổng số: <strong>{{ $products->total() ?? 0 }}</strong> bản ghi
                            </span>
                            </div>
                        </div>

                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên sản phẩm</th>
                                <th>Danh mục</th>
                                <th>Đơn vị</th>
                                <th>Số lô</th>
                                <th>Hạn sử dụng</th>
                                <th>Tồn kho</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>{{ $product->unit }}</td>
                                    <td>{{ $product->batch_number }}</td>
                                    <td>{{ \Carbon\Carbon::parse($product->expiration_date)->format('d/m/Y') }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>
                                    <span class="badge {{ $product->status ? 'badge-success' : 'badge-danger' }}">
                                        {{ $product->status ? 'Hoạt động' : 'Dừng hoạt động' }}
                                    </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('products.show', $product->id) }}"
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Chi tiết
                                        </a>
                                        <a href="{{ route('products.edit', $product->id) }}"
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        @if($product->status)
                                            <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="confirmDeactivate({{ $product->id }}, '{{ $product->name }}')">
                                                <i class="fas fa-ban"></i> Dừng hoạt động
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-success"
                                                    onclick="confirmActivate({{ $product->id }}, '{{ $product->name }}')">
                                                <i class="fas fa-check"></i> Kích hoạt
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

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
                                <span class="mr-2">Hiển thị:</span>
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
                                <span class="ml-2">/ trang</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Deactivate Confirmation -->
    <div class="modal fade" id="deactivateModal" tabindex="-1" role="dialog" aria-labelledby="deactivateModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deactivateModalLabel">Xác nhận dừng hoạt động</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn dừng hoạt động sản phẩm <strong id="productNameDeactivate"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <form id="deactivateForm" action="" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger">Xác nhận</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Activate Confirmation -->
    <div class="modal fade" id="activateModal" tabindex="-1" role="dialog" aria-labelledby="activateModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="activateModalLabel">Xác nhận kích hoạt</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn kích hoạt sản phẩm <strong id="productNameActivate"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <form id="activateForm" action="" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">Xác nhận</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function confirmDeactivate(productId, productName) {
            document.getElementById('productNameDeactivate').textContent = productName;
            document.getElementById('deactivateForm').action = `/products/${productId}/deactivate`;
            $('#deactivateModal').modal('show');
        }

        function confirmActivate(productId, productName) {
            document.getElementById('productNameActivate').textContent = productName;
            document.getElementById('activateForm').action = `/products/${productId}/activate`;
            $('#activateModal').modal('show');
        }

        function changePageSize(perPage) {
            const url = new URL(window.location.href);
            url.searchParams.set('perPage', perPage);
            window.location.href = url.toString();
        }

        // Ensure modals can be closed
        $(document).ready(function () {
            // Initialize all modals
            $('.modal').modal({
                show: false,
                backdrop: 'static',
                keyboard: false
            });

            // Add click handlers for close buttons
            $('[data-dismiss="modal"]').on('click', function () {
                $(this).closest('.modal').modal('hide');
            });
        });
    </script>
@endsection

@section('styles')
    <style>
        .table th, .table td {
            vertical-align: middle;
        }

        /* Ant Design style pagination */
        .pagination {
            display: flex;
            padding-left: 0;
            list-style: none;
            border-radius: 0.25rem;
        }

        .pagination .page-item .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #1890ff;
            background-color: #fff;
            border: 1px solid #d9d9d9;
        }

        .pagination .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #1890ff;
            border-color: #1890ff;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            cursor: auto;
            background-color: #fff;
            border-color: #d9d9d9;
        }

        .pagination-size-selector {
            display: flex;
            align-items: center;
        }

        .pagination-size-selector select {
            margin: 0 5px;
        }

        /* Improve table appearance */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f5f5f5;
            border-bottom: 1px solid #dee2e6;
        }
    </style>
@endsection
