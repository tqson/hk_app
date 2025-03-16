@extends('layouts.master')

@section('title', 'Chi tiết sản phẩm - HK LOVE')

@section('page-title', 'Chi tiết sản phẩm')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin chi tiết sản phẩm</h3>
                        <div class="card-tools">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Chỉnh sửa
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 200px;">ID sản phẩm</th>
                                <td>{{ $product->id }}</td>
                            </tr>
                            <tr>
                                <th>Tên sản phẩm</th>
                                <td>{{ $product->name }}</td>
                            </tr>
                            <tr>
                                <th>Danh mục</th>
                                <td>{{ $product->category->name }}</td>
                            </tr>
                            <tr>
                                <th>Đơn vị tính</th>
                                <td>{{ $product->unit }}</td>
                            </tr>
                            <tr>
                                <th>Số lô</th>
                                <td>{{ $product->batch_number }}</td>
                            </tr>
                            <tr>
                                <th>Hạn sử dụng</th>
                                <td>{{ \Carbon\Carbon::parse($product->expiration_date)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Số lượng tồn kho</th>
                                <td>{{ $product->stock }}</td>
                            </tr>
                            <tr>
                                <th>Ngày tạo</th>
                                <td>{{ $product->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
