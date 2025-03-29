<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'batches']);

        // Tìm kiếm theo tên sản phẩm, mã Sản phẩm hoặc mã vạch
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
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
                $query->where(function($q) {
                    $q->where('stock', '>', 0)
                        ->orWhereHas('batches', function($q) {
                            $q->where('quantity', '>', 0);
                        });
                });
            } elseif ($request->stock_filter === 'out_of_stock') {
                $query->where(function($q) {
                    $q->where('stock', '<=', 0)
                        ->whereDoesntHave('batches', function($q) {
                            $q->where('quantity', '>', 0);
                        });
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
            'price' => 'required|numeric|min:0',
//            'import_price' => 'required|numeric|min:0',
            'description' => 'nullable',

            // Thông tin lô hàng ban đầu (nếu có)
            'batch_number' => 'nullable|max:50',
            'manufacturing_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:manufacturing_date',
            'stock' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Xử lý upload hình ảnh

        // Tạo sản phẩm mới
        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit' => $request->unit,
            'price' => $request->price,
            'import_price' => $request->import_price,
            'description' => $request->description,
            'status' => $request->status ?? true,
            'stock' => $request->stock ?? 0,
        ]);

        // Tạo lô hàng ban đầu nếu có thông tin
        if ($request->filled('batch_number') && $request->filled('expiry_date')) {
            ProductBatch::create([
                'product_id' => $product->id,
                'batch_number' => $request->batch_number,
                'manufacturing_date' => $request->manufacturing_date,
                'expiry_date' => $request->expiry_date,
                'quantity' => $request->stock ?? 0,
                'import_price' => $request->import_price,
                'status' => 'active',
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Sản phẩm đã được tạo thành công.');
    }

    public function show($id)
    {
        // Tải sản phẩm cùng với các quan hệ cần thiết
        $product = Product::with(['batches', 'category', 'importItems.import.supplier', 'importItems.batch'])
            ->findOrFail($id);

        return view('pages.products.show', compact('product'));
    }

    public function edit($id)
    {
        // Load the product with its batches relationship
        $product = Product::with('batches')->findOrFail($id);

        // Load all product categories for the dropdown
        $categories = ProductCategory::all();

        // Return the view with the data
        // Note: Changed from 'pages.products.edit' to 'products.edit' to match your view structure
        return view('pages.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'category_id' => 'required|exists:product_categories,id',
//            'unit' => 'required|max:50',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable',
//            'status' => 'required',
//            'batches.*.batch_number' => 'required|string|max:50',
//            'batches.*.manufacturing_date' => 'required|date',
//            'batches.*.expiry_date' => 'required|date|after:batches.*.manufacturing_date',
//            'batches.*.quantity' => 'required|numeric|min:0',
//            'batches.*.import_price' => 'required|numeric|min:0',
//            'batches.*.status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update product details
        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit' => $request->unit,
            'price' => $request->price,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        // Handle batches
        if ($request->has('batches')) {
            foreach ($request->batches as $batchData) {
                if (isset($batchData['id'])) {
                    // Update existing batch
                    $batch = ProductBatch::findOrFail($batchData['id']);
                    $batch->update([
                        'batch_number' => $batchData['batch_number'],
                        'manufacturing_date' => $batchData['manufacturing_date'],
                        'expiry_date' => $batchData['expiry_date'],
                        'quantity' => $batchData['quantity'],
                        'import_price' => $batchData['import_price'],
                        'status' => $batchData['status'],
                    ]);
                } else {
                    // Create new batch
                    $product->batches()->create([
                        'batch_number' => $batchData['batch_number'],
                        'manufacturing_date' => $batchData['manufacturing_date'],
                        'expiry_date' => $batchData['expiry_date'],
                        'quantity' => $batchData['quantity'],
                        'import_price' => $batchData['import_price'],
                        'status' => $batchData['status'],
                    ]);
                }
            }
        }

        // Handle batch deletion
        if ($request->has('delete_batches')) {
            ProductBatch::whereIn('id', $request->delete_batches)->delete();
        }

        return redirect()->route('products.index')
            ->with('success', 'Sản phẩm đã được cập nhật thành công.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Kiểm tra xem sản phẩm có liên quan đến hóa đơn không
        if ($product->salesInvoiceDetails()->count() > 0 ||
            $product->importItems()->count() > 0 ||
            $product->batches()->count() > 0) {
            return redirect()->route('products.index')
                ->with('error', 'Không thể xóa sản phẩm này vì có dữ liệu liên quan.');
        }

        // Xóa hình ảnh nếu có
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Sản phẩm đã được xóa thành công.');
    }

    public function searchSale(Request $request)
    {
        $query = $request->get('search');
        $products = Product::where('name', 'like', "%{$query}%")
            ->with(['category', 'batches'])
            ->where('status', true)
            ->limit(10)
            ->get();

        $products->each(function ($product) {
            $product->total_quantity = $product->batches->sum('quantity');
        });

        return response()->json($products);
    }

    public function search(Request $request)
    {
        $query = Product::query()->where('status', true);

        // Tìm kiếm theo từ khóa
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('id', 'like', "%{$searchTerm}%");
            });
        }

        // Lấy sản phẩm mới nhất nếu không có từ khóa tìm kiếm
        if ($request->has('recent') && $request->recent) {
            $query->latest('id');

            if ($request->has('limit')) {
                $query->limit($request->limit);
            } else {
                $query->limit(10);
            }
        }

        // Chỉ lấy sản phẩm có tồn kho
        if ($request->has('has_stock') && $request->has_stock) {
            $query->whereHas('batches', function($q) {
                $q->whereRaw('quantity > 0');
            });
        }

        // Lấy thông tin tồn kho
        $products = $query->with(['batches' => function($q) {
            $q->select('product_batches.id', 'product_id', 'batch_number', 'manufacturing_date', 'expiry_date', 'quantity');
        }])->get();

        // Tính tổng tồn kho cho mỗi sản phẩm
        $products->each(function($product) {
            $product->quantity = $product->batches->sum('quantity');
        });

        return $products;
    }

    /**
     * Lấy thông tin chi tiết sản phẩm và các lô còn hàng
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBatches($id)
    {
        $product = Product::findOrFail($id);

        // Lấy các lô còn hàng
        $batches = ProductBatch::where('product_id', $id)
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date')
            ->get();

        return response()->json([
            'success' => true,
            'product' => $product,
            'batches' => $batches
        ]);
    }

    /**
     * Cập nhật số lượng tồn kho của sản phẩm
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStock(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $product = Product::findOrFail($id);
        $product->stock = $request->stock;
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật tồn kho thành công',
            'product' => $product
        ]);
    }
}
