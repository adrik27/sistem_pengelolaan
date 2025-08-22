<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use App\Models\Penerimaan;
use App\Models\StokPersediaanBidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon; // Import Carbon untuk manipulasi tanggal
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PenerimaanController extends Controller
{
    /**
     * Method untuk menyimpan data item penerimaan baru.
     */

    private function postPenerimaan($data)
    {
        $url = "https://sififo.kuduskab.go.id/fifonew/api/createpenerimaan.php";
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

            // 🚨 Tambahkan ini untuk masalah SSL
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



    public function store(Request $request)
    {
        // Aturan validasi disesuaikan dengan atribut 'name' dari HTML
        $validator = Validator::make($request->all(), [
            'bookingDay'      => 'required|numeric|min:1|max:31',
            'bookingMonth'    => 'required|string',
            'bookingYear'     => 'required|numeric|min:1900',
            'supplier'        => 'required|string|max:255',
            'invoiceNo'       => 'required|string|max:255',
            'receiptStatus'   => 'required',
            'noteNo'          => 'required|string|max:255',
            'receiveNo'       => 'required|string|max:255',
            'fundingSource'   => 'required',
            'itemName'        => 'required',
            'itemQty'         => 'required|numeric|min:0',
            'itemPrice'       => 'required|numeric|min:0',
            'itemDescription' => 'nullable|string',
            'kelompokBarang'  => 'required',
        ]);

        // ... (sisa kode validasi sama) ...
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // ... (kode untuk memproses tanggal tetap sama) ...
        $monthMap = [
            'Januari' => 1,
            'Februari' => 2,
            'Maret' => 3,
            'April' => 4,
            'Mei' => 5,
            'Juni' => 6,
            'Juli' => 7,
            'Agustus' => 8,
            'September' => 9,
            'Oktober' => 10,
            'November' => 11,
            'Desember' => 12
        ];
        $monthNumber = $monthMap[$request->bookingMonth];
        $tanggalPembukuan = Carbon::create($request->bookingYear, $monthNumber, $request->bookingDay)->format('Y-m-d');

        // ... (kode untuk memproses nama barang tetap sama) ...
        $itemFullName = $request->input('itemNameText') ?? '';
        $itemParts = explode(' - ', $itemFullName, 2);
        $kodeBarang = trim($itemParts[0] ?? '');
        $namaBarang = trim($itemParts[1] ?? '');

        // Simpan ke database dengan kunci yang sesuai
        $bidangId = Auth::user()->bidang_id;
        $qty = str_replace(',', '.', $request->itemQty);
        $harga = str_replace(',', '.', $request->itemPrice);

        DB::beginTransaction();
        try {
            $penerimaan = Penerimaan::create([
                'bidang_id'         => $bidangId,
                'tanggal_pembukuan' => $tanggalPembukuan,
                'supplier'          => $request->supplier,
                'no_faktur'         => $request->invoiceNo, // Gunakan $request->invoiceNo
                'status_penerimaan' => $request->receiptStatus,
                'no_nota'           => $request->noteNo,
                'no_terima'         => $request->receiveNo,
                'sumber_dana'       => $request->fundingSource,
                'kode_barang'       => $kodeBarang,
                'nama_barang'       => $namaBarang,
                'qty'               => $qty, // Ganti koma dengan titik
                'harga_satuan'      => $harga, // Ganti koma dengan titik
                'keterangan'        => $request->itemDescription,
            ]);

            // --- LOGIKA TAMBAHAN UNTUK STOK PERSEDIAAN BIDANG ---
            $stokBidang = StokPersediaanBidang::where('kode_barang', $kodeBarang)
                ->where('bidang_id', $bidangId)
                ->first();

            if ($stokBidang) {
                // Jika stok sudah ada, update jumlahnya
                $stokBidang->increment('qty_sisa', $qty);
                $stokBidang->harga_satuan = $harga; // Perbarui dengan harga terbaru
                $stokBidang->save();
            } else {
                // Jika stok belum ada, buat entri baru
                $dataBarang = DataBarang::where('kode_barang', $kodeBarang)->first();

                StokPersediaanBidang::create([
                    'kode_kelompok' => $request->kelompokBarang,
                    'kode_barang'   => $kodeBarang,
                    'bidang_id'     => $bidangId,
                    'nama_barang'   => $namaBarang,
                    'satuan'        => $dataBarang->satuan ?? 'N/A', // Ambil satuan dari data barang
                    'qty_sisa'      => $qty,
                    'harga_satuan'  => $harga,
                ]);
            }
            DB::commit();

            // --- LOGIKA TAMBAHAN UNTUK API EKSTERNAL ---
            if ($request->receiptStatus === 'Pembelian') {
                $status = 1;
            } else if ($request->receiptStatus === 'Hibah') {
                $status = 2;
            } else {
                $status = 3;
            }

            if ($request->fundingSource === 'DAU/APBD') {
                $sumberdanan = 1;
            } else if ($request->fundingSource === 'BLUD') {
                $sumberdanan = 2;
            } else if ($request->fundingSource === 'BOK') {
                $sumberdanan = 3;
            } else if ($request->fundingSource === 'BOS') {
                $sumberdanan = 7;
            } else if ($request->fundingSource === 'Droping') {
                $sumberdanan = 4;
            } else if ($request->fundingSource === 'Hibah') {
                $sumberdanan = 5;
            } else {
                $sumberdanan = 6;
            }

            $dataBarang = DataBarang::where('kode_barang', $kodeBarang)->first();

            // Panggil API eksternal createpenerimaan
            $apiPayload = [
                'no_nota'       => $request->noteNo,
                'tgl_buku'      => $tanggalPembukuan,
                'bulan'         => $monthNumber,
                'status'        => $status,
                'supplier'      => $request->supplier,
                'dok_faktur'    => $request->invoiceNo,
                'id_barang'     => $dataBarang->id,
                'qty'           => $qty,
                'harga_satuan'  => $harga,
                'bukti_terima'  => $request->receiveNo,
                'tgl_terima'    => $tanggalPembukuan, // bisa diganti kalau ada tanggal terima khusus
                'ket'           => $request->itemDescription,
                'sumberdana'    => $sumberdanan,
            ];

            $apiResponse = $this->postPenerimaan($apiPayload);

            // Kalau response valid dan ada id_trx_terima_sififo → update ke tabel penerimaan
            if ($apiResponse['success']) {
                $penerimaan->update([
                    'id_trx_terima_sififo' => $apiResponse['data']['id_trx_terima_sififo']
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil ditambahkan dan stok telah diperbarui!',
                'data'    => $penerimaan,
                // 'api'     => $apiResponse
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }
}
