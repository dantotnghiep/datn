<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\VariationAttributeValue;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributes = Attribute::with('values')->get();
        $variationAttributeValues = VariationAttributeValue::all();
        return view('admin.attribute.attribute', compact('attributes','variationAttributeValues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.attribute.attribute');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:attributes,slug|max:255',
        ]);

        Attribute::create([
                'name'=>$request->name,
                'slug'=>$request->slug,
        ]);
        return redirect()->route('admin.attribute.attribute')->with('success','Thuộc Tính đã được thêm');
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
        $attributes = Attribute::all();
        $attribute = Attribute::findOrFail($id);
        return view('admin.attribute.attribute-edit',compact('attribute','attributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $attribute = Attribute::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:attributes,slug,' . $id,
        ]);

        $attribute->update([
            'name'=>$request->name,
            'slug'=>$request->slug,
        ]);
        return redirect()->route('admin.attribute.attribute')->with('success',"Update Thành Công");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->delete();
        return redirect()->route('admin.attribute.attribute')->with('success','Xóa Thành Công Thuộc Tính Của Sản Phẩm');
    }
}
