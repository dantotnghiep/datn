<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Admin\Traits\HasUploadImage;

class ProductImageController extends BaseController
{
    use HasUploadImage;

    public function __construct()
    {
        $this->model = ProductImage::class;
        $this->viewPath = 'admin.components.product-image';
        $this->route = 'admin.product-images';
        $this->hasImage = true;
        $this->imageField = 'image_path';
        $this->imageFolder = 'products';
        parent::__construct();
    }

    /**
     * Handle temporary image uploads from Dropzone
     */
    public function uploadTemp(Request $request)
    {
        Log::info('Product image upload attempt', [
            'has_file' => $request->hasFile('images'),
            'content_type' => $request->header('Content-Type')
        ]);

        if (!$request->hasFile('images')) {
            Log::warning('No images found in request');
            return response()->json(['error' => 'No image found'], 400);
        }

        // Check if it's a single file or multiple files
        $images = $request->file('images');

        // If it's a single file, convert to array for consistent handling
        if (!is_array($images)) {
            $images = [$images];
        }

        Log::info('Processing ' . count($images) . ' images for upload');

        $paths = [];

        foreach ($images as $image) {
            // Validate image
            if (!$image->isValid() || !in_array($image->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                Log::warning('Invalid image uploaded', [
                    'original_name' => $image->getClientOriginalName(),
                    'mime_type' => $image->getMimeType(),
                    'is_valid' => $image->isValid()
                ]);
                return response()->json(['error' => 'Invalid image file uploaded: ' . $image->getClientOriginalName()], 400);
            }

            // Store the image
            $path = $image->store('temp/products', 'public');
            Log::info('Image stored at: ' . $path);
            $paths[] = $path;
        }

        // Return appropriate response for Dropzone
        if (count($paths) === 1) {
            return response()->json([
                'success' => true,
                'path' => $paths[0],
                'name' => basename($paths[0]),
                'size' => Storage::disk('public')->size($paths[0])
            ]);
        } else {
            $result = ['success' => true, 'path' => $paths, 'files' => []];
            foreach ($paths as $path) {
                $result['files'][] = [
                    'path' => $path,
                    'name' => basename($path),
                    'size' => Storage::disk('public')->size($path)
                ];
            }
            return response()->json($result);
        }
    }
}
