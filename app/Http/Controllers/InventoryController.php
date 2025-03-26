<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBatch;
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

        // Phân trang
        $perPage = $request->input('perPage', 10);
        $products = $query->paginate($perPage)->appends($request->except('page'));

        return view('pages.inventory.index', compact('products'));
    }
}
