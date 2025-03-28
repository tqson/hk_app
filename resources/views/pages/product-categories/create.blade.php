@extends('layouts.app')

@section('title', 'Thêm nhóm sản phẩm - HK LOVE')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thêm nhóm sản phẩm mới</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('product-categories.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="name">Tên danh mục <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group pb-3">
                                <label for="note">Ghi chú</label>
                                <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" rows="3">{{ old('note') }}</textarea>
                                @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Lưu danh mục</button>
                                <a href="{{ route('product-categories.index') }}" class="btn btn-secondary">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
