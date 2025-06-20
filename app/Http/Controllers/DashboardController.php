<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function tampil_dashboard()
    {
        return view('Admin.Dashboard.dashboard_admin');
    }
}
