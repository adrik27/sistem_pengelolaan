<?php

namespace App\Http\Controllers;

use App\Models\SaldoAwal;
use App\Models\DataMaster;
use Illuminate\Http\Request;
use App\Models\TransaksiMasuk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransaksiMasukController extends Controller
{
    public function tampil_transaksi_masuk(Request $request)
    {

        $req_status = $request->input('status');

        $data = collect();
        if (Auth::user()->jabatan_id == 1) { // super admin (administrator)
            $data = TransaksiMasuk::query()
                        ->where('status', $req_status)
                        ->with('DataMaster')
                        ->get();
                        
        } else if(Auth::user()->jabatan_id == 2) { // pengurus barang (admin bukan super admin)
            $data = TransaksiMasuk::query()
                        ->where('status', $req_status)
                        ->where('department_id', Auth::user()->department_id)
                        ->with('DataMaster')
                        ->get();

        } else { // pengguna barang (pegawai input transaksi)
            $data = TransaksiMasuk::query()
                ->where('status', $req_status)
                ->where('department_id', Auth::user()->department_id)
                ->with('DataMaster')
                ->get();
        }

        $Data_Barang = DataMaster::all();
        $Budget_Awal = SaldoAwal::first();

        return view('Admin.TransaksiMasuk.tampil_transaksi_masuk', [
            'data'  =>  $data,
            'data_barang' => $Data_Barang,
            'budget_awal' => $Budget_Awal,
        ]);
    }

    public function create_transaksi_masuk(Request $request)
    {
        DB::beginTransaction();

        try {
            $tgl_transaksi = now()->format('Y-m-d');
            $department_id = 2;
            // $department_id = Auth::user()->department_id;

            // Gabungkan data duplikat dari form (kode_barang sama -> jumlahkan qty & total_harga)
            $mergedItems = [];

            foreach ($request->kode as $index => $kode) {
                if (!$kode) continue; // skip jika kosong

                $qty = (int) $request->qty[$index];
                $harga = (int) $request->harga[$index];
                $total = (int) $request->total_harga[$index];

                if (isset($mergedItems[$kode])) {
                    $mergedItems[$kode]['qty'] += $qty;
                    $mergedItems[$kode]['total_harga'] += $total;
                } else {
                    $mergedItems[$kode] = [
                        'kode_barang' => $kode,
                        'qty' => $qty,
                        'harga' => $harga,
                        'total_harga' => $total,
                    ];
                }
            }

            foreach ($mergedItems as $item) {
                $kode_barang = $item['kode_barang'];
                $qty = $item['qty'];
                $harga = $item['harga'];
                $total_harga = $item['total_harga'];

                // Ambil barang terkait (assumsi ada relasi)
                $barang = DB::table('data_masters')->where('kode_barang', $kode_barang)->first();
                if (!$barang) continue;

                // Cek jika sudah ada transaksi dengan kode & tgl & departemen yg sama
                $existing = TransaksiMasuk::where('tgl_transaksi', $tgl_transaksi)
                    ->where('department_id', $department_id)
                    ->where('kode_barang', $kode_barang)
                    ->first();

                if ($existing) {
                    // Update qty dan saldo_digunakan
                    $existing->qty += $qty;
                    $existing->total_harga += $total_harga;
                    $existing->save();
                } else {
                    TransaksiMasuk::create([
                        'tgl_transaksi' => $tgl_transaksi,
                        'department_id' => $department_id,
                        'kode_barang' => $kode_barang,
                        'nama_barang' => $barang->nama,
                        'nama_satuan' => $barang->satuan,
                        'qty' => $qty,
                        'harga_satuan' => $harga,
                        'total_harga' => $total_harga,
                        'status' => 'pending'
                    ]);
                }
            }

            // Update saldo_awals: tambah saldo_digunakan
            $saldoAwal = SaldoAwal::where('department_id', $department_id)
                ->where('tahun', now()->year)
                ->first();

            if ($saldoAwal) {
                $saldoAwal->saldo_digunakan += array_sum(array_column($mergedItems, 'total_harga'));
                $saldoAwal->save();
            }

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
