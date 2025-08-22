<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use App\Models\MasterBarang;
use App\Models\Penerimaan;
use App\Models\Pengeluaran;
use App\Models\SaldoAwal;
use App\Models\StokPersediaanBidang;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator; // <-- Tambahkan ini

class TransaksiController extends Controller
{
    // ### TRANSAKSI MASUK ###


    public function edit_penerimaan($id)
    {
        $data = Penerimaan::find($id);
        return view('Admin.penerimaan.edit', [
            'data' => $data
        ]);
    }

    public function tambah_penerimaan(Request $request)
    {
        // Ambil bulan dan tahun dari query parameter, jika ada.
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        return view('Admin.penerimaan.tambah', [
            'selected_bulan' => $bulan,
            'selected_tahun' => $tahun,
        ]);
    }

    public function ambil_penerimaan(Request $request)
    {
        $data = Penerimaan::where('tanggal_pembukuan', $request->tanggal_pembukuan)
            ->where('bidang_id', Auth::user()->bidang_id)
            ->where('supplier', $request->supplier)
            ->where('no_faktur', $request->no_faktur)
            ->where('status_penerimaan', $request->status_penerimaan)
            ->where('no_nota', $request->no_nota)
            ->where('no_terima', $request->no_terima)
            ->where('sumber_dana', $request->sumber_dana)
            ->get();
        return response()->json($data);
    }

