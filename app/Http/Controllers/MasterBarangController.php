<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
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


    public function seluruh_data_barang()
    {
        $datas = DataBarang::orderBy('nama_barang', 'asc')->get();
        return response()->json($datas);
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search');

        $barangs = DataBarang::query()
            ->select('id', 'kode_kelompok', 'kode_barang', 'nama_barang')
            ->when($searchTerm, function ($query, $searchTerm) {
                // Cari berdasarkan nama atau kode barang
                $query->where('nama_barang', 'like', "%{$searchTerm}%")
                    ->orWhere('kode_barang', 'like', "%{$searchTerm}%");
            })
            ->limit(10) // Batasi hasil untuk performa
            ->get();

        // Kembalikan data dalam format JSON
        return response()->json($barangs);
    }
}
