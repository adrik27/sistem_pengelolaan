<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\SaldoAwal;
use App\Models\Transaksi;
use App\Models\MasterBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StokPersediaanBidang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator; // <-- Tambahkan ini

class TransaksiController extends Controller
{
    // ### TRANSAKSI MASUK ###
    public function tampil_transaksi_masuk(Request $request)
    {
        $req_status = '';
        $req_year = $request->input('tahun') ?? date('Y');

        if (Auth::user()->jabatan_id == 3) { // User
            $req_status = $request->input('status') ?? 'pending';
            $data = Transaksi::whereYear('tgl_transaksi', $req_year)
                ->where('status', $req_status)
                ->where('department_id', Auth::user()->department_id)
                ->where('jenis_transaksi', 'masuk')
                ->with('MasterBarang')
                ->with('Department')
                ->orderBy('tgl_transaksi', 'desc')
                ->get();
        } else { // Admin
            $req_status = $request->input('status') ?? 'pending';
            $data = Transaksi::whereYear('tgl_transaksi', $req_year)
                ->where('status', $req_status)
                ->where('jenis_transaksi', 'masuk')
                ->with('MasterBarang')
                ->with('Department')
                ->orderBy('tgl_transaksi', 'desc')
                ->get();
        }

        $Data_Barang = MasterBarang::orderBy('nama', 'asc')->get();

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

                $barang = DB::table('master_barangs')->where('kode_barang', $kode_barang)->first();
                if (!$barang) continue;

                $saldo_awal = SaldoAwal::where('department_id', $department_id)->where('tahun', now()->year)->first();
                // if ($total > $saldo_awal->saldo_awal) {
                //     DB::rollback();
                //     return redirect()->back()->with('error', 'Terjadi kesalahan: Saldo tidak mencukupi.');
                // }

                // Cek jika sudah ada transaksi dengan kode & tgl & departemen & status & jenis_transaksi yg sama
                $existing = Transaksi::where('department_id', $department_id)
                    // ->where('tgl_transaksi', $tgl_transaksi)
                    ->where('kode_barang', $kode_barang)
                    ->where('status', 'pending')
                    ->where('jenis_transaksi', 'masuk')
                    ->first();


                if ($existing) {
                    // cek saldo 
                    $total_saldo_exist = $total_harga + $existing->total_harga ?? 0;
                    if ($total_saldo_exist > (($saldo_awal->saldo_awal ?? 0) - ($saldo_awal->saldo_digunakan ?? 0))) {
                        DB::rollback();
                        return redirect()->back()->with('error', 'Terjadi kesalahan: Saldo tidak mencukupi.');
                    }

                    // cek ketersediaan stok di database
                    $total_qty_exis = $qty + $existing->qty ?? 0;
                    if ($total_qty_exis > $barang->qty_sisa) {
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
                        'jenis_transaksi'   => 'masuk',
                        'pembuat_id'        => Auth::user()->id,
                        'keterangan'        => "Transaksi User",
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
        $Transaksi = Transaksi::where('id', $id)->where('jenis_transaksi', 'masuk')->first();
        $year = Carbon::parse($Transaksi->tgl_transaksi)->format('Y');

        if ($Transaksi) {
            $saldoAwal = SaldoAwal::where('department_id', $Transaksi->department_id)
                ->where('tahun', $year)
                ->first();

            if ($saldoAwal) {
                // cek saldo
                $TotalHargaDataTransaksi = Transaksi::where('department_id', $Transaksi->department_id)
                    ->where('jenis_transaksi', 'masuk')
                    ->where('status', 'selesai')
                    ->sum('total_harga') ?? 0;

                if (($Transaksi->total_harga + $TotalHargaDataTransaksi) > $saldoAwal->saldo_awal) {
                    $Transaksi->update(
                        [
                            'status' => 'tolak',
                        ]
                    );
                    return redirect()->back()->with('error', 'Terjadi kesalahan: Saldo tidak mencukupi.');
                }

                // cek ketersediaan stok
                $TotalQtyTransaksi = Transaksi::where('department_id', $Transaksi->department_id)
                    ->where('jenis_transaksi', 'masuk')
                    ->where('status', 'selesai')
                    ->sum('qty') ?? 0;

                $DataQtyMasterBarang = MasterBarang::where('kode_barang', $Transaksi->kode_barang)
                    ->sum('qty_sisa') ?? 0;

                if (($Transaksi->qty + $TotalQtyTransaksi) > $DataQtyMasterBarang) {
                    $Transaksi->update(
                        [
                            'status' => 'tolak',
                        ]
                    );
                    return redirect()->back()->with('error', 'Terjadi kesalahan: Stok barang ' . ucwords($Transaksi->barang->nama) . ' tidak mencukupi.');
                }

                // Update transaksi
                $Transaksi->update(
                    [
                        'status' => 'selesai',
                        'verifikator_id' => Auth::user()->id
                    ]
                );

                // Update master_barangs: kurangi qty_Sisa
                DB::table('master_barangs')
                    ->where('kode_barang', $Transaksi->kode_barang)
                    ->decrement('qty_sisa', $Transaksi->qty);

                // Update saldo_awals: tambah saldo_digunakan
                $saldoAwal->saldo_digunakan += $Transaksi->total_harga;
                $saldoAwal->save();

                // cek barang Stok_persediaan_bidang
                $ExistingStokPersediaan = StokPersediaanBidang::where('kode_barang', $Transaksi->kode_barang)
                    ->where('department_id', $Transaksi->department_id)
                    ->exists();

                if (!$ExistingStokPersediaan) {
                    StokPersediaanBidang::create([
                        'kode_barang' => $Transaksi->kode_barang,
                        'department_id' => $Transaksi->department_id,
                        'qty' => $Transaksi->qty
                    ]);
                } else {
                    StokPersediaanBidang::where('kode_barang', $Transaksi->kode_barang)
                        ->where('department_id', $Transaksi->department_id)
                        ->increment('qty', $Transaksi->qty);
                }
            }

            return redirect()->back()->with('success', 'Transaksi penerimaan berhasil diverifikasi.');
        } else {
            return redirect()->back()->with('error', 'Transaksi penerimaan tidak ditemukan.');
        }
    }

    public function tolak_transaksi_masuk($id)
    {
        $Transaksi = Transaksi::where('id', $id)->where('jenis_transaksi', 'masuk')->first();

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
        $Transaksi = Transaksi::where('id', $id)->where('jenis_transaksi', 'masuk')->first();

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
        $Transaksi = Transaksi::where('id', $id)->where('jenis_transaksi', 'masuk')->first();

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

        // tampil data barang jenis transaksi = keluar
        if (Auth::user()->jabatan_id == 3) { // user
            $req_status = $request->input('status') ?? 'selesai';
            $data = Transaksi::whereYear('tgl_transaksi', $req_year)
                ->where('status', $req_status)
                ->where('department_id', Auth::user()->department_id)
                ->where('jenis_transaksi', 'keluar')
                ->with('MasterBarang')
                ->with('Department')
                ->orderBy('tgl_transaksi', 'desc')
                ->get();
        } else { // admin
            $req_status = $request->input('status') ?? 'selesai';
            $data = Transaksi::whereYear('tgl_transaksi', $req_year)
                ->where('status', $req_status)
                ->where('jenis_transaksi', 'keluar')
                ->with('MasterBarang')
                ->with('Department')
                ->orderBy('tgl_transaksi', 'desc')
                ->get();
        }


        // ambil data barang berdasarkan transaksi masuk
        if (Auth::user()->jabatan_id == 3) { //user
            $Data_Barang_By_Transaksi_Masuk = DB::table('transaksis')
                ->select(
                    'kode_barang',
                    'nama_barang as nama',
                    DB::raw('SUM(qty) as qty_digunakan')
                )
                ->where('department_id', Auth::user()->department_id)
                ->where('status', 'selesai')
                ->where('jenis_transaksi', 'masuk')
                ->groupBy('kode_barang', 'nama_barang')
                ->get();
        } else { // admin
            $Data_Barang_By_Transaksi_Masuk = DB::table('transaksis')
                ->select(
                    'kode_barang',
                    'nama_barang as nama',
                    DB::raw('SUM(qty) as qty_digunakan')
                )
                ->where('status', 'selesai')
                ->where('jenis_transaksi', 'masuk')
                ->groupBy('kode_barang', 'nama_barang')
                ->get();
        }

        // ambil nilai budget awal tahun ini
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

                $barang = DB::table('master_barangs')->where('kode_barang', $kode_barang)->first();
                if (!$barang) continue;

                // cek barang di stok_persediaan berdasarkan bidangnya
                $stok_persediaan_bidang = StokPersediaanBidang::where('kode_barang', $kode_barang)
                    ->where('department_id', $department_id)
                    ->first();

                // jika barang di stok_persediaan berdasarkan bidangnya ada, maka stok kurangi
                if ($stok_persediaan_bidang) {
                    $stok_persediaan_bidang->qty -= $qty;
                    $stok_persediaan_bidang->save();
                } else {
                    StokPersediaanBidang::create([
                        'kode_barang' => $kode_barang,
                        'department_id' => $department_id,
                        'qty' => $qty,
                    ]);
                }

                // cek saldo awal mencukupi tidak
                $saldo_awal = SaldoAwal::where('department_id', $department_id)->where('tahun', now()->year)->first();
                if ($total > $saldo_awal->saldo_digunakan) {
                    DB::rollback();
                    return redirect()->back()->with('error', 'Terjadi kesalahan: Saldo tidak mencukupi.');
                }

                // Cek jika sudah ada transaksi dengan kode & tgl & departemen & status & jenis_transaksi yg sama
                $existing = Transaksi::where('department_id', $department_id)
                    // ->where('tgl_transaksi', $tgl_transaksi)
                    ->where('kode_barang', $kode_barang)
                    ->where('status', 'pending')
                    ->where('jenis_transaksi', 'keluar')
                    ->first();

                if ($existing) {
                    // cek saldo di database
                    $total_saldo_exist = $total_harga + $existing->total_harga ?? 0;
                    if ($total_saldo_exist > ($saldo_awal->saldo_digunakan ?? 0)) {
                        DB::rollback();
                        return redirect()->back()->with('error', 'Terjadi kesalahan: Saldo tidak mencukupi.');
                    }

                    // cek ketersediaan stok di database
                    $total_qty_exis = $qty + $existing->qty ?? 0;
                    if ($total_qty_exis > $barang->sisa_qty) {
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
                        'status'            => 'selesai',
                        'jenis_transaksi'   => 'keluar',
                        'pembuat_id'        => Auth::user()->id,
                        'verifikator_id'    => Auth::user()->id,
                        'keterangan'        => "Transaksi User",
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

    // public function verifikasi_transaksi_keluar($id)
    // {
    //     $Transaksi = Transaksi::where('id', $id)->where('jenis_transaksi', 'transaksi keluar')->first();
    //     $year = Carbon::parse($Transaksi->tgl_transaksi)->format('Y');

    //     if ($Transaksi) {
    //         $Transaksi->update(
    //             [
    //                 'status' => 'verifikasi',
    //                 'verifikator_id' => Auth::user()->id
    //             ]
    //         );


    //         $saldoAwal = SaldoAwal::where('department_id', $Transaksi->department_id)
    //             ->where('tahun', $year)
    //             ->first();

    //         if ($saldoAwal) {
    //             // Update data_masters: kurangi qty_digunakan
    //             DB::table('data_masters')
    //                 ->where('kode_barang', $Transaksi->kode_barang)
    //                 ->decrement('qty_digunakan', $Transaksi->qty);

    //             // Update saldo_awals: kurangi saldo_digunakan
    //             $saldoAwal->saldo_digunakan -= $Transaksi->total_harga;
    //             $saldoAwal->save();
    //         }

    //         return redirect()->back()->with('success', 'Transaksi pengeluaran berhasil diverifikasi.');
    //     } else {
    //         return redirect()->back()->with('error', 'Transaksi pengeluaran tidak ditemukan.');
    //     }
    // }

    // public function tolak_transaksi_keluar($id)
    // {
    //     $Transaksi = Transaksi::where('id', $id)->where('jenis_transaksi', 'transaksi keluar')->first();

    //     if ($Transaksi) {
    //         $Transaksi->update(
    //             [
    //                 'status' => 'tolak',
    //             ]
    //         );

    //         return redirect()->back()->with('success', 'Transaksi pengeluaran berhasil ditolak.');
    //     } else {
    //         return redirect()->back()->with('error', 'Transaksi pengeluaran tidak ditemukan.');
    //     }
    // }

    // public function update_transaksi_keluar(Request $request, $id)
    // {
    //     $Transaksi = Transaksi::where('id', $id)->where('jenis_transaksi', 'transaksi keluar')->first();

    //     if ($Transaksi) {
    //         $Transaksi->update([
    //             'qty' => $request->qty,
    //             'total_harga' => $request->total_harga,
    //         ]);
    //         return redirect()->back()->with('success', 'Transaksi pengeluaran berhasil diperbarui.');
    //     } else {
    //         return redirect()->back()->with('error', 'Transaksi pengeluaran tidak ditemukan.');
    //     }
    // }

    // public function hapus_transaksi_keluar($id)
    // {
    //     $Transaksi = Transaksi::where('id', $id)->where('jenis_transaksi', 'transaksi keluar')->first();

    //     if ($Transaksi && $Transaksi->status == 'pending') {
    //         $Transaksi->delete();
    //         return redirect()->back()->with('success', 'Transaksi pengeluaran berhasil dihapus.');
    //     } else {
    //         return redirect()->back()->with('error', 'Transaksi pengeluaran tidak ditemukan atau statusnya sudah terverifikasi.');
    //     }
    // }
    // ### END TRANSAKSI KELUAR ###
}
