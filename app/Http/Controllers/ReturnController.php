<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\ReturnInvoice;
use App\Models\ReturnInvoiceDetail;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReturnController extends Controller
{
    /**
     * Hiển thị danh sách hóa đơn trả hàng
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $query = ReturnInvoice::with(['user', 'salesInvoice', 'details.product'])
            ->orderBy('created_at', 'desc');

        // Tìm kiếm theo ngày
        if ($request->has('date_from') && $request->date_from) {
            $dateFrom = Carbon::parse($request->date_from)->startOfDay();
            $query->where('created_at', '>=', $dateFrom);
        }

        if ($request->has('date_to') && $request->date_to) {
            $dateTo = Carbon::parse($request->date_to)->endOfDay();
            $query->where('created_at', '<=', $dateTo);
        }

        $returnInvoices = $query->paginate($perPage);
        $returnInvoices->appends($request->except('page'));

        return view('pages.returns.index', compact('returnInvoices'));
    }

    /**
     * Hiển thị form tạo hóa đơn trả hàng
     */
    public function create()
    {
        // Lấy 10 hóa đơn bán hàng gần nhất
        $recentInvoices = SalesInvoice::orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('pages.returns.create', compact('recentInvoices'));
    }

    /**
     * Tìm kiếm hóa đơn bán hàng
     */
    public function searchInvoices(Request $request)
    {
        $search = $request->input('search');

        $invoices = SalesInvoice::where('id', 'LIKE', "%{$search}%")
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json($invoices);
    }

    /**
     * Lấy thông tin chi tiết hóa đơn bán hàng
     */
    public function getInvoiceDetails($id)
    {
        $invoice = SalesInvoice::with(['details.product'])->findOrFail($id);

        return response()->json($invoice);
    }

    /**
     * Lưu hóa đơn trả hàng
     */
    public function store(Request $request)
    {
        $request->validate([
            'sales_invoice_id' => 'required|exists:sales_invoices,id',
            'products' => 'required|json',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Tạo hóa đơn trả hàng
            $returnInvoice = new ReturnInvoice();
            $returnInvoice->user_id = Auth::id();
            $returnInvoice->sales_invoice_id = $request->sales_invoice_id;
            $returnInvoice->total_amount = $request->total_amount;
            $returnInvoice->notes = $request->notes;
            $returnInvoice->created_at = now();
            $returnInvoice->save();

            // Tạo chi tiết hóa đơn trả hàng
            $products = json_decode($request->products, true);

            foreach ($products as $product) {
                if ($product['return_quantity'] > 0) {
                    $detail = new ReturnInvoiceDetail();
                    $detail->return_invoice_id = $returnInvoice->id;
                    $detail->product_id = $product['id'];
                    $detail->quantity = $product['return_quantity'];
                    $detail->price = $product['price'];
                    $detail->save();

                    // Cập nhật tồn kho
//                    $productModel = Product::find($product['id']);
//                    $productModel->stock += $product['return_quantity'];
//                    $productModel->save();

                    $productBatch = ProductBatch::find($product['batch']);

                    if ($productBatch) {
                        $productBatch->quantity += $product['return_quantity'];
                        $productBatch->save();
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Hóa đơn trả hàng đã được tạo thành công!',
                'return_invoice_id' => $returnInvoice->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hiển thị chi tiết hóa đơn trả hàng
     */
    public function show($id)
    {
        $returnInvoice = ReturnInvoice::with(['user', 'salesInvoice', 'details.product'])
            ->findOrFail($id);

        return view('pages.returns.detail', compact('returnInvoice'));
    }
}
