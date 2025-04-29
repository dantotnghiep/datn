<?php

namespace App\Http\Controllers\Admin;

use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AttributeValueController extends BaseController
{
    public function __construct()
    {
        $this->model = AttributeValue::class;
        $this->viewPath = 'admin.attribute-values';
        $this->route = 'admin.attribute-values';
        parent::__construct();
    }

    public function store(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:255'
        ]);

        try {
            AttributeValue::create([
                'attribute_id' => $request->attribute_id,
                'value' => $request->value
            ]);

            return redirect()->back()->with('success', 'Thêm giá trị thuộc tính thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}