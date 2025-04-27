<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrderStatus;
use Illuminate\Http\Request;

class OrderStatusController extends BaseController
{
    public function __construct()
    {
        $this->model = OrderStatus::class;
        $this->viewPath = 'admin.order-status';
        $this->route = 'admin.order-status';
        parent::__construct();
    }
} 