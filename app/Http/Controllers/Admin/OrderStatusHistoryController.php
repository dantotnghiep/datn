<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;

class OrderStatusHistoryController extends BaseController
{
    public function __construct()
    {
        $this->model = OrderStatusHistory::class;
        $this->viewPath = 'admin.order-status-history';
        $this->route = 'admin.order-status-history';
        parent::__construct();
    }
} 