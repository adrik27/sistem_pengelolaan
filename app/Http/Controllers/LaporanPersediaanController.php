<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class LaporanPersediaanController extends Controller
{
    public function tampil_laporan_persediaan()
    {
        $data = Transaksi::where('status', 'verifikasi')->get();

        // Kelompokkan berdasarkan department_id dan kode_barang
        $grouped = $data->groupBy(function ($item) {
            return $item->department_id . '-' . $item->kode_barang;
        });

        $laporan = [];

        foreach ($grouped as $key => $items) {
            $row = [
                'department_id' => $items->first()->department_id,
                'kode_barang' => $items->first()->kode_barang,

                // Penerimaan
                'tgl_masuk' => null,
                'nama_barang_masuk' => null,
                'harga_masuk' => null,
                'jumlah_masuk' => null,
                'saldo_masuk' => null,

                // Pengeluaran
                'tgl_keluar' => null,
                'nama_barang_keluar' => null,
                'harga_keluar' => null,
                'jumlah_keluar' => null,
                'saldo_keluar' => null,
            ];


            foreach ($items as $item) {
                if ($item->jenis_transaksi === 'transaksi masuk') {
                    $row['tgl_masuk'] = $item->tgl_transaksi;
                    $row['nama_barang_masuk'] = $item->nama_barang;
                    $row['harga_masuk'] = $item->harga_satuan;
                    $row['jumlah_masuk'] = $item->qty;
                    $row['saldo_masuk'] = $item->total_harga;
                } elseif ($item->jenis_transaksi === 'transaksi keluar') {
                    $row['tgl_keluar'] = $item->tgl_transaksi;
                    $row['nama_barang_keluar'] = $item->nama_barang;
                    $row['harga_keluar'] = $item->harga_satuan;
                    $row['jumlah_keluar'] = $item->qty;
                    $row['saldo_keluar'] = $item->total_harga;
                }
            }

            $laporan[] = $row;
        }


        dd($laporan);

        return view('Admin.LaporanPersediaan.laporan_persediaan');
    }
}
