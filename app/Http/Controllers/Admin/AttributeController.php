<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends BaseController
{
    public function __construct()
    {
        $this->model = Attribute::class;
        $this->viewPath = 'admin.attributes';
        $this->route = 'admin.attributes';
        parent::__construct();
    }
} 