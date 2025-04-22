<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::latest()->paginate(10);
        return view('admin.discounts.index', compact('discounts'));
    }

    public function create()
    {
        $products = Product::where('status', 'active')->get();
        $categories = Category::all();
        return view('admin.discounts.create', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:discounts,code',
            'sale' => 'required|numeric|min:0',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after:startDate',
            'maxUsage' => 'nullable|integer|min:1',
            'minOrderValue' => 'nullable|numeric|min:0',
            'maxDiscount' => 'nullable|numeric|min:0',
        ]);

        Discount::create($validated);

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Mã giảm giá đã được tạo thành công.');
    }

    public function edit(Discount $discount)
    {
        return view('admin.discounts.edit', compact('discount'));
    }

    public function update(Request $request, Discount $discount)
    {
        $validated = $request->validate([
            'code' => 'required|unique:discounts,code,' . $discount->id,
            'sale' => 'required|numeric|min:0',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after:startDate',
            'maxUsage' => 'nullable|integer|min:1',
            'minOrderValue' => 'nullable|numeric|min:0',
            'maxDiscount' => 'nullable|numeric|min:0',
        ]);

        $discount->update($validated);

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Mã giảm giá đã được cập nhật thành công.');
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();
        return redirect()->route('admin.discounts.index')
            ->with('success', 'Mã giảm giá đã được xóa thành công.');
    }
}