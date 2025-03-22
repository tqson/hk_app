@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Chi tiết hóa đơn trả hàng
                            #TH{{ str_pad($returnInvoice->id, 6, '0', STR_PAD_LEFT) }}</h5>
                        <div>
{{--                            <button onclick="window.print();" class="btn btn-primary mr-2">--}}
{{--                                <i class="fas fa-print"></i> In hóa đơn--}}
{{--                            </button>--}}
                            <a href="{{ route('returns.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p><strong>Mã hóa đơn trả hàng:</strong>
                                    TH{{ str_pad($returnInvoice->id, 6, '0', STR_PAD_LEFT) }}</p>
                                <p><strong>Mã hóa đơn bán hàng:</strong>
                                    HD{{ str_pad($returnInvoice->salesInvoice->id, 6, '0', STR_PAD_LEFT) }}</p>
                                <p><strong>Ngày
                                        tạo:</strong> {{ Carbon\Carbon::parse($returnInvoice->created_at)->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Người tạo:</strong> {{ $returnInvoice->user->full_name }}</p>
                                <p><strong>Ngày mua
                                        hàng:</strong> {{ Carbon\Carbon::parse($returnInvoice->salesInvoice->created_at)->format('d/m/Y H:i') }}
                                </p>
                                <p><strong>Ghi chú:</strong> {{ $returnInvoice->notes ?? 'Không có' }}</p>
                            </div>
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                <tr>
                                    <th width="5%">STT</th>
                                    <th width="25%">Tên sản phẩm</th>
                                    <th width="10%">Đơn vị</th>
                                    <th width="15%">Số lượng hoàn trả</th>
                                    <th width="15%">Đơn giá</th>
                                    <th width="15%">Thành tiền</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($returnInvoice->details as $index => $detail)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $detail->product->name }}</td>
                                        <td>{{ $detail->product->unit }}</td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td class="text-right">{{ number_format($detail->price, 0, ',', '.') }} VND</td>
                                        <td class="text-right">{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}
                                            VND
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5" class="text-right font-weight-bold">Tổng tiền hoàn trả:</td>
                                    <td class="text-right font-weight-bold">{{ number_format($returnInvoice->total_amount, 0, ',', '.') }}
                                        VND
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Thông tin hóa đơn bán hàng gốc</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Mã hóa đơn:</strong>
                                                    HD{{ str_pad($returnInvoice->salesInvoice->id, 6, '0', STR_PAD_LEFT) }}
                                                </p>
                                                <p><strong>Ngày
                                                        tạo:</strong> {{ Carbon\Carbon::parse($returnInvoice->salesInvoice->created_at)->format('d/m/Y H:i') }}
                                                </p>
                                                <p><strong>Người
                                                        tạo:</strong> {{ $returnInvoice->salesInvoice->user->full_name }}
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Tổng
                                                        tiền:</strong> {{ number_format($returnInvoice->salesInvoice->total_amount, 0, ',', '.') }}
                                                    VND</p>
                                                <p><strong>Chiết
                                                        khấu:</strong> {{ number_format($returnInvoice->salesInvoice->discount, 0, ',', '.') }}
                                                    VND</p>
                                                <p><strong>Thanh
                                                        toán:</strong> {{ number_format($returnInvoice->salesInvoice->total_amount - $returnInvoice->salesInvoice->discount, 0, ',', '.') }}
                                                    VND</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .card, .card * {
                visibility: visible;
            }

            .card {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .btn, .no-print {
                display: none !important;
            }

            .card-header {
                text-align: center;
                border-bottom: 1px solid #000;
            }

            .table {
                width: 100%;
                border-collapse: collapse;
            }

            .table th, .table td {
                border: 1px solid #000;
                padding: 8px;
            }
        }
    </style>
@endsection
