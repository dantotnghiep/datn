<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends BaseController
{
    public function __construct()
    {
        $this->model = ProductImage::class;
        $this->viewPath = 'admin.product-images';
        $this->route = 'admin.product-images';
        $this->hasImage = true;
        $this->imageField = 'image_path';
        $this->imageFolder = 'products';
        parent::__construct();
    }
} 