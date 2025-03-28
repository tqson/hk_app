@extends('layouts.app')

@section('title', 'Quản lý nhóm sản phẩm - HK LOVE')

{{--@section('page-title', 'Quản lý nhóm sản phẩm')--}}

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách nhóm sản phẩm</h3>
                        <div class="card-tools">
                            <a href="{{ route('product-categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Thêm nhóm sản phẩm mới
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

                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên danh mục</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $index => $category)
                                <tr>
                                    <td>{{ $categories->firstItem() + $index }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->created_at->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('product-categories.show', $category->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Chi tiết
                                        </a>
                                        <a href="{{ route('product-categories.edit', $category->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <form action="{{ route('product-categories.destroy', $category->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">
                                                <i class="fas fa-trash"></i> Xóa
                                            </button>
                                        </form>
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
                                        <li class="page-item {{ $categories->onFirstPage() ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $categories->url(1) }}" aria-label="First">
                                                <span aria-hidden="true">&laquo;&laquo;</span>
                                            </a>
                                        </li>
                                        <!-- Previous Page -->
                                        <li class="page-item {{ $categories->onFirstPage() ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $categories->previousPageUrl() }}"
                                               aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <!-- Page Numbers -->
                                        @php
                                            $currentPage = $categories->currentPage();
                                            $lastPage = $categories->lastPage();
                                            $startPage = max($currentPage - 2, 1);
                                            $endPage = min($currentPage + 2, $lastPage);
                                        @endphp
                                        @for ($i = $startPage; $i <= $endPage; $i++)
                                            <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $categories->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endfor
                                        <!-- Next Page -->
                                        <li class="page-item {{ $categories->hasMorePages() ? '' : 'disabled' }}">
                                            <a class="page-link" href="{{ $categories->nextPageUrl() }}" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                        <!-- Last Page -->
                                        <li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $categories->url($lastPage) }}" aria-label="Last">
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

@section('script')
    <script>
        function changePageSize(size) {
            const url = new URL(window.location.href);
            url.searchParams.set('perPage', size);
            window.location.href = url.toString();
        }
    </script>
@endsection
