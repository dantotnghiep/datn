<?php

namespace App\Http\Controllers\Admin;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends BaseController
{
    public function __construct()
    {
        $this->model = Wishlist::class;
        $this->viewPath = 'admin.wishlists';
        $this->route = 'admin.wishlists';
        parent::__construct();
    }
} 