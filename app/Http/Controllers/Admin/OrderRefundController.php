<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrderRefund;
use Illuminate\Http\Request;

class OrderRefundController extends BaseController
{
    public function __construct()
    {
        $this->model = OrderRefund::class;
        $this->viewPath = 'admin.order-refunds';
        $this->route = 'admin.order-refunds';
        parent::__construct();
    }
} 