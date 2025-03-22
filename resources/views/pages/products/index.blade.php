@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Quản lý sản phẩm</h1>
        <p class="mb-4">Danh sách tất cả sản phẩm trong hệ thống.</p>

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
                                   placeholder="Tên, SKU, mã vạch..." value="{{ request('search') }}">
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
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang kinh doanh</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Ngừng kinh doanh</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="stock_filter">Tồn kho:</label>
                            <select class="form-control" id="stock_filter" name="stock_filter">
                                <option value="">Tất cả</option>
                                <option value="in_stock" {{ request('stock_filter') == 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                                <option value="out_of_stock" {{ request('stock_filter') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
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
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Danh sách sản phẩm</h6>
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
                            <th>SKU</th>
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
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail mr-2" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light mr-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
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
                                                                            @if($batch->expiry_date->isPast())
                                                                                <span class="badge badge-danger">Hết hạn</span>
                                                                            @elseif($batch->expiry_date->diffInMonths(now()) <= 3)
                                                                                <span class="badge badge-warning">Sắp hết hạn</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ $batch->quantity }}</td>
                                                                        <td>
                                                                            @if($batch->status == 'active')
                                                                                <span class="badge badge-success">Đang sử dụng</span>
                                                                            @elseif($batch->status == 'inactive')
                                                                                <span class="badge badge-secondary">Ngừng sử dụng</span>
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
                                    @if($product->status == 'active')
                                        <span class="badge badge-success">Đang kinh doanh</span>
                                    @else
                                        <span class="badge badge-secondary">Ngừng kinh doanh</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{ $product->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Modal xác nhận xóa -->
                                    <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $product->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $product->id }}">Xác nhận xóa</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Bạn có chắc chắn muốn xóa sản phẩm <strong>{{ $product->name }}</strong>?
                                                    <p class="text-danger mt-2">Lưu ý: Hành động này không thể hoàn tác và sẽ xóa tất cả dữ liệu liên quan đến sản phẩm này.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Xóa</button>
                                                    </form>
                                                </div>
                                            </div>
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
                                        <a href="{{ route('products.create') }}" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus"></i> Thêm sản phẩm mới
                                        </a>
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

@push('scripts')
    <script>
        $(document).ready(function() {
            // Auto submit form when changing perPage
            $('#perPage').change(function() {
                $(this).closest('form').submit();
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
@endpush

