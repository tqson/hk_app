<?php

namespace App\Http\Controllers;

use App\Models\ProductBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductBatchController extends Controller
{
    public function getByProduct($productId)
    {
        $batches = ProductBatch::where('product_id', $productId)
            ->orderBy('expiry_date', 'asc')
            ->get();

        return response()->json($batches);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'batch_number' => 'required|string|max:50',
            'manufacturing_date' => 'required|date',
            'expiry_date' => 'required|date|after:manufacturing_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Kiểm tra xem lô đã tồn tại chưa
        $existingBatch = ProductBatch::where('product_id', $request->product_id)
            ->where('batch_number', $request->batch_number)
            ->first();

        if ($existingBatch) {
            return response()->json([
                'success' => false,
                'message' => 'Lô sản phẩm này đã tồn tại'
            ], 422);
        }

        // Tạo lô mới
        $batch = new ProductBatch();
        $batch->product_id = $request->product_id;
        $batch->batch_number = $request->batch_number;
        $batch->manufacturing_date = $request->manufacturing_date;
        $batch->expiry_date = $request->expiry_date;
        $batch->import_price = $request->import_price;
        $batch->quantity = 0;
        $batch->save();

        return response()->json([
            'success' => true,
            'message' => 'Thêm lô sản phẩm thành công',
            'batch' => $batch
        ]);
    }
}
