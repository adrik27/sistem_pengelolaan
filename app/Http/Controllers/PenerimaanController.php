<?php

namespace App\Http\Controllers;

use App\Models\Penerimaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon; // Import Carbon untuk manipulasi tanggal
use Illuminate\Support\Facades\Auth;

class PenerimaanController extends Controller
{
    /**
     * Method untuk menyimpan data item penerimaan baru.
     */
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
        $itemFullName = $request->input('itemNameText');
        list($kodeBarang, $namaBarang) = explode(' - ', $itemFullName, 2);

        // Simpan ke database dengan kunci yang sesuai
        $bidangId = Auth::user()->bidang_id;
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
                'kode_barang'       => trim($kodeBarang),
                'nama_barang'       => trim($namaBarang),
                'qty'               => str_replace(',', '.', $request->itemQty), // Ganti koma dengan titik
                'harga_satuan'      => str_replace(',', '.', $request->itemPrice), // Ganti koma dengan titik
                'keterangan'        => $request->itemDescription,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil ditambahkan!',
                'data'    => $penerimaan
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }
}
