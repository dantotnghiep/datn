<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AttributeValueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributeValues = AttributeValue::with('attribute')->get();
        $attributes = Attribute::all();
        return view('admin.attribute.attribute-values', compact('attributeValues','attributes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $attributes = Attribute::all();
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
        ]);
        AttributeValue::create([
            'attribute_id' => $request->attribute_id,
            'value' => $request->value,
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
        $attributeValues = AttributeValue::with('attribute')->get();
        $attribute_value = AttributeValue::findOrFail($id);
        $attributes = Attribute::all();
        return view('admin.attribute.attribute-values-edit',compact('attributeValues','attributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $attribute = Attribute::findOrFail($id);
        $request->validate([
           'attribute_id' =>'required|exists:attribute,id',
           'value'=>'required|string|max:255',
            'slug'=>[
            'required',
            'string',
            'max:255',
            Rule::unique('attribute_values','slug')->ignore($attribute->id),
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attributeValues = AttributeValue::fineOrFail($id);
        $attributeValues->delete();
        return redirect()->route('admin.attribute.attribute-value')->with('success',"Xóa Thành Công Thuộc Tính Danh Mục");
    }
}
