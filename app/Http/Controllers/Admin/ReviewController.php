<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends BaseController
{
    public function __construct()
    {
        $this->model = Review::class;
        $this->viewPath = 'admin.components.reviews';
        $this->route = 'admin.reviews';
        parent::__construct();
    }
} 