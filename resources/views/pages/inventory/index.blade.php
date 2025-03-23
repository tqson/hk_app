@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Kiểm tra tồn kho</h1>
        <p class="mb-4">Quản lý và theo dõi tồn kho sản phẩm.</p>

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
                        <div class="col-md-2 mb-3">
                            <label for="perPage">Hiển thị:</label>
                            <select class="form-control" id="perPage" name="perPage">
                                <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
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
                            <th width="5%">STT</th>
                            <th width="25%">Tên sản phẩm</th>
                            <th width="10%">Đơn vị</th>
                            <th width="20%">Số lô</th>
                            <th width="15%">Hạn sử dụng</th>
                            <th width="15%">Số lượng tồn kho</th>
                            <th width="10%">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($products as $index => $product)
                            @if($product->batches->count() > 0)
                                @foreach($product->batches as $batch)
                                    @if($batch->quantity > 0)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->unit }}</td>
                                            <td>{{ $batch->batch_number }}</td>
                                            <td>
                                                {{ $batch->expiry_date->format('d/m/Y') }}
                                                @if($batch->expiry_date->isPast())
                                                    <span class="badge badge-danger">Hết hạn</span>
                                                @elseif($batch->expiry_date->diffInMonths(now()) <= 3)
                                                    <span class="badge badge-warning">Sắp hết hạn</span>
                                                @endif
                                            </td>
                                            <td>
                                            <span class="font-weight-bold {{ $batch->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $batch->quantity }}
                                            </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('disposal.create') }}?product_id={{ $product->id }}&batch_id={{ $batch->id }}" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash-alt"></i> Hủy
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                @if($product->stock > 0)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->unit }}</td>
                                        <td><span class="text-muted">Không có lô</span></td>
                                        <td><span class="text-muted">N/A</span></td>
                                        <td>
                                        <span class="font-weight-bold {{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $product->stock }}
                                        </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('disposal.create') }}?product_id={{ $product->id }}" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i> Hủy
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
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

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        Hiển thị {{ $products->firstItem() ?? 0 }} đến {{ $products->lastItem() ?? 0 }} của {{ $products->total() }} sản phẩm
                    </div>
                    <div>
                        {{ $products->links() }}
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
    </script>
@endsection
