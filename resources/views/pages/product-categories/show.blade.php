@extends('layouts.app')

@section('title', 'Chi tiết nhóm sản phẩm - HK LOVE')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Chi tiết nhóm sản phẩm</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Tên nhóm sản phẩm:</strong>
                            <p>{{ $category->name }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Ghi chú:</strong>
                            <p>{{ $category->note ?? 'Không có ghi chú' }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Ngày tạo:</strong>
                            <p>{{ $category->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Ngày cập nhật:</strong>
                            <p>{{ optional($category->updated_at)->format('d/m/Y') ?: $category->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <a href="{{ route('product-categories.edit', $category->id) }}" class="btn btn-primary">Chỉnh sửa</a>
                            <a href="{{ route('product-categories.index') }}" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
