<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthenticateController extends Controller
{
    public function tampil_login() 
    {
        return view('Authenticate.Login');
    }
}
