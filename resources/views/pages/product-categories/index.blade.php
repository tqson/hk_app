@extends('layouts.app')

@section('title', 'Quản lý danh mục sản phẩm - HK LOVE')

{{--@section('page-title', 'Quản lý danh mục sản phẩm')--}}

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách danh mục sản phẩm</h3>
                        <div class="card-tools">
                            <a href="{{ route('product-categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Thêm danh mục mới
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
                                <th>ID</th>
                                <th>Tên danh mục</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