    public function penerimaan(Request $request)
    {
        // 1. Ambil nilai filter dari request TANPA nilai default.
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        // 2. Inisialisasi data sebagai koleksi kosong.
        // Data hanya akan diisi jika ada filter yang diterapkan.
        $data = collect();

        // 3. Cek apakah pengguna sudah memilih bulan dan tahun.
        if ($bulan && $tahun) {
            // Jika ya, jalankan query untuk mengambil data.
            $data = Penerimaan::query()
                ->whereMonth('tanggal_pembukuan', $bulan)
                ->whereYear('tanggal_pembukuan', $tahun)
                ->orderBy('tanggal_pembukuan', 'desc')
                ->get();
        }

        // 4. Kirim data (bisa berisi hasil query atau koleksi kosong) beserta
        //    nilai filter yang dipilih ke view.
        return view('Admin.Transaksi.tampil_transaksi_masuk', [
            'data' => $data,
            'selected_bulan' => $bulan,
            'selected_tahun' => $tahun,
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

    // public function verifikasi_transaksi_masuk($id)
    // {
    //     $Transaksi = Transaksi::where('id', $id)->where('jenis_transaksi', 'masuk')->first();
    //     $year = Carbon::parse($Transaksi->tgl_transaksi)->format('Y');

    //     if ($Transaksi) {
    //         $saldoAwal = SaldoAwal::where('department_id', $Transaksi->department_id)
    //             ->where('tahun', $year)
    //             ->first();

    //         if ($saldoAwal) {
    //             // cek saldo
    //             $TotalHargaDataTransaksi = Transaksi::where('department_id', $Transaksi->department_id)
    //                 ->where('jenis_transaksi', 'masuk')
    //                 ->where('status', 'selesai')
    //                 ->sum('total_harga') ?? 0;

    //             if (($Transaksi->total_harga + $TotalHargaDataTransaksi) > $saldoAwal->saldo_awal) {
    //                 $Transaksi->update(
    //                     [
    //                         'status' => 'tolak',
    //                     ]
    //                 );
    //                 return redirect()->back()->with('error', 'Terjadi kesalahan: Saldo tidak mencukupi.');
    //             }

    //             // cek ketersediaan stok
    //             $TotalQtyTransaksi = Transaksi::where('department_id', $Transaksi->department_id)
    //                 ->where('jenis_transaksi', 'masuk')
    //                 ->where('status', 'selesai')
    //                 ->sum('qty') ?? 0;

    //             $DataQtyMasterBarang = MasterBarang::where('kode_barang', $Transaksi->kode_barang)
    //                 ->sum('qty_sisa') ?? 0;

    //             if (($Transaksi->qty + $TotalQtyTransaksi) > $DataQtyMasterBarang) {
    //                 $Transaksi->update(
    //                     [
    //                         'status' => 'tolak',
    //                     ]
    //                 );
    //                 return redirect()->back()->with('error', 'Terjadi kesalahan: Stok barang ' . ucwords($Transaksi->barang->nama) . ' tidak mencukupi.');
    //             }

    //             // Update transaksi
    //             $Transaksi->update(
    //                 [
    //                     'status' => 'selesai',
    //                     'verifikator_id' => Auth::user()->id
    //                 ]
    //             );

    //             // Update master_barangs: kurangi qty_Sisa
    //             DB::table('master_barangs')
    //                 ->where('kode_barang', $Transaksi->kode_barang)
    //                 ->decrement('qty_sisa', $Transaksi->qty);

    //             // Update saldo_awals: tambah saldo_digunakan
    //             $saldoAwal->saldo_digunakan += $Transaksi->total_harga;
    //             $saldoAwal->save();

    //             // cek barang Stok_persediaan_bidang
    //             $ExistingStokPersediaan = StokPersediaanBidang::where('kode_barang', $Transaksi->kode_barang)
    //                 ->where('department_id', $Transaksi->department_id)
    //                 ->exists();

    //             if (!$ExistingStokPersediaan) {
    //                 StokPersediaanBidang::create([
    //                     'kode_barang' => $Transaksi->kode_barang,
    //                     'department_id' => $Transaksi->department_id,
    //                     'qty' => $Transaksi->qty
    //                 ]);
    //             } else {
    //                 StokPersediaanBidang::where('kode_barang', $Transaksi->kode_barang)
    //                     ->where('department_id', $Transaksi->department_id)
    //                     ->increment('qty', $Transaksi->qty);
    //             }
    //         }

    //         return redirect()->back()->with('success', 'Transaksi penerimaan berhasil diverifikasi.');
    //     } else {
    //         return redirect()->back()->with('error', 'Transaksi penerimaan tidak ditemukan.');
    //     }
    // }

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

    private function postPenerimaanUpdate($data)
    {
        $url = "https://sififo.kuduskab.go.id/fifonew/api/editpenerimaan.php";
        $token = "7b89a011ce9d3bb448e2d726e12a2b35425aa6edeaf49b414b33eac7cf4f1ee9";

        try {
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer " . $token,
                "Content-Type: application/json",
                "Accept: application/json",
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            // 🚨 Abaikan SSL (jika server belum ada sertifikat valid)
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new \Exception(curl_error($ch));
            }

            curl_close($ch);

            return json_decode($response, true);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal memanggil API: ' . $e->getMessage(),
            ];
        }
    }


    public function update_transaksi_masuk(Request $request, $id)
    {
        $data = $request->except('_token', 'bookingDay', 'bookingMonth', 'bookingYear');
        $databarang = DataBarang::where('id', $data['kode_barang'])->first();


        $day = $request->bookingDay;
        $month = $request->bookingMonth;
        $year = $request->bookingYear;

        $data['tanggal_pembukuan'] = $year . '-' . $month . '-' . $day;
        $data['kode_barang']    =    $databarang->kode_barang;
        $data['nama_barang']    =    $databarang->nama_barang;
        $data['bidang_id']    =    Auth::user()->bidang_id;

        $Transaksi = Penerimaan::where('id', $id)->first();

        if ($Transaksi) {
            $Transaksi->update($data);


            // --- Siapkan data untuk dikirim ke API eksternal ---
            // --- LOGIKA TAMBAHAN UNTUK API EKSTERNAL ---
            if ($data['status_penerimaan'] === 'Pembelian') {
                $status = 1;
            } else if ($data['status_penerimaan'] === 'Hibah') {
                $status = 2;
            } else {
                $status = 3;
            }

            if ($data['sumber_dana'] === 'DAU/APBD') {
                $sumberdanan = 1;
            } else if ($data['sumber_dana'] === 'BLUD') {
                $sumberdanan = 2;
            } else if ($data['sumber_dana'] === 'BOK') {
                $sumberdanan = 3;
            } else if ($data['sumber_dana'] === 'BOS') {
                $sumberdanan = 7;
            } else if ($data['sumber_dana'] === 'Droping') {
                $sumberdanan = 4;
            } else if ($data['sumber_dana'] === 'Hibah') {
                $sumberdanan = 5;
            } else {
                $sumberdanan = 6;
            }

            $apiData = [
                "id_trx_terima_sififo" => $Transaksi->id_trx_terima_sififo, // harus sesuai API
                "no_nota"      => $data['no_nota'],
                "tgl_buku"     => $data['tanggal_pembukuan'],
                "bulan"        => $month,
                "status"       => $status,
                "supplier"     => $data['supplier'],
                "dok_faktur"   => $data['no_faktur'],
                "id_barang"    => $databarang->id,
                "qty"          => $data['qty'],
                "harga_satuan" => $data['harga_satuan'],
                "nilai"         => $data['qty'] * $data['harga_satuan'],
                "bukti_terima" => $data['no_terima'],
                "tgl_terima"   => $data['tanggal_pembukuan'],
                "ket"          => $data['keterangan'],
                "sumberdana"   => $sumberdanan,
            ];

            // --- Kirim ke API eksternal ---
            $apiResponse = $this->postPenerimaanUpdate($apiData);

            if ($apiResponse && isset($apiResponse['success']) && $apiResponse['success']) {
                return redirect('/penerimaan')->with('success', 'Transaksi berhasil diperbarui & sinkron dengan API.');
            } else {
                return redirect('/penerimaan')->with('error', 'Data lokal terupdate, tapi gagal update ke API: ' . ($apiResponse['message'] ?? 'Tidak ada response'));
            }

            // return redirect('/penerimaan')->with('success', 'Transaksi penerimaan berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Transaksi penerimaan tidak ditemukan.');
        }
    }

    private function postPenerimaanDelete($id_trx_terima_sififo)
    {
        $url = "https://sififo.kuduskab.go.id/fifonew/api/deletepenerimaan.php"; // ganti sesuai endpoint asli

        $token = "7b89a011ce9d3bb448e2d726e12a2b35425aa6edeaf49b414b33eac7cf4f1ee9";

        $payload = json_encode([
            "id_trx_terima_sififo" => $id_trx_terima_sififo
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $token,
            "Content-Length: " . strlen($payload)
        ]);

        // 🚨 Abaikan SSL (jika server belum ada sertifikat valid)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return [
                "success" => false,
                "message" => "cURL Error: " . curl_error($ch)
            ];
        }

