<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search by product name
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Order by created_at
        $query->orderBy('created_at', 'desc');

        // Pagination
        $perPage = $request->input('perPage', 10); // Default to 10 items per page
        $products = $query->paginate($perPage)->appends($request->except('page'));

        return view('pages.products.index', compact('products'));
    }

    public function deactivate(Product $product)
    {
        $product->status = false;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được dừng hoạt động thành công.');
    }

    public function activate(Product $product)
    {
        $product->status = true;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được kích hoạt thành công.');
    }

    public function create()
    {
        $categories = ProductCategory::all();
        return view('pages.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'category_id' => 'required|exists:product_categories,id',
            'unit' => 'required|max:50',
            'batch_number' => 'required|max:50',
            'expiration_date' => 'required|date|after:today',
            'stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit' => $request->unit,
            'batch_number' => $request->batch_number,
            'expiration_date' => $request->expiration_date,
            'stock' => $request->stock,
            'created_at' => now()
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Sản phẩm đã được tạo thành công.');
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('pages.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = ProductCategory::all();
        return view('pages.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'category_id' => 'required|exists:product_categories,id',
            'unit' => 'required|max:50',
            'batch_number' => 'required|max:50',
            'expiration_date' => 'required|date',
            'stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit' => $request->unit,
            'batch_number' => $request->batch_number,
            'expiration_date' => $request->expiration_date,
            'stock' => $request->stock,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Sản phẩm đã được cập nhật thành công.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Kiểm tra xem sản phẩm có liên quan đến hóa đơn không
        if ($product->salesInvoiceDetails()->count() > 0 ||
            $product->purchaseInvoiceDetails()->count() > 0 ||
            $product->disposalRecords()->count() > 0) {
            return redirect()->route('products.index')
                ->with('error', 'Không thể xóa sản phẩm này vì có dữ liệu liên quan.');
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Sản phẩm đã được xóa thành công.');
    }
}

