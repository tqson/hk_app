<?php

namespace App\Http\Controllers;

use App\Models\Import;
use App\Models\ProductBatch;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\PaymentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    public function index(Request $request)
    {
        $query = Import::with('supplier');

        // Search by import code or supplier name
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('import_code', 'like', "%{$searchTerm}%")
                    ->orWhereHas('supplier', function($sq) use ($searchTerm) {
                        $sq->where('name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by payment status
        if ($request->has('payment_status') && !empty($request->payment_status)) {
            if ($request->payment_status === 'paid') {
                $query->where('debt_amount', 0);
            } elseif ($request->payment_status === 'unpaid') {
                $query->where('debt_amount', '>', 0);
            }
        }

        // Set per page from request or default to 10
        $perPage = $request->input('perPage', 10);

        // Get imports with pagination
        $imports = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));

        // Calculate totals (these will be for all records, not just the paginated ones)
        $totalDebt = Import::sum('debt_amount');
        $totalPaid = Import::sum('paid_amount');
        $totalAmount = Import::sum('final_amount');

        // If you want totals for filtered results only, use the query builder:
        // $totalDebt = $query->sum('debt_amount');
        // $totalPaid = $query->sum('paid_amount');
        // $totalAmount = $query->sum('final_amount');

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
            'products.*.product_batch_id' => 'required|exists:product_batches,id', // Changed to required
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
                'vat' => (int)$request->vat,
                'discount_percent' => $request->discount_percent,
                'final_amount' => $request->final_amount,
                'paid_amount' => $request->paid_amount,
                'debt_amount' => $request->debt_amount,
            ]);

            foreach ($request->products as $item) {
                // Create import item with batch_id
                $importItem = $import->items()->create([
                    'product_id' => $item['product_id'],
                    'product_batch_id' => $item['product_batch_id'], // Always set batch_id
                    'quantity' => $item['quantity'],
                    'import_price' => $item['import_price'],
                    'total_price' => $item['total_price'],
                ]);

                // Update batch quantity
                $batch = ProductBatch::findOrFail($item['product_batch_id']);
                $batch->quantity += $item['quantity'];
                $batch->save();
            }

            // Create payment history if paid amount > 0
            if ($request->paid_amount > 0) {
                PaymentHistory::create([
                    'import_id' => $import->id,
                    'amount' => $request->paid_amount,
                    'remaining_debt' => $request->debt_amount,
                    'payment_date' => now(),
                    'payment_method' => $request->payment_method ?? 'cash',
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
