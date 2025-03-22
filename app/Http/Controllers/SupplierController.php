<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        // Tìm kiếm theo tên
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $suppliers = $query->orderBy('id', 'desc')->paginate(10);

        return view('pages.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'mobile' => 'required|string|size:10|regex:/^[0-9]+$/',
            'phone' => 'nullable|string|size:10|regex:/^[0-9]+$/',
            'tax_code' => 'nullable|string|size:10|regex:/^[0-9]+$/',
        ], [
            'name.required' => 'Tên nhà cung cấp không được để trống',
            'contact_person.required' => 'Người liên hệ không được để trống',
            'mobile.required' => 'Số di động không được để trống',
            'mobile.size' => 'Số di động phải có đúng 10 số',
            'mobile.regex' => 'Số di động chỉ được nhập số',
            'phone.size' => 'Số điện thoại phải có đúng 10 số',
            'phone.regex' => 'Số điện thoại chỉ được nhập số',
            'tax_code.size' => 'Mã số thuế phải có đúng 10 số',
            'tax_code.regex' => 'Mã số thuế chỉ được nhập số',
        ]);

        $supplier = Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Thêm mới nhà cung cấp thành công!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        return view('pages.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Supplier $supplier)
    {
        return view('pages.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'mobile' => 'required|string|size:10|regex:/^[0-9]+$/',
            'phone' => 'nullable|string|size:11|regex:/^[0-9]+$/',
            'tax_code' => 'nullable|string|size:10|regex:/^[0-9]+$/',
        ], [
            'name.required' => 'Tên nhà cung cấp không được để trống',
            'contact_person.required' => 'Người liên hệ không được để trống',
            'mobile.required' => 'Số di động không được để trống',
            'mobile.size' => 'Số di động phải có đúng 10 số',
            'mobile.regex' => 'Số di động chỉ được nhập số',
            'phone.size' => 'Số điện thoại phải có đúng 11 số',
            'phone.regex' => 'Số điện thoại chỉ được nhập số',
            'tax_code.size' => 'Mã số thuế phải có đúng 10 số',
            'tax_code.regex' => 'Mã số thuế chỉ được nhập số',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Cập nhật nhà cung cấp thành công!');
    }

    /**
     * Toggle the status of the supplier.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus(Supplier $supplier)
    {
        $newStatus = $supplier->status === 'active' ? 'inactive' : 'active';
        $supplier->update(['status' => $newStatus]);

        $message = $newStatus === 'active'
            ? 'Kích hoạt nhà cung cấp thành công!'
            : 'Dừng hoạt động nhà cung cấp thành công!';

        return redirect()->route('suppliers.index')
            ->with('success', $message);
    }
}
