<?php

namespace App\Http\Controllers\Admin;

use App\Models\AttributeValueVariation;
use Illuminate\Http\Request;

class AttributeValueVariationController extends BaseController
{
    public function __construct()
    {
        $this->model = AttributeValueVariation::class;
        $this->viewPath = 'admin.attribute-value-variations';
        $this->route = 'admin.attribute-value-variations';
        parent::__construct();
    }
} 