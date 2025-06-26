<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\SaldoAwal;
use App\Models\Transaksi;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanPersediaanController extends Controller
{

    public function tampil_laporan_persediaan(Request $request)
    {

        // proses filter
        $req_department = $request->input('department_id');
        $tahun_from = $request->input('tahun_from') ?? date('Y');
        $tahun_to = $request->input('tahun_to') ?? date('Y');

        $range_tahun = [
            Carbon::createFromDate($tahun_from)->startOfYear()->toDateString(),
            Carbon::createFromDate($tahun_to)->endOfYear()->toDateString()
        ];

        if (Auth::user()->jabatan_id == 3) { // pengguna
            $departments = collect(); // kosong karena user hanya bisa akses departemen tertentu
            $transaksi = Transaksi::where('status', 'verifikasi')
                ->where('department_id', Auth::user()->department_id)
                ->whereBetween('tgl_transaksi', $range_tahun)
                ->orderBy('tgl_transaksi')
                ->get();
        } else {
            $departments = Department::where('status', 'aktif')->where('id', '!=', 1)->get();
            $transaksi = Transaksi::where('status', 'verifikasi')
                ->where('department_id', $req_department)
                ->whereBetween('tgl_transaksi', $range_tahun)
                ->orderBy('tgl_transaksi')
                ->get();
        }


        // proses olah data untuk di tampilkan
        $grouped = [];

        // Kelompokkan transaksi masuk & keluar per dept + kode_barang
        foreach ($transaksi as $item) {
            $key = $item->department_id . '-' . $item->kode_barang;

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'masuk' => [],
                    'keluar' => [],
                    'saldo_awal' => 0,
                ];
            }

            if ($item->jenis_transaksi === 'transaksi masuk') {
                $grouped[$key]['masuk'][] = $item;
            } elseif ($item->jenis_transaksi === 'transaksi keluar') {
                $grouped[$key]['keluar'][] = $item;
            }
        }

        // Ambil saldo awal berdasarkan department & tahun
        foreach ($grouped as $key => $value) {
            [$dept, $kode] = explode('-', $key);
            $tahun = $value['masuk'][0]->tgl_transaksi->format('Y') ?? now()->format('Y');

            $saldo = SaldoAwal::where('department_id', $dept)
                ->where('tahun', $tahun)
                ->first();


            $department = Department::find($dept);

            $grouped[$key]['saldo_awal'] = $saldo ? $saldo->saldo_awal : 0;
        }

        // Gabungkan masuk dan keluar sejajar per baris
        $laporan = [];
        foreach ($grouped as $key => $data) {
            $jumlahBaris = max((count($data['masuk']) ?? 0), (count($data['keluar']) ?? 0));
            [$dept, $kode] = explode('-', $key);
            $saldo_awal = $data['saldo_awal'];

            for ($i = 0; $i < $jumlahBaris; $i++) {
                $masuk = $data['masuk'][$i] ?? null;
                $keluar = $data['keluar'][$i] ?? null;

                $laporan[] = [
                    'department_id' => $department->nama,
                    'saldo_awal'    => $saldo_awal,

                    'tgl_masuk'     => $masuk?->tgl_transaksi,
                    'kode_masuk'    => $masuk?->kode_barang,
                    'nama_masuk'    => $masuk?->nama_barang,
                    'harga_masuk'   => $masuk?->harga_satuan,
                    'qty_masuk'     => $masuk?->qty,
                    'saldo_masuk'   => ($masuk?->harga_satuan ?? 0) * ($masuk?->qty ?? 0),

                    'tgl_keluar'    => $keluar?->tgl_transaksi,
                    'kode_keluar'   => $keluar?->kode_barang,
                    'nama_keluar'   => $keluar?->nama_barang,
                    'harga_keluar'  => $keluar?->harga_satuan,
                    'qty_keluar'    => $keluar?->qty,
                    'saldo_keluar'  => ($keluar?->harga_satuan ?? 0) * ($keluar?->qty ?? 0),

                    'sisa_stok'     => ($masuk?->qty ?? 0) - ($keluar?->qty ?? 0),
                    'sisa_saldo'    => (($masuk?->harga_satuan ?? 0) * ($masuk?->qty ?? 0)) - (($keluar?->harga_satuan ?? 0) * ($keluar?->qty ?? 0)),
                ];
            }
        }

        return view('Admin.LaporanPersediaan.laporan_persediaan', [
            'laporan' => $laporan,

            'tahun_from' => $tahun_from,
            'tahun_to' => $tahun_to,
            'req_departments' => $req_department,
            'departments' => $departments,

            // 'jumlah_merge' => $jumlahBaris,
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
