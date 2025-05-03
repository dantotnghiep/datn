<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductVariation;

class ProductVariationController extends BaseController
{
    public function __construct()
    {
        $this->model = ProductVariation::class;
        $this->viewPath = 'admin.components.productVariations';
        $this->route = 'admin.product-variations';
        parent::__construct();
    }
}   