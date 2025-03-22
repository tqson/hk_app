<?php

namespace App\Http\Controllers;

use App\Models\Import;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\PaymentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    public function index()
    {
        $imports = Import::with('supplier')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalDebt = Import::sum('debt_amount');
        $totalPaid = Import::sum('paid_amount');
        $totalAmount = Import::sum('final_amount');

        return view('pages.imports.index', compact('imports', 'totalDebt', 'totalPaid', 'totalAmount'));
    }

    public function create()
    {
        $suppliers = Supplier::where('status', 'active')->get();
        $products = Product::all();

        return view('pages.imports.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'expected_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'vat' => 'required|numeric|min:0',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'final_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'debt_amount' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.product_batch_id' => 'nullable|exists:product_batches,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.import_price' => 'required|numeric|min:0',
            'products.*.total_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $import = Import::create([
                'supplier_id' => $request->supplier_id,
                'expected_date' => $request->expected_date,
                'total_amount' => $request->total_amount,
                'vat' => $request->vat,
                'discount_percent' => $request->discount_percent,
                'final_amount' => $request->final_amount,
                'paid_amount' => $request->paid_amount,
                'debt_amount' => $request->debt_amount,
            ]);

            foreach ($request->products as $item) {
                $import->items()->create([
                    'product_id' => $item['product_id'],
                    'product_batch_id' => $item['product_batch_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'import_price' => $item['import_price'],
                    'total_price' => $item['total_price'],
                ]);

                // Update product stock
                $product = Product::find($item['product_id']);
                $product->stock += $item['quantity'];
                $product->save();
            }

            // Create payment history if paid amount > 0
            if ($request->paid_amount > 0) {
                PaymentHistory::create([
                    'import_id' => $import->id,
                    'amount' => $request->paid_amount,
                    'remaining_debt' => $request->debt_amount,
                ]);
            }

            DB::commit();
            return redirect()->route('imports.index')->with('success', 'Hóa đơn nhập hàng đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e->getMessage());
            return back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Import $import)
    {
        $import->load(['supplier', 'items.product', 'items.productBatch']);
        return view('pages.imports.show', compact('import'));
    }

    public function updatePayment(Request $request, Import $import)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:0.01|max:' . $import->debt_amount,
        ]);

        DB::beginTransaction();
        try {
            $import->paid_amount += $request->payment_amount;
            $import->debt_amount -= $request->payment_amount;
            $import->save();

            PaymentHistory::create([
                'import_id' => $import->id,
                'amount' => $request->payment_amount,
                'remaining_debt' => $import->debt_amount,
            ]);

            DB::commit();
            return redirect()->route('imports.index')->with('success', 'Thanh toán đã được cập nhật thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    public function paymentHistory(Import $import)
    {
        $paymentHistories = $import->paymentHistories()->orderBy('created_at', 'desc')->get();
        return view('pages.imports.payment_history', compact('import', 'paymentHistories'));
    }
}
