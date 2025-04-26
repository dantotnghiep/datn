<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductVariation;
use Illuminate\Http\Request;

class ProductVariationController extends BaseController
{
    public function __construct()
    {
        $this->model = ProductVariation::class;
        $this->viewPath = 'admin.product-variations';
        $this->route = 'admin.product-variations';
        parent::__construct();
    }
} 