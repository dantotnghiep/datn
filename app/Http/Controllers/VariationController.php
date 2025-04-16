<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Variation;
use App\Models\VariationAttributeValue;
use Illuminate\Support\Facades\Log;

class VariationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($productId) {}
    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'sku' => 'required|string',
                'price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'sale_start' => 'nullable|date',
                'sale_end' => 'nullable|date|after_or_equal:sale_start',
            ]);

            DB::transaction(function () use ($validated, $id) {
                $variation = Variation::findOrFail($id);

                $variation->update([
                    'sku' => $validated['sku'],
                    'price' => $validated['price'],
                    'stock' => $validated['stock'],
                    'sale_price' => !empty($validated['sale_price']) ? $validated['sale_price'] : null,
                    'sale_start' => !empty($validated['sale_start']) ? date('Y-m-d H:i:s', strtotime($validated['sale_start'])) : null,
                    'sale_end' => !empty($validated['sale_end']) ? date('Y-m-d H:i:s', strtotime($validated['sale_end'])) : null,
                ]);
            });

            return redirect()->back()->with('success', 'Cập nhật biến thể thành công!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Vui lòng kiểm tra lại thông tin!');
        }
    }
}