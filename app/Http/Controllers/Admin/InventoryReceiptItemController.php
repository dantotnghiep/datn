<?php

namespace App\Http\Controllers\Admin;

use App\Models\InventoryReceiptItem;
use Illuminate\Http\Request;

class InventoryReceiptItemController extends BaseController
{
    public function __construct()
    {
        $this->model = InventoryReceiptItem::class;
        $this->viewPath = 'admin.inventory-receipt-items';
        $this->route = 'admin.inventory-receipt-items';
        parent::__construct();
    }
} 