<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::orderBy('created_at', 'desc')->get();
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
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        ProductCategory::create([
            'name' => $request->name,
            'created_at' => now()
        ]);

        return redirect()->route('product-categories.index')
            ->with('success', 'Danh mục sản phẩm đã được tạo thành công.');
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
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $category->update([
            'name' => $request->name
        ]);

        return redirect()->route('product-categories.index')
            ->with('success', 'Danh mục sản phẩm đã được cập nhật thành công.');
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
            ->with('success', 'Danh mục sản phẩm đã được xóa thành công.');
    }
}
