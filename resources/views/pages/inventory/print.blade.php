<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo kiểm kê</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 10px;
            width: 100%;
        }

        /* Tiêu đề báo cáo */
        .report-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .report-date {
            font-size: 12pt;
            margin-bottom: 15px;
            text-align: center;
            font-weight: 500;
        }

        .report-title {
            font-size: 18pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 15px 0;
        }

        /* Bảng dữ liệu */
        .report-table {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            table-layout: fixed; /* Quan trọng: Giúp cố định chiều rộng cột */
        }

        .report-table th,
        .report-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            font-size: 10pt;
            word-wrap: break-word; /* Cho phép ngắt từ khi quá dài */
            overflow: hidden;
        }

        .report-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        /* Phần chữ ký */
        .signature-section {
            text-align: right;
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .signature-date {
            margin-bottom: 10px;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .signature-note {
            font-style: italic;
            margin-bottom: 40px;
        }

        .signature-name {
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Điều khiển in ấn */
        .print-controls {
            margin-bottom: 20px;
            background: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
        }

        .print-btn {
            padding: 8px 15px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }

        .close-btn {
            padding: 8px 15px;
            background: #f44336;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Định dạng cho in ấn */
        @media print {
            @page {
                size: A4 landscape; /* Thay đổi sang chế độ ngang */
                margin: 0.5cm; /* Giảm margin xuống */
            }

            html, body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
            }

            body {
                padding: 5mm;
                font-size: 10pt; /* Giảm font size khi in */
            }

            .print-controls {
                display: none;
            }

            .report-table {
                width: 100%;
                max-width: 100%;
                page-break-inside: auto;
            }

            .report-table th {
                background-color: #f2f2f2 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .signature-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
<div class="print-controls">
    <button class="print-btn" onclick="window.print()">In báo cáo</button>
    <button class="close-btn" onclick="window.close()">Đóng</button>
</div>

<div class="report-header">
    <div class="report-title">BÁO CÁO KIỂM KÊ</div>
    <div class="report-date">Đến ngày {{ $now->day }}/{{ $now->month }}/{{ $now->year }} {{ $now->format('H:i') }}</div>
</div>

<table class="report-table">
    <thead>
    <tr>
        <th style="width: 7%">STT</th>
        <th style="width: 25%">Tên sản phẩm</th> <!-- Giảm từ 30% xuống 25% -->
        <th style="width: 10%">Đơn vị</th>
        <th style="width: 23%">Số lô</th> <!-- Tăng từ 20% lên 23% -->
        <th style="width: 20%">Hạn sử dụng</th> <!-- Tăng từ 15% lên 20% -->
        <th style="width: 15%">Số lượng</th> <!-- Tăng từ 10% lên 15% -->
    </tr>
    </thead>
    <tbody>
    @php
        $currentNumber = 1;
    @endphp

    @forelse($products as $product)
        @php
            $batchesWithStock = $product->batches->filter(function($batch) {
                return $batch->quantity > 0;
            });
            $rowCount = $batchesWithStock->count();
        @endphp

        @if($rowCount > 0)
            @foreach($batchesWithStock as $index => $batch)
                <tr>
                    @if($index === 0)
                        <td rowspan="{{ $rowCount }}" class="text-center">{{ $currentNumber }}</td>
                        <td rowspan="{{ $rowCount }}">{{ $product->name }}</td>
                        <td rowspan="{{ $rowCount }}" class="text-center">{{ $product->unit }}</td>
                    @endif
                    <td>{{ $batch->batch_number }}</td>
                    <td class="text-center">{{ $batch->expiry_date->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $batch->quantity }}</td>
                </tr>
            @endforeach
            @php
                $currentNumber++;
            @endphp
        @endif
    @empty
        <tr>
            <td colspan="6" class="text-center">Không có dữ liệu</td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="signature-section">
    <div class="signature-date">Ngày {{ $now->day }} tháng {{ $now->month }} năm {{ $now->year }}</div>
    <div class="signature-title">NGƯỜI LẬP PHIẾU</div>
    <div class="signature-note">Ký, ghi rõ họ tên</div>
    <div class="signature-name">{{ strtoupper($user->name ?? '') }}</div>
</div>

<script>
    // Tự động mở hộp thoại in khi trang được tải
    window.onload = function() {
        // Đợi một chút để đảm bảo trang đã được render đầy đủ
        setTimeout(function() {
            window.print();
        }, 1000);
    };

    // Xử lý sự kiện trước khi in
    window.onbeforeprint = function() {
        // Đảm bảo tất cả nội dung đã được tải
        document.body.style.zoom = "100%";
    };
</script>
</body>
</html>
