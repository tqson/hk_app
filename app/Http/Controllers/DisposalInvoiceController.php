<?php

namespace App\Http\Controllers;

use App\Models\DisposalInvoice;
use App\Models\DisposalItem;
use App\Models\Product;
use App\Models\ProductBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DisposalInvoiceController extends Controller
{
    /**
     * Hiển thị danh sách phiếu xuất hủy
     */
    public function index(Request $request)
    {
        $query = DisposalInvoice::with('user');

        // Tìm kiếm theo mã hóa đơn
        if ($request->has('search') && !empty($request->search)) {
            $query->where('invoice_code', 'like', '%' . $request->search . '%');
        }

        // Lọc theo khoảng thời gian
        if ($request->has('from_date') && !empty($request->from_date)) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->from_date)->startOfDay());
        }

        if ($request->has('to_date') && !empty($request->to_date)) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->to_date)->endOfDay());
        }

        // Sắp xếp theo thời gian tạo giảm dần (mới nhất lên đầu)
        $query->orderBy('created_at', 'desc');

        // Phân trang
        $perPage = $request->input('perPage', 10);
        $disposals = $query->paginate($perPage)->appends($request->except('page'));
        $totalDisposalValue = DisposalInvoice::sum('total_amount');
        $totalDisposalItems = DisposalItem::sum('quantity');

        return view('pages.disposal.index', compact('disposals', 'totalDisposalValue',
            'totalDisposalItems'));
    }

    /**
     * Hiển thị form tạo phiếu xuất hủy mới
     */
    public function create()
    {
        // Lấy danh sách sản phẩm còn tồn kho
        $products = Product::whereHas('batches', function($query) {
            $query->where('quantity', '>', 0);
        })->orWhere('stock', '>', 0)->get();
        $lastDisposal = DisposalInvoice::latest()->first();
        $lastId = $lastDisposal ? intval(substr($lastDisposal->disposal_code, 3)) : 0;
        $disposalCode = 'HUY' . str_pad($lastId + 1, 6, '0', STR_PAD_LEFT);

        return view('pages.disposal.create', compact('products', 'disposalCode'));
    }

    /**
     * Lưu phiếu xuất hủy mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.batch_id' => 'nullable|exists:product_batches,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.reason' => 'required|string|max:255',
            'note' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Tạo phiếu xuất hủy
            $invoice = DisposalInvoice::create([
                'user_id' => auth()->id(),
                'total_amount' => 0, // Sẽ cập nhật sau
                'note' => $request->note,
            ]);

            $totalAmount = 0;

            // Thêm các sản phẩm vào phiếu
            foreach ($request->items as $item) {
                $quantity = (int) $item['quantity'];
                $price = (float) $item['price'];
                $totalPrice = $quantity * $price;
                $totalAmount += $totalPrice;

                // Tạo item cho phiếu
                DisposalItem::create([
                    'disposal_invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'product_batch_id' => $item['batch_id'] ?? null,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total_price' => $totalPrice,
                    'reason' => $item['reason'],
                ]);

                // Cập nhật số lượng tồn kho
                if (!empty($item['batch_id'])) {
                    // Nếu có lô, cập nhật số lượng trong lô
                    $batch = ProductBatch::find($item['batch_id']);
                    if ($batch) {
                        $batch->quantity = max(0, $batch->quantity - $quantity);
                        $batch->save();
                    }
                } else {
                    // Nếu không có lô, cập nhật số lượng trong sản phẩm
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->stock = max(0, $product->stock - $quantity);
                        $product->save();
                    }
                }
            }

            // Cập nhật tổng tiền cho phiếu
            $invoice->total_amount = $totalAmount;
            $invoice->save();

            DB::commit();

            return redirect()->route('disposal.index')
                ->with('success', 'Phiếu xuất hủy đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hiển thị chi tiết phiếu xuất hủy
     */
    public function show(DisposalInvoice $disposal)
    {
        $disposal->load(['items.product', 'items.batch', 'user']);
        return view('pages.disposal.show', compact('disposal'));
    }

    /**
     * Lấy thông tin lô của sản phẩm (AJAX)
     */
    public function getProductBatches($productId)
    {
        $batches = ProductBatch::where('product_id', $productId)
            ->where('quantity', '>', 0)
            ->where('status', 'active')
            ->get(['id', 'batch_number', 'manufacturing_date', 'expiry_date', 'quantity']);

        return response()->json($batches);
    }
}
