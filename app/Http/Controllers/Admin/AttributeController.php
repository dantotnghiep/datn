<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeController extends BaseController
{
    public function __construct()
    {
        $this->model = Attribute::class;
        $this->viewPath = 'admin.components.attributes';
        $this->route = 'admin.attributes';
        parent::__construct();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'values' => 'required|array|min:1',
            'values.*' => 'required|string|max:255'
        ]);

        DB::beginTransaction();
        try {
            $item = $this->model::create([
                'name' => $validated['name']
            ]);

            // Create attribute values
            foreach ($validated['values'] as $value) {
                AttributeValue::create([
                    'attribute_id' => $item->id,
                    'value' => $value
                ]);
            }

            DB::commit();
            return redirect()->route($this->route . '.index')
                ->with('success', 'Thuộc tính đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function storeValue(Request $request)
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

            return redirect()->back()->with('success', 'Giá trị thuộc tính đã được thêm thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
