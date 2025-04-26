<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends BaseController
{
    public function __construct()
    {
        $this->model = OrderItem::class;
        $this->viewPath = 'admin.order-items';
        $this->route = 'admin.order-items';
        parent::__construct();
    }
} 