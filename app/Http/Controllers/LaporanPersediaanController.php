<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Carbon\Carbon;
use App\Models\SaldoAwal;
use App\Models\Transaksi;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StokPersediaanBidang;
use Illuminate\Support\Facades\Gate;

class LaporanPersediaanController extends Controller
{

    public function tampil_laporan_persediaan(Request $request)
    {
        // Ambil parameter filter dari request untuk dikirim kembali ke view
        $req_department = $request->input('department_id');
        $tahun_from = $request->input('tahun_from') ?? date('Y');
        $tahun_to = $request->input('tahun_to') ?? date('Y');

        // Query dasar ke tabel stok persediaan bidang
        $query = StokPersediaanBidang::query();

        // Filter berdasarkan hak akses
        if (Gate::denies('admin')) {
            // Jika BUKAN admin, paksa filter berdasarkan bidang_id user
            $query->where('bidang_id', Auth::user()->bidang_id);
        } else {
            // Jika ADMIN, filter berdasarkan pilihan dropdown (jika ada)
            if ($req_department) {
                $query->where('bidang_id', $req_department);
            }
        }

        $stok_items = $query->get();

        // Transformasi data agar sesuai dengan struktur yang diharapkan oleh view
        $laporan = [];
        foreach ($stok_items as $item) {
            $bidang = Bidang::find($item->bidang_id);
            $laporan[] = [
                'department_id' => $bidang ? $bidang->nama : 'N/A',
                'saldo_awal'    => 0, // Kolom ini tidak relevan lagi

                'tgl_masuk'     => null, // Kolom ini tidak ada di tabel stok
                'kode_masuk'    => $item->kode_barang,
                'nama_masuk'    => $item->nama_barang,
                'harga_masuk'   => $item->harga_satuan,
                'qty_masuk'     => $item->qty_sisa,
                'saldo_masuk'   => $item->harga_satuan * $item->qty_sisa,

                'tgl_keluar'    => null, // Kolom-kolom pengeluaran tidak relevan
                'kode_keluar'   => null,
                'nama_keluar'   => null,
                'harga_keluar'  => 0,
                'qty_keluar'    => 0,
                'saldo_keluar'  => 0,

                'sisa_stok'     => $item->qty_sisa,
                'sisa_saldo'    => $item->harga_satuan * $item->qty_sisa,
            ];
        }

        // Siapkan data untuk filter dropdown di view
        $departments = Bidang::where('status', 'aktif')->where('id', '!=', 1)->get();
        $bidangList = Gate::allows('admin') ? Bidang::where('id', '!=', 1)->orderBy('nama')->get() : collect();

        return view('Admin.LaporanPersediaan.laporan_persediaan', [
            'laporan' => $laporan,
            'bidangList' => $bidangList,
            'tahun_from' => $tahun_from,
            'tahun_to' => $tahun_to,
            'req_departments' => $req_department,
            'departments' => $departments,
        ]);
    }





    // public function tampil_laporan_persediaan()
    // {
    //     $data = Transaksi::where('status', 'verifikasi')->get();

    //     // Kelompokkan berdasarkan department_id dan kode_barang
    //     $grouped = $data->groupBy(function ($item) {
    //         return $item->department_id . '-' . $item->kode_barang . '-' . $item->tgl_transaksi;
    //     });

    //     $laporan = [];

    //     foreach ($grouped as $key => $items) {
    //         $saldo_awal = SaldoAwal::select('saldo_awal', 'saldo_digunakan')
    //             ->where('department_id', $items->first()->department_id)
    //             ->where('tahun', $items->first()->tgl_transaksi->format('Y'))
    //             ->first();


    //         $row = [
    //             'total_merge'   => count($items) / 2,
    //             'department_id' => $items->first()->department_id,
    //             'saldo_awal' => $saldo_awal->saldo_awal ?? 0,
    //             'saldo_digunakan' => $saldo_awal->saldo_digunakan ?? 0,
    //             'sisa_saldo' => ($saldo_awal->saldo_awal - $saldo_awal->saldo_digunakan) ?? 0,


    //             // Penerimaan
    //             'tgl_masuk' => null,
    //             'kode_barang_masuk' => null,
    //             'nama_barang_masuk' => null,
    //             'harga_masuk' => null,
    //             'jumlah_masuk' => null,
    //             'saldo_masuk' => null,

    //             // Pengeluaran
    //             'tgl_keluar' => null,
    //             'kode_barang_keluar' => null,
    //             'nama_barang_keluar' => null,
    //             'harga_keluar' => null,
    //             'jumlah_keluar' => null,
    //             'saldo_keluar' => null,
    //         ];


    //         foreach ($items as $item) {
    //             if ($item->jenis_transaksi === 'transaksi masuk') {
    //                 $row['tgl_masuk'] = $item->tgl_transaksi;
    //                 $row['kode_barang_masuk'] = $item->kode_barang;
    //                 $row['nama_barang_masuk'] = $item->nama_barang;
    //                 $row['harga_masuk'] = $item->harga_satuan;
    //                 $row['jumlah_masuk'] = $item->qty;
    //                 $row['saldo_masuk'] = $item->total_harga;
    //             } elseif ($item->jenis_transaksi === 'transaksi keluar') {
    //                 $row['tgl_keluar'] = $item->tgl_transaksi;
    //                 $row['kode_barang_keluar'] = $item->kode_barang;
    //                 $row['nama_barang_keluar'] = $item->nama_barang;
    //                 $row['harga_keluar'] = $item->harga_satuan;
    //                 $row['jumlah_keluar'] = $item->qty;
    //                 $row['saldo_keluar'] = $item->total_harga;
    //             }
    //         }

    //         $laporan[] = $row;
    //     }


    //     dd($laporan);

    //     return view('Admin.LaporanPersediaan.laporan_persediaan');
    // }
}
