<?php

namespace App\Http\Controllers;

use App\Models\Penerimaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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

        // 5. Query data
        $query = Penerimaan::query();

        // 6. Terapkan filter berdasarkan hak akses
        if (Gate::allows('admin')) {
            // Jika admin, filter berdasarkan input dari form
            if ($request->filled('bidang_id')) {
                $query->where('bidang_id', $request->bidang_id);
            }
        } else {
            // Jika bukan admin, paksa filter berdasarkan bidang user yang login
            $query->where('bidang_id', Auth::user()->bidang_id);
        }

        // Terapkan filter tanggal
        $data = $query->whereBetween('tanggal_pembukuan', [
            $request->tanggal_awal,
            $request->tanggal_akhir
        ])
            ->orderBy('tanggal_pembukuan', 'asc')
            ->get();

        // Kembalikan data dalam format JSON yang dimengerti DataTables
        return response()->json(['data' => $data]);
    }
}
