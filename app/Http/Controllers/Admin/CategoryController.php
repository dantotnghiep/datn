<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;

class CategoryController extends BaseController
{
    public function __construct()
    {
        $this->model = Category::class;
        $this->viewPath = 'admin.crud';
        $this->route = 'admin.categories';
        parent::__construct();
    }
}
