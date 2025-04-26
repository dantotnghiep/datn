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
} 