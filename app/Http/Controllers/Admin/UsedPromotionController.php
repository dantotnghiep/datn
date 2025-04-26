<?php

namespace App\Http\Controllers\Admin;

use App\Models\UsedPromotion;
use Illuminate\Http\Request;

class UsedPromotionController extends BaseController
{
    public function __construct()
    {
        $this->model = UsedPromotion::class;
        $this->viewPath = 'admin.used-promotions';
        $this->route = 'admin.used-promotions';
        parent::__construct();
    }
} 