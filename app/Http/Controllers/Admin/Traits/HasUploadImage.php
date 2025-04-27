<?php

namespace App\Http\Controllers\Admin\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait HasUploadImage
{
    protected function handleImageUpload(Request $request, array $validated, string $field = 'image', string $folder = 'uploads')
    {
        if ($request->hasFile($field)) {
            $validated[$field] = $request->file($field)->store($folder, 'public');
        }
        return $validated;
    }

    protected function handleImageUpdate(Request $request, $item, array $validated, string $field = 'image', string $folder = 'uploads')
    {
        if ($request->hasFile($field)) {
            // Xóa ảnh cũ
            if ($item->{$field}) {
                Storage::disk('public')->delete($item->{$field});
            }
            $validated[$field] = $request->file($field)->store($folder, 'public');
        }
        return $validated;
    }

    protected function handleImageDelete($item, string $field = 'image')
    {
        if ($item->{$field}) {
            Storage::disk('public')->delete($item->{$field});
        }
    }
}
