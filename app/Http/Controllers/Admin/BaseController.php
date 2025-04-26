<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

abstract class BaseController extends Controller
{
    protected $model;
    protected $viewPath;
    protected $route;
    protected $itemsPerPage = 10;

    // Cấu hình xử lý ảnh
    protected $hasImage = false; // Mặc định không xử lý ảnh
    protected $imageField = 'image'; // Tên trường ảnh
    protected $imageFolder = 'uploads'; // Thư mục lưu ảnh

    public function __construct()
    {
        $this->setupBaseProperties();
    }

    protected function setupBaseProperties()
    {
        if (!$this->model || !$this->viewPath || !$this->route) {
            throw new \Exception('Please set $model, $viewPath and $route in your controller.');
        }
    }

    public function index(Request $request)
    {
        $query = $this->model::query();

        // Handle Search
        if ($request->has('search')) {
            $searchTerm = $request->get('search');
            $searchableFields = $this->model::getSearchableFields();

            $query->where(function($q) use ($searchTerm, $searchableFields) {
                foreach ($searchableFields as $field) {
                    $q->orWhere($field, 'LIKE', "%{$searchTerm}%");
                }
            });
        }

        // Handle Filters
        if ($request->has('filter')) {
            $filters = $request->get('filter');
            foreach ($filters as $field => $value) {
                if ($value !== null && $value !== '') {
                    $query->where($field, $value);
                }
            }
        }

        // Handle Sorting
        if ($request->has('sort')) {
            $sort = $request->get('sort');
            if (preg_match('/^(.+)_(asc|desc)$/', $sort, $matches)) {
                $field = $matches[1];
                $direction = $matches[2];
                $query->orderBy($field, $direction);
            }
        }

        // Handle Trashed Items
        if ($request->get('trashed')) {
            $query->onlyTrashed();
        }

        $items = $query->paginate($this->itemsPerPage)->withQueryString();
        $fields = $this->model::getFields();
        $title = class_basename($this->model);

        return view($this->viewPath . '.index', [
            'items' => $items,
            'fields' => $fields,
            'title' => $title,
            'route' => $this->route
        ]);
    }

    public function create()
    {
        $fields = $this->model::getFields();
        return view($this->viewPath . '.form', [
            'fields' => $fields,
            'route' => $this->route
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->model::rules());

        // Xử lý upload ảnh nếu có
        if ($this->hasImage) {
            $validated = $this->handleImageUpload($request, $validated);
        }

        $this->model::create($validated);

        return redirect()->route($this->route . '.index')
            ->with('success', 'Item created successfully');
    }

    public function edit($slug)
    {
        $item = $this->model::withTrashed()->where('slug', $slug)->firstOrFail();
        $fields = $this->model::getFields();
        return view($this->viewPath . '.form', [
            'item' => $item,
            'fields' => $fields,
            'route' => $this->route
        ]);
    }

    public function update(Request $request, $slug)
    {
        $item = $this->model::withTrashed()->where('slug', $slug)->firstOrFail();
        $validated = $request->validate($this->model::rules($item->id));

        // Xử lý upload ảnh nếu có
        if ($this->hasImage) {
            $validated = $this->handleImageUpdate($request, $item, $validated);
        }

        $item->update($validated);

        return redirect()->route($this->route . '.index')
            ->with('success', 'Item updated successfully');
    }

    public function destroy($slug)
    {
        $item = $this->model::where('slug', $slug)->firstOrFail();

        // Xử lý xóa ảnh nếu có
        if ($this->hasImage) {
            $this->handleImageDelete($item);
        }

        $item->delete();

        return redirect()->route($this->route . '.index')
            ->with('success', 'Item moved to trash successfully');
    }

    public function restore($slug)
    {
        $item = $this->model::onlyTrashed()->where('slug', $slug)->firstOrFail();
        $item->restore();

        return redirect()->route($this->route . '.index')
            ->with('success', 'Item restored successfully');
    }

    // Các phương thức xử lý ảnh
    protected function handleImageUpload(Request $request, array $validated)
    {
        if ($request->hasFile($this->imageField)) {
            $validated[$this->imageField] = $request->file($this->imageField)
                ->store($this->imageFolder, 'public');
        }
        return $validated;
    }

    protected function handleImageUpdate(Request $request, $item, array $validated)
    {
        if ($request->hasFile($this->imageField)) {
            // Xóa ảnh cũ
            if ($item->{$this->imageField}) {
                Storage::disk('public')->delete($item->{$this->imageField});
            }
            $validated[$this->imageField] = $request->file($this->imageField)
                ->store($this->imageFolder, 'public');
        }
        return $validated;
    }

    protected function handleImageDelete($item)
    {
        if ($item->{$this->imageField}) {
            Storage::disk('public')->delete($item->{$this->imageField});
        }
    }
}
