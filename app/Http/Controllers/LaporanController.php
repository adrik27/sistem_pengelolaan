<?php

namespace App\Http\Controllers;

use App\Models\Penerimaan;
use App\Models\StokPersediaanBidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Riskihajar\Terbilang\Facades\Terbilang;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman utama laporan penerimaan.
     */
    public function penerimaan()
    {
        // Hanya menampilkan view, data akan di-load melalui AJAX.
        // return view('laporan.penerimaan');
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

    public function cetakBeritaAcara(Request $request)
    {
        // Validasi input tanggal dan bidang
        $request->validate([
            'tanggal_akhir' => 'required|date_format:Y-m-d',
            'tanggal_cetak' => 'required|date_format:Y-m-d',
            'bidang_id'     => 'nullable|integer',
        ]);

        // === START: Pengambilan Data Laporan ===
        $query = StokPersediaanBidang::query();

        // Filter berdasarkan hak akses
        if (Auth::user()->jabatan_id == 1 || Auth::user()->jabatan_id == 2) {
            // Jika admin, filter berdasarkan input dari form
            if ($request->filled('bidang_id')) {
                $query->where('bidang_id', $request->bidang_id);
            }
        } else {
            // Jika bukan admin, paksa filter berdasarkan bidang user yang login
            $query->where('bidang_id', Auth::user()->bidang_id);
        }

        // Ambil data dan kelompokkan berdasarkan kode_kelompok
        $persediaan = $query->where('qty_sisa', '>', 0)->orderBy('kode_barang')->get();
        $groupedPersediaan = $persediaan->groupBy('kode_kelompok');
        // === END: Pengambilan Data Laporan ===


        // === START: Persiapan Data untuk View ===
        // Hitung Grand Total
        $grandTotal = $persediaan->sum(function ($item) {
            return $item->qty_sisa * $item->harga_satuan;
        });

        // Konversi Grand Total ke format terbilang
        $grandTotalTerbilang = Terbilang::make($grandTotal, ' rupiah');

        // Buat objek Carbon dari tanggal cetak
        $carbonTanggalCetak = Carbon::parse($request->tanggal_cetak)->locale('id');

        // Siapkan semua format tanggal yang dibutuhkan
        $tanggalCetakFormatted = $carbonTanggalCetak->isoFormat('dddd, D MMMM YYYY'); // -> "Rabu, 30 Juli 2025"
        $tanggalCetakSingkat   = $carbonTanggalCetak->isoFormat('D MMMM YYYY');   // -> "30 Juli 2025"
        $tanggalAkhirFormatted = Carbon::parse($request->tanggal_akhir)->locale('id')->isoFormat('D MMMM YYYY');

        // Data Pejabat (Contoh, sebaiknya diambil dari database atau config)
        $pejabat = [
            'pengguna_barang' => (object) [
                'nama' => 'Ir. DIDIK TRI PRASETIYO, M.Si',
                'nip' => '196611271996031002',
                'jabatan' => 'Pengguna Barang',
                'pangkat' => 'Pembina Utama Muda'
            ],
            'pengurus_barang' => (object) [
                'nama' => 'EBIM',
                'nip' => '199404042025211075',
                'jabatan' => 'Pengurus Barang',
                'pangkat' => 'Pengatur Tingkat I'
            ]
        ];
        // === END: Persiapan Data untuk View ===


        // Kirim semua data yang dibutuhkan ke view cetak
        return view('Admin.LaporanPersediaan.cetak_berita_acara', compact(
            'groupedPersediaan',
            'grandTotal',
            'grandTotalTerbilang',
            'tanggalCetakFormatted',
            'tanggalCetakSingkat', // <-- Kirim variabel baru ini
            'tanggalAkhirFormatted',
            'pejabat'
        ));
    }
    // public function cetakBeritaAcara(Request $request)
    // {
    //     // Validasi input tanggal dan bidang
    //     $request->validate([
    //         'tanggal_akhir' => 'required|date_format:Y-m-d',
    //         'tanggal_cetak' => 'required|date_format:Y-m-d',
    //         'bidang_id'     => Gate::allows('view-any-laporan') ? 'nullable|integer' : '',
    //     ]);

    //     // === START: Pengambilan Data Laporan ===
    //     $query = StokPersediaanBidang::query();

    //     // Filter berdasarkan hak akses
    //     if (Gate::allows('view-any-laporan')) {
    //         dd('admin');
    //         // Jika admin, filter berdasarkan input dari form
    //         if ($request->filled('bidang_id')) {
    //             dd('admin tapi masuk kondisi');
    //             $query->where('bidang_id', $request->bidang_id);
    //         }
    //     } else {
    //         dd('bukan');
    //         // Jika bukan admin, paksa filter berdasarkan bidang user yang login
    //         $query->where('bidang_id', Auth::user()->bidang_id);
    //     }

    //     // Ambil data dan kelompokkan berdasarkan kode_kelompok
    //     $persediaan = $query->where('qty_sisa', '>', 0)->orderBy('kode_barang')->get();
    //     $groupedPersediaan = $persediaan->groupBy('kode_kelompok');
    //     // === END: Pengambilan Data Laporan ===


    //     // === START: Persiapan Data untuk View ===
    //     // Hitung Grand Total
    //     $grandTotal = $persediaan->sum(function ($item) {
    //         return $item->qty_sisa * $item->harga_satuan;
    //     });

    //     // Konversi Grand Total ke format terbilang
    //     $grandTotalTerbilang = Terbilang::make($grandTotal, ' rupiah');

    //     // Buat objek Carbon dari tanggal cetak
    //     $carbonTanggalCetak = Carbon::parse($request->tanggal_cetak)->locale('id');

    //     // Siapkan semua format tanggal yang dibutuhkan
    //     $tanggalCetakFormatted = $carbonTanggalCetak->isoFormat('dddd, D MMMM YYYY'); // -> "Rabu, 30 Juli 2025"
    //     $tanggalCetakSingkat   = $carbonTanggalCetak->isoFormat('D MMMM YYYY');   // -> "30 Juli 2025"
    //     $tanggalAkhirFormatted = Carbon::parse($request->tanggal_akhir)->locale('id')->isoFormat('D MMMM YYYY');

    //     // Data Pejabat (Contoh, sebaiknya diambil dari database atau config)
    //     $pejabat = [
    //         'pengguna_barang' => (object) [
    //             'nama' => 'Ir. DIDIK TRI PRASETIYO, M.Si',
    //             'nip' => '196611271996031002',
    //             'jabatan' => 'Pengguna Barang',
    //             'pangkat' => 'Pembina Utama Muda'
    //         ],
    //         'pengurus_barang' => (object) [
    //             'nama' => 'HARTOMO',
    //             'nip' => '198003162010011004',
    //             'jabatan' => 'Pengurus Barang',
    //             'pangkat' => 'Pengatur Tingkat I'
    //         ]
    //     ];
    //     // === END: Persiapan Data untuk View ===


    //     // Kirim semua data yang dibutuhkan ke view cetak
    //     return view('Admin.LaporanPersediaan.cetak_berita_acara', compact(
    //         'groupedPersediaan',
    //         'grandTotal',
    //         'grandTotalTerbilang',
    //         'tanggalCetakFormatted',
    //         'tanggalCetakSingkat', // <-- Kirim variabel baru ini
    //         'tanggalAkhirFormatted',
    //         'pejabat'
    //     ));
    // }
}
