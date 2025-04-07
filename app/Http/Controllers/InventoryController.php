<?php

namespace App\Http\Controllers;

use App\Models\Product;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Hiển thị trang kiểm tra tồn kho
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'batches']);

        // Tìm kiếm theo tên sản phẩm
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Lọc theo trạng thái tồn kho
        if ($request->has('stock_status') && !empty($request->stock_status)) {
            if ($request->stock_status === 'in_stock') {
                // Sản phẩm còn hàng (có ít nhất một lô với số lượng > 0)
                $query->whereHas('batches', function($q) {
                    $q->where('quantity', '>', 0);
                });
            } elseif ($request->stock_status === 'out_of_stock') {
                // Sản phẩm hết hàng (không có lô nào hoặc tất cả các lô đều có số lượng <= 0)
                $query->whereDoesntHave('batches', function($q) {
                    $q->where('quantity', '>', 0);
                });
            }
        }

        // Sắp xếp
        $sortField = $request->input('sort_field', 'name');
        $sortDirection = $request->input('sort_direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        // Lưu query vào session để sử dụng cho export và print
        session(['inventory_filter_query' => [
            'search' => $request->search,
            'stock_status' => $request->stock_status,
            'sort_field' => $sortField,
            'sort_direction' => $sortDirection
        ]]);

        // Phân trang
        $perPage = $request->input('perPage', 10);
        $products = $query->paginate($perPage)->appends($request->except('page'));

        return view('pages.inventory.index', compact('products'));
    }

    /**
     * Áp dụng các bộ lọc từ session vào query
     */
    private function applyFiltersFromSession()
    {
        $query = Product::with(['batches']);
        $filters = session('inventory_filter_query', []);

        // Áp dụng tìm kiếm
        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        // Áp dụng lọc theo trạng thái tồn kho
        if (!empty($filters['stock_status'])) {
            if ($filters['stock_status'] === 'in_stock') {
                $query->whereHas('batches', function($q) {
                    $q->where('quantity', '>', 0);
                });
            } elseif ($filters['stock_status'] === 'out_of_stock') {
                $query->whereDoesntHave('batches', function($q) {
                    $q->where('quantity', '>', 0);
                });
            }
        }

        // Áp dụng sắp xếp
        $sortField = $filters['sort_field'] ?? 'name';
        $sortDirection = $filters['sort_direction'] ?? 'asc';
        $query->orderBy($sortField, $sortDirection);

        return $query;
    }

    public function export()
    {
        // Lấy dữ liệu sản phẩm đã được lọc
        $query = $this->applyFiltersFromSession();
        $products = $query->get();

        // Tạo đối tượng PhpWord
        $phpWord = new PhpWord();

        // Thêm style
        $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 16, 'name' => 'Arial'], ['alignment' => 'center']);
        $phpWord->addTitleStyle(2, ['bold' => true, 'size' => 14, 'name' => 'Arial'], ['alignment' => 'center']);

        // Tạo section mới
        $section = $phpWord->addSection();

        // Thêm tiêu đề
        $section->addTitle('BÁO CÁO KIỂM KÊ', 1);
        $section->addTextBreak(1);

        // Tạo bảng
        $table = $section->addTable([
            'borderSize' => 1,
            'borderColor' => '000000',
            'width' => 100 * 50,
            'unit' => 'pct',
            'alignment' => 'center'
        ]);

        // Thêm header cho bảng
        $table->addRow();
        $table->addCell(1000, ['bgColor' => 'D3D3D3'])->addText('STT', ['bold' => true]);
        $table->addCell(5000, ['bgColor' => 'D3D3D3'])->addText('Tên sản phẩm', ['bold' => true]);
        $table->addCell(1500, ['bgColor' => 'D3D3D3'])->addText('Đơn vị', ['bold' => true]);
        $table->addCell(2500, ['bgColor' => 'D3D3D3'])->addText('Số lô', ['bold' => true]);
        $table->addCell(2500, ['bgColor' => 'D3D3D3'])->addText('Hạn sử dụng', ['bold' => true]);
        $table->addCell(2000, ['bgColor' => 'D3D3D3'])->addText('Số lượng tồn kho', ['bold' => true]);

        // Thêm dữ liệu vào bảng
        $stt = 1;
        foreach ($products as $product) {
            $batchesWithStock = $product->batches->filter(function($batch) {
                return $batch->quantity > 0;
            });

            if ($batchesWithStock->count() > 0) {
                foreach ($batchesWithStock as $index => $batch) {
                    $table->addRow();

                    if ($index === 0) {
                        $table->addCell(1000, $index === 0 ? ['vMerge' => 'restart'] : ['vMerge' => 'continue'])->addText($stt);
                        $table->addCell(5000, $index === 0 ? ['vMerge' => 'restart'] : ['vMerge' => 'continue'])->addText($product->name);
                        $table->addCell(1500, $index === 0 ? ['vMerge' => 'restart'] : ['vMerge' => 'continue'])->addText($product->unit);
                    } else {
                        $table->addCell(null, ['vMerge' => 'continue']);
                        $table->addCell(null, ['vMerge' => 'continue']);
                        $table->addCell(null, ['vMerge' => 'continue']);
                    }

                    $table->addCell(2500)->addText($batch->batch_number);
                    $table->addCell(2500)->addText($batch->expiry_date->format('d/m/Y'));
                    $table->addCell(2000)->addText($batch->quantity);
                }
                $stt++;
            }
        }

        $section->addTextBreak(2);

        // Thêm ngày tháng và chữ ký
        $now = Carbon::now();
        $textrun = $section->addTextRun(['alignment' => 'right']);
        $textrun->addText("Ngày {$now->day} tháng {$now->month} năm {$now->year}");

        $section->addTextBreak(1);
        $textrun = $section->addTextRun(['alignment' => 'right']);
        $textrun->addText("Người lập phiếu", ['bold' => true]);

        $section->addTextBreak(1);
        $textrun = $section->addTextRun(['alignment' => 'right']);
        $textrun->addText("Ký, ghi rõ họ tên", ['italic' => true]);

        $section->addTextBreak(3);
        $textrun = $section->addTextRun(['alignment' => 'right']);
        $textrun->addText(strtoupper(Auth::user()->name ?? ''), ['bold' => true]);

        // Lưu file
        $filename = 'Bao_cao_kiem_ke_' . $now->format('d_m_Y_H_i_s') . '.docx';
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

        // Lưu file tạm thời
        $tempFile = storage_path('app/public/' . $filename);
        $objWriter->save($tempFile);

        // Trả về file để download
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    public function printReport()
    {
        // Lấy dữ liệu sản phẩm đã được lọc
        $query = $this->applyFiltersFromSession();
        $products = $query->get();

        $user = Auth::user();
        $now = Carbon::now();

        return view('pages.inventory.print', compact('products', 'user', 'now'));
    }
}
