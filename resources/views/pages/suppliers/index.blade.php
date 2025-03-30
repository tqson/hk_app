@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách nhà cung cấp</h5>
                        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm mới
                        </a>
                    </div>
                    <div class="card-body">
                        <!-- Search Box -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <form action="{{ route('suppliers.index') }}" method="GET" class="form-inline">
                                    <div class="input-group w-100">
                                        <input type="text" name="search" class="form-control me-3"
                                               placeholder="Tìm kiếm theo tên nhà cung cấp..."
                                               value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-primary" type="submit">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Suppliers Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                <tr>
                                    <th width="5%">STT</th>
                                    <th width="20%">Tên nhà cung cấp</th>
                                    <th width="20%">Địa chỉ</th>
                                    <th width="15%">Người liên hệ</th>
                                    <th width="10%">Di động</th>
                                    <th width="10%">Trạng thái</th>
                                    <th width="20%">Hoạt động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($suppliers as $index => $supplier)
                                    <tr>
                                        <td>{{ $index + $suppliers->firstItem() }}</td>
                                        <td>{{ $supplier->name }}</td>
                                        <td>{{ $supplier->address ?? 'N/A' }}</td>
                                        <td>{{ $supplier->contact_person }}</td>
                                        <td>{{ $supplier->mobile }}</td>
                                        <td>
                                        <span class="badge text-dark badge-{{ $supplier->status === 'active' ? 'success' : 'danger' }}">
                                            {{ $supplier->status === 'active' ? 'Đang hoạt động' : 'Dừng hoạt động' }}
                                        </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Xem
                                            </a>
                                            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Sửa
                                            </a>
                                            <button type="button" class="btn btn-sm btn-{{ $supplier->status === 'active' ? 'danger' : 'success' }}"
                                                    data-toggle="modal" data-target="#toggleStatusModal{{ $supplier->id }}">
                                                <i class="fas fa-{{ $supplier->status === 'active' ? 'ban' : 'check' }}"></i>
                                                {{ $supplier->status === 'active' ? 'Dừng hoạt động' : 'Kích hoạt' }}
                                            </button>

                                            <!-- Toggle Status Modal -->
                                            <div class="modal fade" id="toggleStatusModal{{ $supplier->id }}" tabindex="-1" role="dialog" aria-labelledby="toggleStatusModalLabel{{ $supplier->id }}" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="toggleStatusModalLabel{{ $supplier->id }}">
                                                                {{ $supplier->status === 'active' ? 'Dừng hoạt động' : 'Kích hoạt' }}
                                                            </h5>
                                                            <button type="button" class="close border-0" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            {{ $supplier->status === 'active'
                                                                ? "Bạn muốn dừng hoạt động nhà cung cấp {$supplier->name} không?"
                                                                : "Bạn muốn kích hoạt nhà cung cấp {$supplier->name} không?"
                                                            }}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                                            <form action="{{ route('suppliers.toggle-status', $supplier) }}" method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-{{ $supplier->status === 'active' ? 'danger' : 'success' }}">
                                                                    Đồng ý
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Không có nhà cung cấp nào</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $suppliers->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
