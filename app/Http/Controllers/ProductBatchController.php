<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBatch;
use Illuminate\Http\Request;

class ProductBatchController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'batch_number' => 'required|string|max:255',
            'manufacturing_date' => 'required|date',
            'expiry_date' => 'required|date|after:manufacturing_date',
        ]);

        try {
            $batch = ProductBatch::create($request->all());
            return response()->json([
                'success' => true,
                'batch' => $batch,
                'message' => 'Lô sản phẩm đã được tạo thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getByProduct($productId)
    {
        $batches = ProductBatch::where('product_id', $productId)
            ->orderBy('expiry_date', 'asc')
            ->get();

        return response()->json($batches);
    }
}
