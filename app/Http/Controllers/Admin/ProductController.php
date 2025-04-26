<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Admin\Traits\HasUploadImage;

class ProductController extends BaseController
{
    use HasUploadImage;

    public function __construct()
    {
        $this->model = Product::class;
        $this->viewPath = 'admin.products';
        $this->route = 'admin.products';
        parent::__construct();
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->model::rules());
        $validated = $this->handleImageUpload($request, $validated, 'image', 'products');
        $this->model::create($validated);

        return redirect()->route($this->route . '.index')
            ->with('success', 'Sản phẩm đã được tạo thành công');
    }

    public function update(Request $request, $id)
    {
        $item = $this->model::findOrFail($id);
        $validated = $request->validate($this->model::rules($id));
        $validated = $this->handleImageUpdate($request, $item, $validated, 'image', 'products');
        $item->update($validated);

        return redirect()->route($this->route . '.index')
            ->with('success', 'Sản phẩm đã được cập nhật thành công');
    }

    public function destroy($id)
    {
        $item = $this->model::findOrFail($id);
        $this->handleImageDelete($item, 'image');
        $item->delete();

        return redirect()->route($this->route . '.index')
            ->with('success', 'Sản phẩm đã được chuyển vào thùng rác');
    }
}
