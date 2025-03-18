@extends('layouts.master')

@section('title', 'Chỉnh sửa danh mục sản phẩm - HK LOVE')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
{{--                    <div class="card-header">--}}
{{--                        <h3 class="card-title">Chỉnh sửa danh mục sản phẩm</h3>--}}
{{--                    </div>--}}
                    <div class="card-body">
                        <form action="{{ route('product-categories.update', $category->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group pb-3">
                                <label for="name">Tên danh mục <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                                <a href="{{ route('product-categories.index') }}" class="btn btn-secondary">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
