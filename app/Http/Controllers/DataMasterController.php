<?php

namespace App\Http\Controllers;

use App\Models\DataMaster;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataMasterController extends Controller
{
    public function tampil_data_master() 
    {
        $datas = DataMaster::query()->with('RiwayatStok')->get();
        return view('Admin.DataMaster.tampil_data_master', [
            'datas' => $datas,
        ]);
    }

    public function create_data_master(Request $request)
    {
        $validated = $request->validate([
            'kode'     => 'required|array',
            'nama'     => 'required|array',
            'kategori' => 'required|array',
            'satuan'   => 'required|array',
            'harga'    => 'required|array',
            'qty'      => 'required|array',
        ]);

        // Cek duplikasi dalam input
        $kodeArray = $request->kode;
        if (count($kodeArray) !== count(array_unique($kodeArray))) {
            return redirect()->back()->with('error', 'Terdapat duplikasi kode barang dalam input.');
        }

        // Cek duplikat di database
        $duplikatDB = DataMaster::whereIn('kode_barang', $kodeArray)->pluck('nama')->toArray();
        if (count($duplikatDB) > 0) {
            return redirect()->back()->with('error', 'Terdapat duplikasi data di database, dengan nama barang: ' . implode(', ', $duplikatDB));
        }

        DB::beginTransaction();
        try {
            foreach ($kodeArray as $i => $kodeBarang) {
                $nama     = $request->nama[$i];
                $kategori = $request->kategori[$i];
                $satuan   = $request->satuan[$i];
                $harga    = (int) str_replace('.', '', $request->harga[$i]);
                $qty      = (int) $request->qty[$i];

                $data = DataMaster::create([
                    'kode_barang' => $kodeBarang,
                    'nama'        => $nama,
                    'kategori'    => $kategori,
                    'satuan'      => $satuan,
                    'harga'       => $harga,
                ]);

                RiwayatStok::create([
                    'kode_barang' => $kodeBarang,
                    'qty_awal'    => $qty,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update_data_master(Request $request, $id)
    {
        $validated = $request->validate([
            'nama'     => 'required|string',
            'kategori' => 'required|string',
            'satuan'   => 'required|string',
            'harga'    => 'required|numeric',
            'qty'      => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Ambil data master berdasarkan id
            $dataMaster = DataMaster::findOrFail($id);

            // Update DataMaster
            $dataMaster->update([
                'nama'     => $request->nama,
                'kategori' => $request->kategori,
                'satuan'   => $request->satuan,
                'harga'    => $request->harga,
            ]);

            // Update RiwayatStok berdasarkan kode_barang
            RiwayatStok::where('kode_barang', $dataMaster->kode_barang)->update([
                'qty_awal' => $request->qty,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }


    // public function create_data_master(Request $request)
    // {
    //     $validated = $request->validate([
    //         'kode'     => 'required|array',
    //         'nama'     => 'required|array',
    //         'kategori' => 'required|array',
    //         'satuan'   => 'required|array',
    //         'harga'    => 'required|array',
    //         'qty'      => 'required|array',
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         foreach ($request->kode as $i => $kodeBarang) {
    //             $nama     = $request->nama[$i];
    //             $kategori = $request->kategori[$i];
    //             $satuan   = $request->satuan[$i];
    //             $harga    = (int) str_replace('.', '', $request->harga[$i]);
    //             $qty      = (int) $request->qty[$i];

    //             // Cek apakah kode_barang sudah ada
    //             $dataMaster = DataMaster::where('kode_barang', $kodeBarang)->first();

    //             if ($dataMaster) {
    //                 // Jika sudah ada: update harga (jumlahkan) dan update stok awal
    //                 $dataMaster->update([
    //                     'harga' => $dataMaster->harga + $harga
    //                 ]);

    //                 $stok = RiwayatStok::where('kode_barang', $dataMaster->kode_barang)->first();
    //                 if ($stok) {
    //                     $stok->increment('qty_awal', $qty);
    //                 } else {
    //                     RiwayatStok::create([
    //                         'kode_barang' => $kodeBarang,
    //                         'qty_awal'    => $qty,
    //                     ]);
    //                 }
    //             } else {
    //                 // Jika belum ada: buat baru
    //                 $new = DataMaster::create([
    //                     'kode_barang' => $kodeBarang,
    //                     'nama'        => $nama,
    //                     'kategori'    => $kategori,
    //                     'satuan'      => $satuan,
    //                     'harga'       => $harga,
    //                 ]);

    //                 RiwayatStok::create([
    //                     'kode_barang' => $kodeBarang,
    //                     'qty_awal'    => $qty,
    //                 ]);
    //             }
    //         }

    //         DB::commit();
    //         return redirect()->back()->with('success', 'Data berhasil disimpan.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    //     }
    // }
}
