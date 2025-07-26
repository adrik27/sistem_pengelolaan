<?php

namespace App\Http\Controllers;

use App\Models\Penerimaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman utama laporan penerimaan.
     */
    public function penerimaan()
    {
        // Hanya menampilkan view, data akan di-load melalui AJAX.
        return view('laporan.penerimaan');
    }

    /**
     * Mengambil data penerimaan untuk DataTables via AJAX.
     */
    public function getDataPenerimaan(Request $request)
    {
        // Validasi request yang masuk dari AJAX
        $validator = Validator::make($request->all(), [
            'tanggal_awal'  => 'required|date_format:Y-m-d',
            'tanggal_akhir' => 'required|date_format:Y-m-d|after_or_equal:tanggal_awal',
        ]);

        // Jika validasi gagal, kembalikan response error
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userBidangId = Auth::user()->bidang_id;

        // Query data berdasarkan rentang tanggal
        $data = Penerimaan::where('bidang_id', $userBidangId)->whereBetween('tanggal_pembukuan', [
            $request->tanggal_awal,
            $request->tanggal_akhir
        ])->orderBy('tanggal_pembukuan', 'asc')->get();

        // Kembalikan data dalam format JSON yang dimengerti DataTables
        return response()->json(['data' => $data]);
    }
}
