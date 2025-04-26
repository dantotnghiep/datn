<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrderCancellation;
use Illuminate\Http\Request;

class OrderCancellationController extends BaseController
{
    public function __construct()
    {
        $this->model = OrderCancellation::class;
        $this->viewPath = 'admin.order-cancellations';
        $this->route = 'admin.order-cancellations';
        parent::__construct();
    }
} 