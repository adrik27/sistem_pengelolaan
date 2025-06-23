<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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

        $req_status = '';
        $req_year = $request->input('tahun') ?? date('Y');

        if (Auth::user()->jabatan_id == 1) { // super admin (administrator)
            $req_status = $request->input('status') ?? 'verifikasi';
            $data = TransaksiMasuk::whereYear('tgl_transaksi', $req_year)
                        ->where('status', $req_status)
                        ->with('DataMaster')
                        ->with('Department')
                        ->get();
                        
        } else if(Auth::user()->jabatan_id == 2) { // pengurus barang (admin bukan super admin)
            $req_status = 'pending';
            $data = TransaksiMasuk::whereYear('tgl_transaksi', $req_year)
                        ->where('status', $req_status)
                        ->where('department_id', Auth::user()->department_id)
                        ->with('DataMaster')
                        ->with('Department')
                        ->get();

        } else { // pengguna barang (pegawai input transaksi)
            $req_status = $request->input('status') ?? 'verifikasi';
            $data = TransaksiMasuk::whereYear('tgl_transaksi', $req_year)
                ->where('status', $req_status)
                ->where('department_id', Auth::user()->department_id)
                ->with('DataMaster')
                ->with('Department')
                ->get();
                
        }
        
        $Data_Barang = DataMaster::all();

        $Budget_Awal = SaldoAwal::where('department_id', Auth::user()->department_id)->where('tahun', now()->year)->first();

        return view('Admin.TransaksiMasuk.tampil_transaksi_masuk', [
            'data'          =>  $data,
            'data_barang'   =>  $Data_Barang,
            'budget_awal'   =>  $Budget_Awal,
            'req_status'    =>  $req_status,
            'req_year'      =>  $req_year,
        ]);
    }

    public function create_transaksi_masuk(Request $request)
    {
        DB::beginTransaction();

        try {
            $tgl_transaksi = now()->format('Y-m-d');
            $department_id = Auth::user()->department_id;

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
                        'kode_barang'   => $kode_barang,
                        'nama_barang'   => $barang->nama,
                        'nama_satuan'   => $barang->satuan,
                        'qty'           => $qty,
                        'harga_satuan'  => $harga,
                        'total_harga'   => $total_harga,
                        'status'        => 'pending',
                        'pembuat_id'    => Auth::user()->id,
                    ]);
                }
                // Update data_masters: tambah qty_digunakan
                // DB::table('data_masters')
                //     ->where('kode_barang', $kode_barang)
                //     ->increment('qty_digunakan', $qty);
            }

            // Update saldo_awals: tambah saldo_digunakan
            // $saldoAwal = SaldoAwal::where('department_id', $department_id)
            //     ->where('tahun', now()->year)
            //     ->first();

            // if ($saldoAwal) {
            //     $saldoAwal->saldo_digunakan += array_sum(array_column($mergedItems, 'total_harga'));
            //     $saldoAwal->save();
            // }

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function verifikasi_transaksi_masuk($id)
    {
        $TransaksiMasuk = TransaksiMasuk::where('id', $id)->first();
        $year = Carbon::parse($TransaksiMasuk->tgl_transaksi)->format('Y');

        if ($TransaksiMasuk) {
            $TransaksiMasuk->update(
            [
                'status' => 'verifikasi', 
                'verifikator_id' => Auth::user()->id
            ]);

            
            $saldoAwal = SaldoAwal::where('department_id', $TransaksiMasuk->department_id)
                ->where('tahun', $year)
                ->first();

            if ($saldoAwal) {
                // Update data_masters: tambah qty_digunakan
                DB::table('data_masters')
                    ->where('kode_barang', $TransaksiMasuk->kode_barang)
                    ->increment('qty_digunakan', $TransaksiMasuk->qty);

                // Update saldo_awals: tambah saldo_digunakan
                $saldoAwal->saldo_digunakan += $TransaksiMasuk->total_harga;
                $saldoAwal->save();
            }

            return redirect()->back()->with('success', 'Transaksi berhasil diverifikasi.');
        } else {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }
    }
}
