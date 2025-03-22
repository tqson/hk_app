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
        $query = Product::with(['category', 'batches']);

        // Tìm kiếm theo tên sản phẩm, mã SKU hoặc mã vạch
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%')
                    ->orWhere('barcode', 'like', '%' . $search . '%');
            });
        }

        // Lọc theo danh mục
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo trạng thái
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Lọc theo tồn kho
        if ($request->has('stock_filter') && !empty($request->stock_filter)) {
            if ($request->stock_filter === 'in_stock') {
                $query->where('stock', '>', 0)
                    ->orWhereHas('batches', function($q) {
                        $q->where('quantity', '>', 0);
                    });
            } elseif ($request->stock_filter === 'out_of_stock') {
                $query->where('stock', '<=', 0)
                    ->whereDoesntHave('batches', function($q) {
                        $q->where('quantity', '>', 0);
                    });
            }
        }

        // Sắp xếp
        $sortField = $request->input('sort_field', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Phân trang
        $perPage = $request->input('perPage', 10); // Mặc định 10 sản phẩm mỗi trang
        $products = $query->paginate($perPage)->appends($request->except('page'));

        // Lấy danh sách danh mục cho bộ lọc
        $categories = ProductCategory::all();

        return view('pages.products.index', compact('products', 'categories'));
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


    public function search(Request $request)
    {
        $query = $request->get('term');
        $products = Product::where('name', 'like', "%{$query}%")
            ->with('category')
            ->get();

        return response()->json($products);
    }
}

