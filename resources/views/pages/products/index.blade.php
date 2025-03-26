@extends('layouts.app')

@section('title', 'Quản lý sản phẩm - HK LOVE')

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

        /* Action dropdown styles */
        .action-dropdown {
            position: relative;
        }

        .action-dropdown-toggle {
            background: none;
            border: none;
            color: #1890ff;
            cursor: pointer;
            padding: 8px;
            border-radius: 4px;
        }

        .action-dropdown-toggle:hover {
            background-color: rgba(24, 144, 255, 0.1);
        }

        .action-dropdown-menu {
            position: absolute;
            right: 0;
            z-index: 1000;
            min-width: 180px;
            padding: 8px 0;
            margin: 0;
            background-color: #fff;
            border-radius: 4px;
            box-shadow: 0 3px 6px -4px rgba(0, 0, 0, 0.12), 0 6px 16px 0 rgba(0, 0, 0, 0.08), 0 9px 28px 8px rgba(0, 0, 0, 0.05);
            display: none;
        }

        .action-dropdown-menu.show {
            display: block;
        }

        .action-dropdown-item {
            display: flex;
            align-items: center;
            padding: 8px 16px;
            color: rgba(0, 0, 0, 0.85);
            font-size: 14px;
            line-height: 22px;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
            text-decoration: none;
        }

        .action-dropdown-item:hover {
            background-color: rgba(24, 144, 255, 0.1);
        }

        .action-dropdown-item i {
            margin-right: 8px;
            font-size: 14px;
            width: 16px;
            text-align: center;
        }

        .action-dropdown-divider {
            height: 1px;
            margin: 4px 0;
            background-color: rgba(0, 0, 0, 0.06);
        }

        .action-dropdown-item.text-danger {
            color: #ff4d4f;
        }

        .action-dropdown-item.text-danger:hover {
            background-color: rgba(255, 77, 79, 0.1);
        }

        .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .bg-success {
            background-color: #52c41a;
        }

        .bg-danger {
            background-color: #ff4d4f;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Quản lý sản phẩm</h1>

        <!-- Filters and Search -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tìm kiếm và lọc</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('products.index') }}" method="GET" class="mb-0">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="search">Tìm kiếm:</label>
                            <input type="text" class="form-control" id="search" name="search"
                                   placeholder="Tên sản phẩm" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="category_id">Danh mục:</label>
                            <select class="form-control" id="category_id" name="category_id">
                                <option value="">Tất cả danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="status">Trạng thái:</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Tất cả trạng thái</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Dừng hoạt động</option>
                            </select>
                        </div>
{{--                        <div class="col-md-2 mb-3">--}}
{{--                            <label for="stock_filter">Tồn kho:</label>--}}
{{--                            <select class="form-control" id="stock_filter" name="stock_filter">--}}
{{--                                <option value="">Tất cả</option>--}}
{{--                                <option value="in_stock" {{ request('stock_filter') == 'in_stock' ? 'selected' : '' }}>Còn hàng</option>--}}
{{--                                <option value="out_of_stock" {{ request('stock_filter') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}

                        <div class="col-md-1 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-end">
{{--                <h6 class="m-0 font-weight-bold text-primary">Danh sách sản phẩm</h6>--}}
                <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Thêm sản phẩm
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên sản phẩm</th>
                            <th>Mã sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Đơn vị</th>
                            <th>Giá bán</th>
                            <th>Tồn kho</th>
                            <th>Lô hàng</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light me-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="fas fa-pills text-secondary"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-weight-bold">{{ $product->name }}</div>
                                            @if($product->barcode)
                                                <small class="text-muted">{{ $product->barcode }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                <td>{{ $product->unit }}</td>
                                <td>{{ number_format($product->price, 0, ',', '.') }} đ</td>
                                <td>
                                    <span class="font-weight-bold {{ $product->getTotalStockAttribute() > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $product->getTotalStockAttribute() }}
                                    </span>
                                </td>
                                <td>
                                    @if($product->batches->count() > 0)
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#batchModal{{ $product->id }}">
                                            {{ $product->batches->count() }} lô
                                        </button>

                                        <!-- Modal hiển thị thông tin lô -->
                                        <div class="modal fade" id="batchModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="batchModalLabel{{ $product->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="batchModalLabel{{ $product->id }}">Thông tin lô hàng - {{ $product->name }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                <tr>
                                                                    <th>Số lô</th>
                                                                    <th>Ngày sản xuất</th>
                                                                    <th>Hạn sử dụng</th>
                                                                    <th>Số lượng</th>
                                                                    <th>Trạng thái</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($product->batches as $batch)
                                                                    <tr>
                                                                        <td>{{ $batch->batch_number }}</td>
                                                                        <td>{{ $batch->manufacturing_date->format('d/m/Y') }}</td>
                                                                        <td>
                                                                            {{ $batch->expiry_date->format('d/m/Y') }}
                                                                        </td>
                                                                        <td>{{ $batch->quantity }}</td>
                                                                        <td>
                                                                            @if($batch->status == 'active')
                                                                                <span class="badge badge-success">Đang sử dụng</span>
                                                                            @elseif($batch->status == 'inactive')
                                                                                <span class="badge badge-success">Ngừng sử dụng</span>
                                                                            @else
                                                                                <span class="badge badge-danger">Hết hạn</span>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Không có lô</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->status)
                                        <div class="d-flex align-items-center">
                                            <span class="status-dot bg-success mr-2"></span>
                                            <span>Hoạt động</span>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <span class="status-dot bg-danger mr-2"></span>
                                            <span>Dừng hoạt động</span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-dropdown">
                                        <button type="button" class="action-dropdown-toggle w-100" onclick="toggleActionMenu({{ $product->id }})">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div id="actionDropdown{{ $product->id }}" class="action-dropdown-menu">
                                            <a href="{{ route('products.show', $product->id) }}" class="action-dropdown-item">
                                                <i class="fas fa-eye"></i> Xem chi tiết
                                            </a>
                                            @if($product->status)
                                                <a href="{{ route('products.edit', $product->id) }}" class="action-dropdown-item">
                                                    <i class="fas fa-edit"></i> Chỉnh sửa
                                                </a>
                                                <a href="javascript:void(0)" class="action-dropdown-item text-danger" onclick="confirmDeactivate('{{ $product->id }}', '{{ $product->name }}')">
                                                    <i class="fas fa-ban"></i> Dừng hoạt động
                                                </a>
                                            @endif
                                            <div class="action-dropdown-divider"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-box-open fa-3x text-gray-300 mb-3"></i>
                                        <h5 class="text-gray-700">Không tìm thấy sản phẩm nào</h5>
                                        <p class="text-gray-500">Hãy thêm sản phẩm mới hoặc thay đổi bộ lọc tìm kiếm</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
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

    <!-- Modal for Delete Confirmation -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa sản phẩm <strong id="productNameDelete"></strong>?</p>
                    <p class="text-danger">Lưu ý: Hành động này không thể hoàn tác.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <form id="deleteForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // Toggle action dropdown menu
        function toggleActionMenu(productId) {
            // Close all other open menus first
            document.querySelectorAll('.action-dropdown-menu.show').forEach(menu => {
                if (menu.id !== `actionDropdown${productId}`) {
                    menu.classList.remove('show');
                }
            });

            // Toggle the clicked menu
            const menu = document.getElementById(`actionDropdown${productId}`);
            menu.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.action-dropdown')) {
                document.querySelectorAll('.action-dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });

        function confirmDeactivate(productId, productName) {
            document.getElementById('productNameDeactivate').textContent = productName;
            document.getElementById('deactivateForm').action = `/products/${productId}/deactivate`;
            $('#deactivateModal').modal('show');

            // Close the dropdown
            document.getElementById(`actionDropdown${productId}`).classList.remove('show');
        }

        function confirmActivate(productId, productName) {
            document.getElementById('productNameActivate').textContent = productName;
            document.getElementById('activateForm').action = `/products/${productId}/activate`;
            $('#activateModal').modal('show');

            // Close the dropdown
            document.getElementById(`actionDropdown${productId}`).classList.remove('show');
        }

        function confirmDelete(productId, productName) {
            document.getElementById('productNameDelete').textContent = productName;
            document.getElementById('deleteForm').action = `/products/${productId}`;
            $('#deleteModal').modal('show');

            // Close the dropdown
            document.getElementById(`actionDropdown${productId}`).classList.remove('show');
        }

        function changePageSize(perPage) {
            const url = new URL(window.location.href);
            url.searchParams.set('perPage', perPage);
            window.location.href = url.toString();
        }

        $(document).ready(function() {
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

            // Auto submit form when changing category
            $('#category_id').change(function() {
                $(this).closest('form').submit();
            });

            // Auto submit form when changing status
            $('#status').change(function() {
                $(this).closest('form').submit();
            });

            // Auto submit form when changing stock filter
            $('#stock_filter').change(function() {
                $(this).closest('form').submit();
            });
        });
    </script>
@endsection
