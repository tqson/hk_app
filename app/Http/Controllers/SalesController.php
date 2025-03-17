<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceDetail;
use App\Models\User;
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
            ->where('stock', '>', 0)
            ->with('category')
            ->get();

        return response()->json($products);
    }

    public function getProduct($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    public function createInvoice(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'final_amount' => 'required|numeric|min:0',
            'cash_amount' => 'nullable|numeric|min:0',
            'transfer_amount' => 'nullable|numeric|min:0',
            'change_amount' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Create sales invoice
            $invoice = new SalesInvoice();
            $invoice->user_id = Auth::id();
            $invoice->total_amount = $request->final_amount;
            $invoice->discount = $request->discount ?? 0;
            $invoice->payment_method = $request->cash_amount > 0 ? 'cash' : 'transfer';
            $invoice->notes = $request->notes;
            $invoice->created_at = now();
            $invoice->save();

            // Create invoice details and update stock
            foreach ($request->products as $item) {
                $detail = new SalesInvoiceDetail();
                $detail->invoice_id = $invoice->id;
                $detail->product_id = $item['id'];
                $detail->quantity = $item['quantity'];
                $detail->price = $item['price'];
                $detail->created_at = now();
                $detail->save();

                // Update product stock
                $product = Product::find($item['id']);
                $product->stock -= $item['quantity'];
                $product->save();
            }

            DB::commit();
            return response()->json(['success' => true, 'invoice_id' => $invoice->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
