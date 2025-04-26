<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends BaseController
{
    public function __construct()
    {
        $this->model = Location::class;
        $this->viewPath = 'admin.locations';
        $this->route = 'admin.locations';
        parent::__construct();
    }
} 