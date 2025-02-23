<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        return view('admin.auth.login');
    }

    public function forgotpassword()
    {
        return view('admin.auth.forgot-password');
    }

    public function loginclient()
    {
        return view('client.auth.login');
    }

    public function register()
    {
        return view('client.auth.register');
    }
}
