@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Sửa nhà cung cấp</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group row mb-3">
                                <label for="name" class="col-md-3 col-form-label text-md-right">Tên nhà cung cấp <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $supplier->name) }}" required>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="contact_person" class="col-md-3 col-form-label text-md-right">Người liên hệ <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" id="contact_person" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror" value="{{ old('contact_person', $supplier->contact_person) }}" required>
                                    @error('contact_person')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="address" class="col-md-3 col-form-label text-md-right">Địa chỉ</label>
                                <div class="col-md-9">
                                    <input type="text" id="address" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $supplier->address) }}">
                                    @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="mobile" class="col-md-3 col-form-label text-md-right">Di động <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" id="mobile" name="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile', $supplier->mobile) }}" required maxlength="10">
                                    <small class="form-text text-muted">Nhập đúng 10 số</small>
                                    @error('mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="phone" class="col-md-3 col-form-label text-md-right">Điện thoại</label>
                                <div class="col-md-9">
                                    <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $supplier->phone) }}" maxlength="11">
                                    <small class="form-text text-muted">Nhập đúng 10 số</small>
                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="tax_code" class="col-md-3 col-form-label text-md-right">Mã số thuế</label>
                                <div class="col-md-9">
                                    <input type="text" id="tax_code" name="tax_code" class="form-control @error('tax_code') is-invalid @enderror" value="{{ old('tax_code', $supplier->tax_code) }}" maxlength="10">
                                    <small class="form-text text-muted">Nhập đúng 10 số</small>
                                    @error('tax_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-9 offset-md-3 text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Lưu
                                    </button>
                                    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Hủy
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Chỉ cho phép nhập số vào các trường số điện thoại và mã số thuế
        $(document).ready(function() {
            $('#mobile, #phone, #tax_code').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    </script>
@endsection
