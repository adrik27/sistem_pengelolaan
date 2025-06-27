<?php

namespace App\Http\Controllers;

use App\Models\MasterBarang;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class MasterBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function tampil_riwayat_transaksi()
    {
        $datas = Transaksi::where('jenis_transaksi', 'masuk')
                    ->where('status', 'selesai')
                    ->where('verifikator_id', 1)
                    ->orWhere('pembuat_id', 1)
                    ->get();
        return view('Admin.Transaksi.seluruh_transaksi', [
            'datas' => $datas,
        ]);
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterBarang $masterBarang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterBarang $masterBarang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterBarang $masterBarang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterBarang $masterBarang)
    {
        //
    }
}
