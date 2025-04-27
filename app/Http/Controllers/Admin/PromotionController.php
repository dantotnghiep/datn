<?php

namespace App\Http\Controllers\Admin;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends BaseController
{
    public function __construct()
    {
        $this->model = Promotion::class;
        $this->viewPath = 'admin.promotions';
        $this->route = 'admin.promotions';
        parent::__construct();
    }
} 