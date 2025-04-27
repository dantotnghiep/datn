<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends BaseController
{
    public function __construct()
    {
        $this->model = Cart::class;
        $this->viewPath = 'admin.carts';
        $this->route = 'admin.carts';
        parent::__construct();
    }
} 