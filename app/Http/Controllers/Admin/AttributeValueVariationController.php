<?php

namespace App\Http\Controllers\Admin;

use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AttributeValueVariationController extends BaseController
{
    public function __construct()
    {
        $this->model = AttributeValue::class;
        $this->viewPath = 'admin.attributes';
        $this->route = 'admin.attribute';
        parent::__construct();
    }
}
