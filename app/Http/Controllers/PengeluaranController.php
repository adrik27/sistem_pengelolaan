<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengeluaranController extends Controller
{
    public function store(Request $request)
    {
        $tanggal    = $request->tanggal;
        $bulan      = $request->bulan;
        $bulan      = str_pad($bulan, 2, '0', STR_PAD_LEFT);
        $tahun      = $request->tahun;

        $tanggal_pembukuan = $tahun . '-' . $bulan . '-' . $tanggal;

        $validasiData = $request->validate([
            'tanggal' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
            'qty' => 'required',
        ]);

        $validasiData['tanggal_pembukuan'] = $tanggal_pembukuan;
        $validasiData['keterangan'] = $request->keterangan;
        $validasiData['status_pengeluaran'] = $request->status_pengeluaran;

        $dataBarang = DataBarang::where('id', $request->kode_barang)->first();

        $validasiData['kode_barang'] = $dataBarang->kode_barang;
        $validasiData['nama_barang'] = $dataBarang->nama_barang;
        $validasiData['bidang_id'] = Auth::user()->bidang_id;

        // dd($validasiData);
        // create
        $a = Pengeluaran::create($validasiData);
        if ($a) {

            // jika ada update qty saja

            // $barang = Pengeluaran::where('kode_barang', $request->kode_barang)->first();
            // $barang->update([
            //     'qty_sisa' => $barang->qty_sisa - $request->qty,
            // ]);

            return redirect()->back()->with('success', 'Pengeluaran berhasil disimpan.');
        } else {
            return redirect()->back()->with('error', 'Pengeluaran gagal disimpan.');
        }
    }

    public function delete($id)
    {
        $a = Pengeluaran::find($id)->delete();
        if ($a) {
            return redirect()->back()->with('success', 'Pengeluaran berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Pengeluaran gagal dihapus.');
        }
    }
}
