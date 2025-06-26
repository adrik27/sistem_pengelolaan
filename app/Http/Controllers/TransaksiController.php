<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\SaldoAwal;
use App\Models\Transaksi;
use App\Models\DataMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    // ### TRANSAKSI MASUK ###
    public function tampil_transaksi_masuk(Request $request)
    {

        $req_status = '';
        $req_year = $request->input('tahun') ?? date('Y');

        if (Auth::user()->jabatan_id == 1) { // super admin (administrator)
            $req_status = $request->input('status') ?? 'verifikasi';
            $data = Transaksi::whereYear('tgl_transaksi', $req_year)
                ->where('status', $req_status)
                ->where('jenis_transaksi', 'transaksi masuk')
                ->with('DataMaster')
                ->with('Department')
                ->orderBy('tgl_transaksi', 'desc')
                ->get();
        } else if (Auth::user()->jabatan_id == 2) { // pengurus barang (admin bukan super admin)
            $req_status = $request->input('status') ?? 'verifikasi';
            $data = Transaksi::whereYear('tgl_transaksi', $req_year)
                ->where('status', $req_status)
                ->where('department_id', Auth::user()->department_id)
                ->where('jenis_transaksi', 'transaksi masuk')
                ->with('DataMaster')
                ->with('Department')
                ->orderBy('tgl_transaksi', 'desc')
                ->get();
        } else { // pengguna barang (pegawai input transaksi)
            $req_status = $request->input('status') ?? 'verifikasi';
            $data = Transaksi::whereYear('tgl_transaksi', $req_year)
                ->where('status', $req_status)
                ->where('department_id', Auth::user()->department_id)
                ->where('jenis_transaksi', 'transaksi masuk')
                ->with('DataMaster')
                ->with('Department')
                ->orderBy('tgl_transaksi', 'desc')
                ->get();
        }

        $Data_Barang = DataMaster::orderBy('nama', 'asc')->get();

        $Budget_Awal = SaldoAwal::where('department_id', Auth::user()->department_id)
            ->where('tahun', now()->year)
            ->first();

        return view('Admin.Transaksi.tampil_transaksi_masuk', [
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

                // cek saldo awal mencukupi tidak
                $saldo_awal = SaldoAwal::where('department_id', $department_id)->where('tahun', now()->year)->first();

                // Cek jika sudah ada transaksi dengan kode & tgl & departemen & status & jenis_transaksi yg sama
                $existing = Transaksi::where('department_id', $department_id)
                    // ->where('tgl_transaksi', $tgl_transaksi)
                    ->where('kode_barang', $kode_barang)
                    ->where('status', 'pending')
                    ->where('jenis_transaksi', 'transaksi masuk')
                    ->first();


                if ($existing) {
                    // cek saldo 
                    $total_saldo_exist = $total_harga + $existing->total_harga ?? 0;
                    if ($total_saldo_exist > $saldo_awal->saldo_awal) {
                        DB::rollback();
                        return redirect()->back()->with('error', 'Terjadi kesalahan: Saldo tidak mencukupi.');
                    }

                    // cek ketersediaan stok di database
                    $total_qty_exis = $qty + $existing->qty ?? 0;
                    if ($total_qty_exis > $barang->qty_awal) {
                        DB::rollback();
                        return redirect()->back()->with('error', 'Terjadi kesalahan: Stok barang ' . ucwords($barang->nama) . ' tidak mencukupi.');
                    }

                    // Update qty dan saldo_digunakan
                    $existing->qty += $qty;
                    $existing->total_harga += $total_harga;
                    $existing->save();
                } else {
                    Transaksi::create([
                        'tgl_transaksi'     => $tgl_transaksi,
                        'department_id'     => $department_id,
                        'kode_barang'       => $kode_barang,
                        'nama_barang'       => $barang->nama,
                        'nama_satuan'       => $barang->satuan,
                        'qty'               => $qty,
                        'harga_satuan'      => $harga,
                        'total_harga'       => $total_harga,
                        'status'            => 'pending',
                        'jenis_transaksi'   => 'transaksi masuk',
                        'pembuat_id'        => Auth::user()->id,
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi penerimaan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function verifikasi_transaksi_masuk($id)
    {
        $Transaksi = Transaksi::where('id', $id)->where('jenis_transaksi', 'transaksi masuk')->first();
        $year = Carbon::parse($Transaksi->tgl_transaksi)->format('Y');

        if ($Transaksi) {
            $Transaksi->update(
                [
                    'status' => 'verifikasi',
                    'verifikator_id' => Auth::user()->id
                ]
            );

            $saldoAwal = SaldoAwal::where('department_id', $Transaksi->department_id)
                ->where('tahun', $year)
                ->first();

            if ($saldoAwal) {
                // Update data_masters: tambah qty_digunakan
                DB::table('data_masters')
                    ->where('kode_barang', $Transaksi->kode_barang)
                    ->increment('qty_digunakan', $Transaksi->qty);

                // Update saldo_awals: tambah saldo_digunakan
                $saldoAwal->saldo_digunakan += $Transaksi->total_harga;
                $saldoAwal->save();
            }

            return redirect()->back()->with('success', 'Transaksi penerimaan berhasil diverifikasi.');
        } else {
            return redirect()->back()->with('error', 'Transaksi penerimaan tidak ditemukan.');
        }
    }

    public function tolak_transaksi_masuk($id)
    {
        $Transaksi = Transaksi::where('id', $id)->where('jenis_transaksi', 'transaksi masuk')->first();

        if ($Transaksi) {
            $Transaksi->update(
                [
                    'status' => 'tolak',
                ]
            );

            return redirect()->back()->with('success', 'Transaksi penerimaan berhasil ditolak.');
        } else {
            return redirect()->back()->with('error', 'Transaksi penerimaan tidak ditemukan.');
        }
    }

    public function update_transaksi_masuk(Request $request, $id)
    {
        $Transaksi = Transaksi::where('id', $id)->where('jenis_transaksi', 'transaksi masuk')->first();

        if ($Transaksi) {
            $Transaksi->update([
                'qty' => $request->qty,
                'total_harga' => $request->total_harga,
            ]);
            return redirect()->back()->with('success', 'Transaksi penerimaan berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Transaksi penerimaan tidak ditemukan.');
        }
    }

    public function hapus_transaksi_masuk($id)
    {
        $Transaksi = Transaksi::where('id', $id)->where('jenis_transaksi', 'transaksi masuk')->first();

        if ($Transaksi && $Transaksi->status == 'pending') {
            $Transaksi->delete();
            return redirect()->back()->with('success', 'Transaksi penerimaan berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Transaksi penerimaan tidak ditemukan atau statusnya sudah terverifikasi.');
        }
    }
    // ### END TRANSAKSI MASUK ###


    // ### TRANSAKSI KELUAR ###
    public function tampil_transaksi_keluar(Request $request)
    {

        $req_status = '';
        $req_year = $request->input('tahun') ?? date('Y');

        if (Auth::user()->jabatan_id == 1) { // super admin (administrator)
            $req_status = $request->input('status') ?? 'verifikasi';
            $data = Transaksi::whereYear('tgl_transaksi', $req_year)
                ->where('status', $req_status)
                ->where('jenis_transaksi', 'transaksi keluar')
                ->with('DataMaster')
                ->with('Department')
                ->orderBy('tgl_transaksi', 'desc')
                ->get();
        } else if (Auth::user()->jabatan_id == 2) { // pengurus barang (admin bukan super admin)
            $req_status = $request->input('status') ?? 'verifikasi';
            $data = Transaksi::whereYear('tgl_transaksi', $req_year)
                ->where('status', $req_status)
                ->where('department_id', Auth::user()->department_id)
                ->where('jenis_transaksi', 'transaksi keluar')
                ->with('DataMaster')
                ->with('Department')
                ->orderBy('tgl_transaksi', 'desc')
                ->get();
        } else { // pengguna barang (pegawai input transaksi)
            $req_status = $request->input('status') ?? 'verifikasi';
            $data = Transaksi::whereYear('tgl_transaksi', $req_year)
                ->where('status', $req_status)
                ->where('department_id', Auth::user()->department_id)
                ->where('jenis_transaksi', 'transaksi keluar')
                ->with('DataMaster')
                ->with('Department')
                ->orderBy('tgl_transaksi', 'desc')
                ->get();
        }

        $Data_Barang_By_Transaksi_Masuk = DB::table('transaksis')
            ->select(
                'kode_barang',
                'nama_barang as nama',
                DB::raw('SUM(qty) as qty_digunakan')
            )
            ->where('status', 'verifikasi')
            ->where('jenis_transaksi', 'transaksi masuk')
            ->groupBy('kode_barang', 'nama_barang')
            ->get();


        $Budget_Awal = SaldoAwal::where('department_id', Auth::user()->department_id)->where('tahun', now()->year)->first();

        return view('Admin.Transaksi.tampil_transaksi_keluar', [
            'data'          =>  $data,
            'data_barang'   =>  $Data_Barang_By_Transaksi_Masuk,
            'budget_awal'   =>  $Budget_Awal,
            'req_status'    =>  $req_status,
            'req_year'      =>  $req_year,
        ]);
    }

    public function create_transaksi_keluar(Request $request)
    {
        DB::beginTransaction();

        try {
            $tgl_transaksi = now()->format('Y-m-d');
            $department_id = Auth::user()->department_id;

            $mergedItems = [];

            foreach ($request->kode as $index => $kode) {
                if (!$kode) continue; // skip jika kosong

                $qty    = (int) $request->qty[$index];
                $harga  = (int) $request->harga[$index];
                $total  = (int) $request->total_harga[$index];

                // proses merge
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

                // cek saldo awal mencukupi tidak
                $saldo_awal = SaldoAwal::where('department_id', $department_id)->where('tahun', now()->year)->first();
                if ($total > $saldo_awal->saldo_awal) {
                    DB::rollback();
                    return redirect()->back()->with('error', 'Terjadi kesalahan: Saldo tidak mencukupi.');
                }

                // Cek jika sudah ada transaksi dengan kode & tgl & departemen & status & jenis_transaksi yg sama
                $existing = Transaksi::where('department_id', $department_id)
                    // ->where('tgl_transaksi', $tgl_transaksi)
                    ->where('kode_barang', $kode_barang)
                    ->where('status', 'pending')
                    ->where('jenis_transaksi', 'transaksi keluar')
                    ->first();

                if ($existing) {
                    // cek saldo di database
                    $total_saldo_exist = $total_harga + $existing->total_harga ?? 0;
                    if ($total_saldo_exist > $saldo_awal->saldo_awal) {
                        DB::rollback();
                        return redirect()->back()->with('error', 'Terjadi kesalahan: Saldo tidak mencukupi.');
                    }

                    // cek ketersediaan stok di database
                    $total_qty_exis = $qty + $existing->qty ?? 0;
                    if ($total_qty_exis > $barang->qty_awal) {
                        DB::rollback();
                        return redirect()->back()->with('error', 'Terjadi kesalahan: Stok barang ' . ucwords($barang->nama) . ' tidak mencukupi.');
                    }

                    // Update qty dan saldo_digunakan
                    $existing->qty += $qty;
                    $existing->total_harga += $total_harga;
                    $existing->save();
                } else {

                    Transaksi::create([
                        'tgl_transaksi'     => $tgl_transaksi,
                        'department_id'     => $department_id,
                        'kode_barang'       => $kode_barang,
                        'nama_barang'       => $barang->nama,
                        'nama_satuan'       => $barang->satuan,
                        'qty'               => $qty,
                        'harga_satuan'      => $harga,
                        'total_harga'       => $total_harga,
                        'status'            => 'pending',
                        'jenis_transaksi'   => 'transaksi keluar',
                        'pembuat_id'        => Auth::user()->id,
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi pengeluaran berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function verifikasi_transaksi_keluar($id)
    {
        $Transaksi = Transaksi::where('id', $id)->where('jenis_transaksi', 'transaksi keluar')->first();
        $year = Carbon::parse($Transaksi->tgl_transaksi)->format('Y');

        if ($Transaksi) {
            $Transaksi->update(
                [
                    'status' => 'verifikasi',
                    'verifikator_id' => Auth::user()->id
                ]
            );


            $saldoAwal = SaldoAwal::where('department_id', $Transaksi->department_id)
                ->where('tahun', $year)
                ->first();

            if ($saldoAwal) {
                // Update data_masters: kurangi qty_digunakan
                DB::table('data_masters')
                    ->where('kode_barang', $Transaksi->kode_barang)
                    ->decrement('qty_digunakan', $Transaksi->qty);

                // Update saldo_awals: kurangi saldo_digunakan
                $saldoAwal->saldo_digunakan -= $Transaksi->total_harga;
                $saldoAwal->save();
            }

            return redirect()->back()->with('success', 'Transaksi pengeluaran berhasil diverifikasi.');
        } else {
            return redirect()->back()->with('error', 'Transaksi pengeluaran tidak ditemukan.');
        }
    }

    public function tolak_transaksi_keluar($id)
    {
        $Transaksi = Transaksi::where('id', $id)->where('jenis_transaksi', 'transaksi keluar')->first();

        if ($Transaksi) {
            $Transaksi->update(
                [
                    'status' => 'tolak',
                ]
            );

            return redirect()->back()->with('success', 'Transaksi pengeluaran berhasil ditolak.');
        } else {
            return redirect()->back()->with('error', 'Transaksi pengeluaran tidak ditemukan.');
        }
    }

    public function update_transaksi_keluar(Request $request, $id)
    {
        $Transaksi = Transaksi::where('id', $id)->where('jenis_transaksi', 'transaksi keluar')->first();

        if ($Transaksi) {
            $Transaksi->update([
                'qty' => $request->qty,
                'total_harga' => $request->total_harga,
            ]);
            return redirect()->back()->with('success', 'Transaksi pengeluaran berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Transaksi pengeluaran tidak ditemukan.');
        }
    }

    public function hapus_transaksi_keluar($id)
    {
        $Transaksi = Transaksi::where('id', $id)->where('jenis_transaksi', 'transaksi keluar')->first();

        if ($Transaksi && $Transaksi->status == 'pending') {
            $Transaksi->delete();
            return redirect()->back()->with('success', 'Transaksi pengeluaran berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Transaksi pengeluaran tidak ditemukan atau statusnya sudah terverifikasi.');
        }
    }
    // ### END TRANSAKSI KELUAR ###
}
