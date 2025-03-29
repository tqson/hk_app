<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('pages.sales.index', compact('user'));
    }

    public function searchProducts(Request $request)
    {
        $query = $request->get('query');
        $products = Product::where('name', 'like', "%{$query}%")
//            ->where('stock', '>', 0)
            ->with('category')
            ->get();

        return response()->json($products);
    }

    public function getProduct($id)
    {
        $product = Product::with(['category', 'batches'])->findOrFail($id);

        // Calculate total quantity from batches
        $product->total_quantity = $product->batches->sum('quantity');

        return response()->json($product);
    }

    public function createInvoice(Request $request)
    {
        try {
            DB::beginTransaction();

            // Xác định phương thức thanh toán
            $paymentMethod = 'cash'; // Mặc định là tiền mặt
            if ($request->transfer_amount > 0 && $request->cash_amount > 0) {
                $paymentMethod = 'mixed'; // Kết hợp
            } elseif ($request->transfer_amount > 0) {
                $paymentMethod = 'transfer'; // Chuyển khoản
            }

            // Tạo hóa đơn
            $invoice = new SalesInvoice();
            $invoice->user_id = Auth::id();
            $invoice->total_amount = $request->total_amount;
            $invoice->discount = $request->discount;
            $invoice->payment_method = $paymentMethod;
            $invoice->notes = $request->notes;
            $invoice->created_at = now();
            $invoice->save();

            // Tạo chi tiết hóa đơn
            $products = $request->products;

            foreach ($products as $product) {
                $detail = new SalesInvoiceDetail();
                $detail->invoice_id = $invoice->id;
                $detail->product_id = $product['id'];
                $detail->quantity = $product['quantity'];
                $detail->price = $product['price'];

                // Cập nhật tồn kho
                $productModel = Product::find($product['id']);
                $productBatch = $productModel->batches()->where('product_id', $product['id'])->first();
//                dd($productBatch);
                $productBatch->quantity -= $product['quantity'];
                $detail->product_batch_id = $productBatch->id;
                $productBatch->save();

                $detail->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Hóa đơn đã được tạo thành công!',
                'invoice_id' => $invoice->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function invoiceList(Request $request)
    {
        $perPage = $request->input('perPage', 10); // Mặc định là 10 nếu không có tham số

        $query = SalesInvoice::with('user')
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

        $invoices = $query->paginate($perPage);

        // Đảm bảo các tham số tìm kiếm được giữ lại khi phân trang
        $invoices->appends($request->except('page'));

        return view('pages.sales.invoice-list', compact('invoices'));
    }

    // Thêm phương thức để xem chi tiết hóa đơn
    public function invoiceDetail($id)
    {
        $invoice = SalesInvoice::with(['details.product', 'user'])->findOrFail($id);

        return view('pages.sales.invoice-detail', compact('invoice'));
    }
}
