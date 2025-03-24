@extends('layouts.app')

@section('title', 'Chỉnh sửa sản phẩm - HK LOVE')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Chỉnh sửa sản phẩm</h1>
        <p class="mb-4">Cập nhật thông tin chi tiết cho sản phẩm "{{ $product->name }}".</p>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin sản phẩm</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name">Tên sản phẩm <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sku">Mã SKU <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                                    @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="barcode">Mã vạch</label>
                                    <input type="text" class="form-control @error('barcode') is-invalid @enderror" id="barcode" name="barcode" value="{{ old('barcode', $product->barcode) }}">
                                    @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="category_id">Danh mục <span class="text-danger">*</span></label>
                                    <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="unit">Đơn vị tính <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('unit') is-invalid @enderror" id="unit" name="unit" value="{{ old('unit', $product->unit) }}" required>
                                    @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="price">Giá bán <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" min="0" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">đ</span>
                                        </div>
                                    </div>
                                    @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="description">Mô tả sản phẩm</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
{{--                            <div class="form-group mb-3">--}}
{{--                                <label for="image">Hình ảnh sản phẩm</label>--}}
{{--                                <div class="custom-file">--}}
{{--                                    <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image">--}}
{{--                                    <label class="custom-file-label" for="image">Chọn file</label>--}}
{{--                                    @error('image')--}}
{{--                                    <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                                <div class="mt-2" id="imagePreview">--}}
{{--                                    @if($product->image)--}}
{{--                                        <img src="{{ asset('storage/' . $product->image) }}" class="img-thumbnail" style="max-height: 200px;">--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <div class="form-group mb-3">
                                <label for="status">Trạng thái</label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="1" {{ old('status', $product->status) == 1 ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="0" {{ old('status', $product->status) == 0 ? 'selected' : '' }}>Dừng hoạt động</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="card bg-light mt-3">
                                <div class="card-body">
                                    <h6 class="font-weight-bold">Thông tin tồn kho</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Tổng tồn kho:</span>
                                        <span class="font-weight-bold {{ $product->getTotalStockAttribute() > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $product->getTotalStockAttribute() }} {{ $product->unit }}
                                    </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span>Tồn kho cơ bản:</span>
                                        <span>{{ $product->stock }} {{ $product->unit }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span>Tồn kho theo lô:</span>
                                        <span>{{ $product->batches->sum('quantity') }} {{ $product->unit }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h6 class="font-weight-bold">Quản lý lô hàng</h6>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Số lô</th>
                                <th>Ngày sản xuất</th>
                                <th>Hạn sử dụng</th>
                                <th>Số lượng</th>
                                <th>Giá nhập</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                            </thead>
                            <tbody id="batchesTable">
                            @foreach($product->batches as $index => $batch)
                                <tr>
                                    <td>
                                        <input type="hidden" name="batches[{{ $index }}][id]" value="{{ $batch->id }}">
                                        <input type="text" class="form-control" name="batches[{{ $index }}][batch_number]" value="{{ $batch->batch_number }}" required>
                                    </td>
                                    <td>
                                        <input type="date" class="form-control" name="batches[{{ $index }}][manufacturing_date]" value="{{ $batch->manufacturing_date->format('Y-m-d') }}" required>
                                    </td>
                                    <td>
                                        <input type="date" class="form-control" name="batches[{{ $index }}][expiry_date]" value="{{ $batch->expiry_date->format('Y-m-d') }}" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="batches[{{ $index }}][quantity]" value="{{ $batch->quantity }}" min="0" required>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="batches[{{ $index }}][import_price]" value="{{ $batch->import_price }}" min="0" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">đ</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <select class="form-control" name="batches[{{ $index }}][status]">
                                            <option value="active" {{ $batch->status == 'active' ? 'selected' : '' }}>Đang sử dụng</option>
                                            <option value="inactive" {{ $batch->status == 'inactive' ? 'selected' : '' }}>Ngừng sử dụng</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger remove-batch" data-batch-id="{{ $batch->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-info mb-4" id="addBatchBtn">
                        <i class="fas fa-plus"></i> Thêm lô mới
                    </button>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Cập nhật sản phẩm
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Image preview
        // document.getElementById('image').addEventListener('change', function(e) {
        //     const file = e.target.files[0];
        //     if (file) {
        //         const reader = new FileReader();
        //         reader.onload = function(event) {
        //             const preview = document.getElementById('imagePreview');
        //             preview.innerHTML = `<img src="${event.target.result}" class="img-thumbnail mt-2" style="max-height: 200px;">`;
        //         }
        //         reader.readAsDataURL(file);
        //         document.querySelector('.custom-file-label').textContent = file.name;
        //     }
        // });

        // Add new batch
        let batchIndex = {{ count($product->batches) }};
        document.getElementById('addBatchBtn').addEventListener('click', function() {
            const today = new Date().toISOString().split('T')[0];
            const newRow = `
            <tr>
                <td>
                    <input type="text" class="form-control" name="batches[${batchIndex}][batch_number]" required>
                </td>
                <td>
                    <input type="date" class="form-control" name="batches[${batchIndex}][manufacturing_date]" value="${today}" required>
                </td>
                <td>
                    <input type="date" class="form-control" name="batches[${batchIndex}][expiry_date]" required>
                </td>
                <td>
                    <input type="number" class="form-control" name="batches[${batchIndex}][quantity]" value="0" min="0" required>
                </td>
                <td>
                    <div class="input-group">
                        <input type="number" class="form-control" name="batches[${batchIndex}][import_price]" value="0" min="0" required>
                        <div class="input-group-append">
                            <span class="input-group-text">đ</span>
                        </div>
                    </div>
                </td>
                <td>
                    <select class="form-control" name="batches[${batchIndex}][status]">
                        <option value="active" selected>Đang sử dụng</option>
                        <option value="inactive">Ngừng sử dụng</option>
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-batch">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
            document.getElementById('batchesTable').insertAdjacentHTML('beforeend', newRow);
            batchIndex++;

            // Add event listeners to new remove buttons
            attachRemoveEvents();
        });

        // Remove batch row
        function attachRemoveEvents() {
            document.querySelectorAll('.remove-batch').forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('Bạn có chắc chắn muốn xóa lô hàng này?')) {
                        const batchId = this.getAttribute('data-batch-id');
                        if (batchId) {
                            // If this is an existing batch, add a hidden field to mark it for deletion
                            const form = this.closest('form');
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'delete_batches[]';
                            hiddenInput.value = batchId;
                            form.appendChild(hiddenInput);
                        }
                        this.closest('tr').remove();
                    }
                });
            });
        }

        // Initial attachment
        attachRemoveEvents();
    </script>
@endsection

