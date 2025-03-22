@extends('layouts.app')

@section('title', 'Chỉnh sửa sản phẩm - HK LOVE')

@section('page-title', 'Chỉnh sửa sản phẩm')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Chỉnh sửa thông tin sản phẩm</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('products.update', $product->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Tên sản phẩm <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category_id">Danh mục <span class="text-danger">*</span></label>
                                        <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                            <option value="">-- Chọn danh mục --</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="unit">Đơn vị tính <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('unit') is-invalid @enderror" id="unit" name="unit" value="{{ old('unit', $product->unit) }}" required>
                                        @error('unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="batch_number">Số lô <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('batch_number') is-invalid @enderror" id="batch_number" name="batch_number" value="{{ old('batch_number', $product->batch_number) }}" required>
                                        @error('batch_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="expiration_date">Hạn sử dụng <span class="text-danger">*</span></label>
                                        <input type="date"
                                               class="form-control @error('expiration_date') is-invalid @enderror"
                                               id="expiration_date"
                                               name="expiration_date"
                                               value="{{ old('expiration_date', $product->expiration_date ? date('Y-m-d', strtotime($product->expiration_date)) : '') }}"
                                               required>
                                        @error('expiration_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="stock">Số lượng tồn kho <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
                                        @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
