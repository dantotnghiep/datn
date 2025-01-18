<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttributeValueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributeValues = AttributeValue::with('attribute')->get();
        $attributes = Attribute::all(); // Lấy tất cả AttributeValues kèm Attribute liên kết
        return view('admin.attribute.attribute-values', compact('attributeValues','attributes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $attributes = Attribute::all(); // Lấy danh sách tất cả Attributes
        return view('admin.attribute.attribute-values', compact('attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:attribute_values,slug',
        ]);
        AttributeValue::create([
            'attribute_id' => $request->attribute_id,
            'value' => $request->value,
            'slug' =>$request->slug ?? Str::slug($request->value, '-'),
        ]);
        return redirect()->route('admin.attribute-values')->with('success','Thêm giá tri thuộc tính thành công');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
