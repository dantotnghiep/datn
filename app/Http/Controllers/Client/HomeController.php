<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function dashboard()
    {
        $categories = Category::with(['products' => function ($query) {
            $query->where('status', 'active'); 
        }])->where('status', 'active') 
        ->get();
        $products = Product::with(['variations', 'images', 'category'])->orderBy('created_at', 'desc')->get();
        return view('client.index',compact('categories','products'));
    }
   
}
