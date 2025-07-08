<?php

namespace App\Http\Controllers;

use App\Models\MasterBarang;
use App\Models\StockOpname;
use Illuminate\Http\Request;

class StockOpnameController extends Controller
{
    public function tampil_stock_opname(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        if ($tahun) {
            $datas = StockOpname::where('tanggal', $tahun)
                ->get();
        }
        // dd($datas);

        return view('Admin.StockOpname.stock_opname', [
            'tahun' => $tahun,
            'datas' => $datas,
        ]);
    }

    public function ambil_stock_opname(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $getData = MasterBarang::all();

        foreach ($getData as $key => $value) {
            $create = [
                'tanggal'       =>  $tahun,
                'kode_barang'   =>  $value->kode_barang,
                'nama'   =>  $value->nama,
                'kategori_id'   =>  $value->kategori_id,
                'satuan'   =>  $value->satuan,
                'qty_sisa'   =>  $value->qty_sisa ?? 0,
                'harga'   =>  $value->harga ?? 0,
                'jumlah'   =>  $value->qty_sisa * $value->harga,
            ];

            StockOpname::create($create);
        }

        return redirect()->back()->with('success', 'Ambil data stok akhir berhasil ditambahkan.');
    }
}
