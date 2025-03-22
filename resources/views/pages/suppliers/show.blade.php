@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Chi tiết nhà cung cấp</h5>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th width="30%">Tên nhà cung cấp</th>
                                    <td>{{ $supplier->name }}</td>
                                </tr>
                                <tr>
                                    <th>Người liên hệ</th>
                                    <td>{{ $supplier->contact_person }}</td>
                                </tr>
                                <tr>
                                    <th>Địa chỉ</th>
                                    <td>{{ $supplier->address ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Di động</th>
                                    <td>{{ $supplier->mobile }}</td>
                                </tr>
                                <tr>
                                    <th>Điện thoại</th>
                                    <td>{{ $supplier->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Mã số thuế</th>
                                    <td>{{ $supplier->tax_code ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Trạng thái</th>
                                    <td>
                                        <span class="badge badge-{{ $supplier->status === 'active' ? 'success' : 'danger' }}">
                                            {{ $supplier->status === 'active' ? 'Đang hoạt động' : 'Dừng hoạt động' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày tạo</th>
                                    <td>{{ $supplier->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Cập nhật lần cuối</th>
                                    <td>{{ $supplier->updated_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
