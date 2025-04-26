<?php

namespace App\Http\Controllers\Admin;

use App\Models\InventoryReceipt;
use Illuminate\Http\Request;

class InventoryReceiptController extends BaseController
{
    public function __construct()
    {
        $this->model = InventoryReceipt::class;
        $this->viewPath = 'admin.inventory-receipts';
        $this->route = 'admin.inventory-receipts';
        parent::__construct();
    }
} 