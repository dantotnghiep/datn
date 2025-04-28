<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends BaseController
{
    public function __construct()
    {
        $this->model = Order::class;
        $this->viewPath = 'admin.components.crud';
        $this->route = 'admin.orders';
        parent::__construct();
    }
} 