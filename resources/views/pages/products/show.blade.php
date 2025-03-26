@extends('layouts.app')

@section('title', 'Chi tiết sản phẩm - HK LOVE')

@section('content')
    <div class="container-fluid">
        <!-- Tiêu đề trang -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Chi tiết sản phẩm</h1>
            <div>
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> Chỉnh sửa
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        <!-- Thông tin cơ bản -->
        <div class="row">
{{--            <div class="col-xl-4 col-md-5">--}}
{{--                <div class="card shadow mb-4">--}}
{{--                    <div class="card-header py-3">--}}
{{--                        <h6 class="m-0 font-weight-bold text-primary">Hình ảnh sản phẩm</h6>--}}
{{--                    </div>--}}
{{--                    <div class="card-body text-center">--}}
{{--                        @if($product->image)--}}
{{--                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid mb-3" style="max-height: 300px;">--}}
{{--                        @else--}}
{{--                            <div class="text-center p-4 bg-light mb-3">--}}
{{--                                <i class="fas fa-image fa-3x text-gray-400"></i>--}}
{{--                                <p class="mt-2 text-gray-500">Chưa có hình ảnh</p>--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        <div class="card bg-light mt-3">--}}
{{--                            <div class="card-body">--}}
{{--                                <h6 class="font-weight-bold">Thông tin tồn kho</h6>--}}
{{--                                <div class="d-flex justify-content-between align-items-center">--}}
{{--                                    <span>Tổng tồn kho:</span>--}}
{{--                                    <span class="font-weight-bold {{ $product->getTotalStockAttribute() > 0 ? 'text-success' : 'text-danger' }}">--}}
{{--                                    {{ $product->getTotalStockAttribute() }} {{ $product->unit }}--}}
{{--                                </span>--}}
{{--                                </div>--}}
{{--                                <div class="d-flex justify-content-between align-items-center mt-2">--}}
{{--                                    <span>Tồn kho theo lô:</span>--}}
{{--                                    <span>{{ $product->batches->sum('quantity') }} {{ $product->unit }}</span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="col-10 col-md-9">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thông tin sản phẩm</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 40%">Tên sản phẩm:</th>
                                        <td>{{ $product->name }}</td>
                                    </tr>
{{--                                    <tr>--}}
{{--                                        <th>Mã sản phẩm:</th>--}}
{{--                                        <td>{{ $product->sku }}</td>--}}
{{--                                    </tr>--}}
                                    <tr>
                                        <th>Mã vạch:</th>
                                        <td>{{ $product->barcode ?? 'Chưa có' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Danh mục:</th>
                                        <td>{{ $product->category->name ?? 'Không có' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 40%">Đơn vị tính:</th>
                                        <td>{{ $product->unit }}</td>
                                    </tr>
                                    <tr>
                                        <th>Giá bán:</th>
                                        <td>{{ number_format($product->price) }} đ</td>
                                    </tr>
                                    <tr>
                                        <th>Trạng thái:</th>
                                        <td>
                                            @if($product->status == 1)
                                                <span class="badge badge-success">Hoạt động</span>
                                            @else
                                                <span class="badge badge-danger">Dừng hoạt động</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Ngày tạo:</th>
                                        <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="font-weight-bold">Mô tả sản phẩm:</h6>
                                <p class="text-justify">{{ $product->description ?? 'Không có mô tả' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin lô hàng -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Danh sách lô hàng</h6>
            </div>
            <div class="card-body">
                @if($product->batches->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Số lô</th>
                                <th>Ngày sản xuất</th>
                                <th>Hạn sử dụng</th>
                                <th>Số lượng</th>
                                <th>Giá nhập</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($product->batches as $batch)
                                <tr>
                                    <td>{{ $batch->batch_number }}</td>
                                    <td>{{ $batch->manufacturing_date->format('d/m/Y') }}</td>
                                    <td>
                                        {{ $batch->expiry_date->format('d/m/Y') }}
                                        @if($batch->expiry_date->isPast())
                                            <span class="badge badge-danger ml-1">Hết hạn</span>
                                        @elseif($batch->expiry_date->diffInDays(now()) <= 30)
                                            <span class="badge badge-warning ml-1">Sắp hết hạn</span>
                                        @endif
                                    </td>
                                    <td>{{ $batch->quantity }} {{ $product->unit }}</td>
                                    <td>{{ number_format($batch->import_price) }} đ</td>
                                    <td>
                                        @if($batch->status == 'active')
                                            <span class="badge badge-success">Đang sử dụng</span>
                                        @else
                                            <span class="badge badge-secondary">Ngừng sử dụng</span>
                                        @endif
                                    </td>
                                    <td>{{ $batch->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center p-4">
                        <i class="fas fa-box-open fa-3x text-gray-400 mb-3"></i>
                        <p class="text-gray-500">Sản phẩm chưa có lô hàng nào</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Lịch sử nhập hàng -->
        @if($product->importItems->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lịch sử nhập hàng</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Mã phiếu nhập</th>
                                <th>Ngày nhập</th>
                                <th>Số lô</th>
                                <th>Số lượng</th>
                                <th>Giá nhập</th>
                                <th>Thành tiền</th>
                                <th>Nhà cung cấp</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($product->importItems as $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('imports.show', $item->import_id) }}">
                                            {{ $item->import->code ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td>{{ $item->import->import_date->format('d/m/Y') ?? 'N/A' }}</td>
                                    <td>{{ $item->batch->batch_number ?? 'N/A' }}</td>
                                    <td>{{ $item->quantity }} {{ $product->unit }}</td>
                                    <td>{{ number_format($item->price) }} đ</td>
                                    <td>{{ number_format($item->quantity * $item->price) }} đ</td>
                                    <td>{{ $item->import->supplier->name ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
