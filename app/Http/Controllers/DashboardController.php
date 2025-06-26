<?php

namespace App\Http\Controllers;

use App\Models\SaldoAwal;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function tampil_dashboard()
    {
        return view('Admin.Dashboard.dashboard_admin');
    }

    public function getSaldoAwal()
    {
        $data = SaldoAwal::sum('saldo_awal');

        return response()->json([
            'data' => $data
        ]);
    }
}