        curl_close($ch);

        return json_decode($response, true);
    }

    public function hapus_transaksi_masuk($id)
    {
        $Transaksi = Penerimaan::where('id', $id)->first();

        if (!$Transaksi) {
            return redirect()->back()->with('error', 'Transaksi penerimaan tidak ditemukan.');
        }

        // kirim request ke API eksternal
        $apiResponse = $this->postPenerimaanDelete($Transaksi->id_trx_terima_sififo);

        if ($apiResponse['success']) {
            // hapus juga di database lokal
            $Transaksi->delete();
            return redirect()->back()->with('success', 'Transaksi penerimaan berhasil dihapus.');
        } else {
            $msg = $apiResponse['message'] ?? 'Gagal menghapus data di API eksternal.';
            return redirect()->back()->with('error', $msg);
        }
    }
    // ### END TRANSAKSI MASUK ###


    // ### TRANSAKSI KELUAR ###
    public function tampil_transaksi_keluar(Request $request)
    {
        $req_month = $request->input('bulan') ?? date('m');
        $req_year = $request->input('tahun') ?? date('Y');

        // tampil data barang jenis transaksi = keluar
        if (Auth::user()->jabatan_id == 3) { // user
            $data = Pengeluaran::whereMonth('tanggal_pembukuan', $req_month)
                ->whereYear('tanggal_pembukuan', $req_year)
                ->where('bidang_id', Auth::user()->bidang_id)
                ->with('bidang')
                ->orderBy('tanggal_pembukuan', 'desc')
                ->get();
        } else { // admin
            $data = Pengeluaran::whereMonth('tanggal_pembukuan', $req_month)
                ->whereYear('tanggal_pembukuan', $req_year)
                ->with('bidang')
                ->orderBy('tanggal_pembukuan', 'desc')
                ->get();
        }

        // ambil data barang
        $Data_Barang = DataBarang::orderBy('nama_barang', 'asc')->get();


        // // ambil data barang berdasarkan transaksi masuk
        // if (Auth::user()->jabatan_id == 3) { //user
        //     $Data_Barang_By_Transaksi_Masuk = DB::table('transaksis')
        //         ->select(
        //             'kode_barang',
        //             'nama_barang as nama',
        //             DB::raw('SUM(qty) as qty_digunakan')
        //         )
        //         ->where('department_id', Auth::user()->department_id)
        //         ->where('status', 'selesai')
        //         ->where('jenis_transaksi', 'masuk')
        //         ->groupBy('kode_barang', 'nama_barang')
        //         ->get();
        // } else { // admin
        //     $Data_Barang_By_Transaksi_Masuk = DB::table('transaksis')
        //         ->select(
        //             'kode_barang',
        //             'nama_barang as nama',
        //             DB::raw('SUM(qty) as qty_digunakan')
        //         )
        //         ->where('status', 'selesai')
        //         ->where('jenis_transaksi', 'masuk')
        //         ->groupBy('kode_barang', 'nama_barang')
        //         ->get();
        // }



        // ambil nilai budget awal tahun ini
        // $Budget_Awal = SaldoAwal::where('department_id', Auth::user()->department_id)->where('tahun', now()->year)->first();

        return view('Admin.Transaksi.tampil_transaksi_keluar', [
            'data'          =>  $data,
            'data_barang'   =>  $Data_Barang,
            'req_month'     =>  $req_month,
            'req_year'      =>  $req_year,
        ]);
    }



    // public function create_transaksi_keluar(Request $request)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $tgl_transaksi = now()->format('Y-m-d');
    //         $department_id = Auth::user()->department_id;

    //         $mergedItems = [];

    //         foreach ($request->kode as $index => $kode) {
    //             if (!$kode) continue; // skip jika kosong

    //             $qty    = (int) $request->qty[$index];
    //             $harga  = (int) $request->harga[$index];
    //             $total  = (int) $request->total_harga[$index];

    //             // proses merge
    //             if (isset($mergedItems[$kode])) {
    //                 $mergedItems[$kode]['qty'] += $qty;
    //                 $mergedItems[$kode]['total_harga'] += $total;
    //             } else {
    //                 $mergedItems[$kode] = [
    //                     'kode_barang' => $kode,
    //                     'qty' => $qty,
    //                     'harga' => $harga,
    //                     'total_harga' => $total,
    //                 ];
    //             }
    //         }

    //         foreach ($mergedItems as $item) {
    //             $kode_barang = $item['kode_barang'];
    //             $qty = $item['qty'];
    //             $harga = $item['harga'];
    //             $total_harga = $item['total_harga'];

    //             $barang = DB::table('master_barangs')->where('kode_barang', $kode_barang)->first();
    //             if (!$barang) continue;

    //             // cek barang di stok_persediaan berdasarkan bidangnya
    //             $stok_persediaan_bidang = StokPersediaanBidang::where('kode_barang', $kode_barang)
    //                 ->where('department_id', $department_id)
    //                 ->first();

    //             // jika barang di stok_persediaan berdasarkan bidangnya ada, maka stok kurangi
    //             if ($stok_persediaan_bidang) {
    //                 $stok_persediaan_bidang->qty -= $qty;
    //                 $stok_persediaan_bidang->save();
    //             } else {
    //                 StokPersediaanBidang::create([
    //                     'kode_barang' => $kode_barang,
    //                     'department_id' => $department_id,
    //                     'qty' => $qty,
    //                 ]);
    //             }

    //             // cek saldo awal mencukupi tidak
    //             $saldo_awal = SaldoAwal::where('department_id', $department_id)->where('tahun', now()->year)->first();
    //             if ($total > $saldo_awal->saldo_digunakan) {
    //                 DB::rollback();
    //                 return redirect()->back()->with('error', 'Terjadi kesalahan: Saldo tidak mencukupi.');
    //             }

    //             // Cek jika sudah ada transaksi dengan kode & tgl & departemen & status & jenis_transaksi yg sama
    //             $existing = Transaksi::where('department_id', $department_id)
    //                 // ->where('tgl_transaksi', $tgl_transaksi)
    //                 ->where('kode_barang', $kode_barang)
    //                 ->where('status', 'pending')
    //                 ->where('jenis_transaksi', 'keluar')
    //                 ->first();

    //             if ($existing) {
    //                 // cek saldo di database
    //                 $total_saldo_exist = $total_harga + $existing->total_harga ?? 0;
    //                 if ($total_saldo_exist > ($saldo_awal->saldo_digunakan ?? 0)) {
    //                     DB::rollback();
    //                     return redirect()->back()->with('error', 'Terjadi kesalahan: Saldo tidak mencukupi.');
    //                 }

    //                 // cek ketersediaan stok di database
    //                 $total_qty_exis = $qty + $existing->qty ?? 0;
    //                 if ($total_qty_exis > $barang->sisa_qty) {
    //                     DB::rollback();
    //                     return redirect()->back()->with('error', 'Terjadi kesalahan: Stok barang ' . ucwords($barang->nama) . ' tidak mencukupi.');
    //                 }

    //                 // Update qty dan saldo_digunakan
    //                 $existing->qty += $qty;
    //                 $existing->total_harga += $total_harga;
    //                 $existing->save();
    //             } else {

    //                 Transaksi::create([
    //                     'tgl_transaksi'     => $tgl_transaksi,
    //                     'department_id'     => $department_id,
    //                     'kode_barang'       => $kode_barang,
    //                     'nama_barang'       => $barang->nama,
    //                     'nama_satuan'       => $barang->satuan,
    //                     'qty'               => $qty,
    //                     'harga_satuan'      => $harga,
    //                     'total_harga'       => $total_harga,
    //                     'status'            => 'selesai',
    //                     'jenis_transaksi'   => 'keluar',
    //                     'pembuat_id'        => Auth::user()->id,
    //                     'verifikator_id'    => Auth::user()->id,
    //                     'keterangan'        => "Transaksi User",
    //                 ]);
    //             }
    //         }

    //         DB::commit();

    //         return redirect()->back()->with('success', 'Transaksi pengeluaran berhasil disimpan.');
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    //     }
    // }

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
