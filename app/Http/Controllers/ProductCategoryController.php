<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $categories = ProductCategory::orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends(['perPage' => $perPage]);

        return view('pages.product-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('pages.product-categories.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:product_categories,name|max:255',
            'note' => 'nullable|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        ProductCategory::create([
            'name' => $request->name,
            'note' => $request->note,
            'created_at' => now()
        ]);

        return redirect()->route('product-categories.index')
            ->with('success', 'Nhóm sản phẩm đã được tạo thành công.');
    }

    public function show($id)
    {
        $category = ProductCategory::findOrFail($id);
        return view('pages.product-categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = ProductCategory::findOrFail($id);
        return view('pages.product-categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = ProductCategory::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:product_categories,name,' . $id,
            'note' => 'nullable|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $category->update([
            'name' => $request->name,
            'note' => $request->note
        ]);

        return redirect()->route('product-categories.index')
            ->with('success', 'Nhóm sản phẩm đã được cập nhật thành công.');
    }

    public function destroy($id)
    {
        $category = ProductCategory::findOrFail($id);

        // Kiểm tra xem danh mục có sản phẩm không
        if ($category->products()->count() > 0) {
            return redirect()->route('product-categories.index')
                ->with('error', 'Không thể xóa danh mục này vì có sản phẩm liên quan.');
        }

        $category->delete();

        return redirect()->route('product-categories.index')
            ->with('success', 'Nhóm sản phẩm đã được xóa thành công.');
    }
}
