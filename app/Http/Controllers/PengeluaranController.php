<?php

namespace App\Http\Controllers;

use App\Models\StokPersediaanBidang;
use App\Models\DataBarang;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengeluaranController extends Controller
{
    private function postTransaksiKeluar($data)
    {
        $url = "https://sififo.kuduskab.go.id/fifonew/api/createkeluar.php";
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

            // 🚨 bypass SSL kalau error sertifikat
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
        $tanggal    = $request->tanggal;
        $bulan      = $request->bulan;
        $bulan      = str_pad($bulan, 2, '0', STR_PAD_LEFT);
        $tahun      = $request->tahun;

        $tanggal_pembukuan = $tahun . '-' . $bulan . '-' . $tanggal;

        $request->validate([
            'tanggal' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
            'qty' => 'required|numeric|min:0.01',
            'kode_barang' => 'required', // Ini adalah ID dari form
            'status_pengeluaran' => 'required',
        ]);

        $bidangId = Auth::user()->bidang_id;
        $dataBarang = DataBarang::where('id', $request->kode_barang)->firstOrFail();
        $qtyKeluar = (float) str_replace(',', '.', $request->qty);

        DB::beginTransaction();
        try {
            // 1. Cari stok untuk barang spesifik di bidang ini
            $stokBidang = StokPersediaanBidang::where('kode_barang', $dataBarang->kode_barang)
                ->where('bidang_id', $bidangId)
                ->lockForUpdate() // Mencegah race condition
                ->first();

            // 2.  Cek apakah stok ada
            if (!$stokBidang) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Gagal update: Stok untuk barang ini tidak ditemukan.');
            }

            // 3. Cek apakah stok ada dan mencukupi
            if ($stokBidang->qty_sisa < $qtyKeluar) {
                DB::rollBack();
                $stokTersedia = $stokBidang ? $stokBidang->qty_sisa : 0;
                return redirect()->back()->with('error', 'Pengeluaran gagal: Stok tidak mencukupi. Stok tersedia: ' . $stokTersedia);
            }

            // 4. Buat catatan pengeluaran
            $pengeluaran = Pengeluaran::create([
                'tanggal_pembukuan' => $tanggal_pembukuan,
                'keterangan' => $request->keterangan,
                'status_pengeluaran' => $request->status_pengeluaran,
                'kode_barang' => $dataBarang->kode_barang,
                'nama_barang' => $dataBarang->nama_barang,
                'bidang_id' => $bidangId,
                'qty' => $qtyKeluar,
            ]);

            // 5. Kurangi stok
            $stokBidang->decrement('qty_sisa', $qtyKeluar);

            // 6. Kirim juga ke API pusat
            // --- LOGIKA TAMBAHAN UNTUK API EKSTERNAL ---
            if ($request->status_pengeluaran === 'pemakaian') {
                $status = 1;
            } else if ($request->status_pengeluaran === 'penghapusan') {
                $status = 2;
            } else {
                $status = 3;
            }
            $apiData = [
                "tgl_buku"  => $tanggal_pembukuan,
                "bulan"     => $bulan,
                "status"    => $status,
                "id_barang" => $dataBarang->id,
                "qty"       => (string)$qtyKeluar,
                "ket"       => $request->keterangan,
            ];

            $apiResponse = $this->postTransaksiKeluar($apiData);

            // 4. Jika sukses, update id_trx_keluar_sififo di tabel lokal
            if ($apiResponse) {
                $pengeluaran->update([
                    'id_trx_keluar_sififo' => $apiResponse['data']['id_trx_keluar_sififo']
                ]);
            }

            // Siapkan parameter untuk redirect kembali ke halaman pengeluaran dengan filter yang sama
            $queryParams = [
                'req_month' => $request->bulan,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ];

            DB::commit();
            return redirect()->route('tampil_transaksi_keluar', $queryParams)->with('success', 'Pengeluaran berhasil disimpan dan stok telah diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Pengeluaran gagal disimpan: ' . $e->getMessage());
        }
    }

    private function postTransaksiKeluarUpdate($data)
    {
        $url = "https://sififo.kuduskab.go.id/fifonew/api/editkeluar.php";
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

            // 🚨 bypass SSL kalau error sertifikat
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'qty' => 'required|numeric|min:0.01',
            'status_pengeluaran' => 'required',
        ]);

        $tanggal_pembukuan = sprintf('%04d-%02d-%02d', $request->tahun, $request->bulan, $request->tanggal);
        $newQty = (float) str_replace(',', '.', $request->qty);
        $bidangId = Auth::user()->bidang_id;

        DB::beginTransaction();

        try {
            $pengeluaran = Pengeluaran::findOrFail($id);
            $oldQty = (float) $pengeluaran->qty;

            $dataBarang = DataBarang::findOrFail($request->kode_barang);
            $stokBidang = StokPersediaanBidang::where('kode_barang', $dataBarang->kode_barang)
                ->where('bidang_id', $bidangId)
                ->lockForUpdate()
                ->first();

            if (!$stokBidang) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Gagal update: Stok untuk barang ini tidak ditemukan.');
            }

            $stok_sebenarnya = $stokBidang->qty_sisa + $oldQty;

            if ($stok_sebenarnya < $newQty) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Pengeluaran gagal: Stok tidak mencukupi. Stok tersedia: ' . $stokBidang->qty_sisa);
            }

            $qtyDifference = $newQty - $oldQty;

            if ($qtyDifference > 0 && $qtyDifference > $stokBidang->qty_sisa) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Gagal update: Stok tidak mencukupi untuk menambah jumlah pengeluaran. Stok tersedia: ' . $stokBidang->qty_sisa);
            }

            $pengeluaran->update([
                'tanggal_pembukuan' => $tanggal_pembukuan,
                'keterangan' => $request->keterangan,
                'status_pengeluaran' => $request->status_pengeluaran,
                'qty' => $newQty,
            ]);

            $stokBidang->decrement('qty_sisa', $qtyDifference);

            // Siapkan payload untuk API edit
            // --- LOGIKA TAMBAHAN UNTUK API EKSTERNAL ---
            if ($request->status_pengeluaran === 'pemakaian') {
                $status = 1;
            } else if ($request->status_pengeluaran === 'penghapusan') {
                $status = 2;
            } else {
                $status = 3;
            }
            $apiData = [
                "id_trx_keluar_sififo" => $pengeluaran->id_trx_keluar_sififo,
                "tgl_buku"  => $tanggal_pembukuan,
                "bulan"     => str_pad($request->bulan, 2, '0', STR_PAD_LEFT),
                "status"    => $status,
                "id_barang" => $dataBarang->id,
                "qty"       => (string)$newQty,
                "ket"       => $request->keterangan ?? "-",
            ];

            $apiResponse = $this->postTransaksiKeluarUpdate($apiData);

            if ($apiResponse) {
                DB::commit();
                return redirect()->back()->with('success', 'Pengeluaran berhasil diupdate dan stok telah disesuaikan.');
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Gagal sinkron ke API pusat: ' . ($apiResponse['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    private function postPenerimaanDelete($id_trx_keluar_sififo)
    {
        $url = "https://sififo.kuduskab.go.id/fifonew/api/deletekeluar.php"; // ganti sesuai endpoint asli

        $token = "7b89a011ce9d3bb448e2d726e12a2b35425aa6edeaf49b414b33eac7cf4f1ee9";

        $payload = json_encode([
            "id_trx_keluar_sififo" => $id_trx_keluar_sififo
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

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $pengeluaran = Pengeluaran::findOrFail($id);
            $qtyToReturn = (float) $pengeluaran->qty;

            $stokBidang = StokPersediaanBidang::where('kode_barang', $pengeluaran->kode_barang)
                ->where('bidang_id', $pengeluaran->bidang_id)
                ->lockForUpdate()
                ->first();

            if ($stokBidang) {
                $stokBidang->increment('qty_sisa', $qtyToReturn);
            }


            // kirim request ke API eksternal
            $apiResponse = $this->postPenerimaanDelete($pengeluaran->id_trx_keluar_sififo);

            if ($apiResponse) {
                $pengeluaran->delete();

                DB::commit();
                return redirect()->back()->with('success', 'Pengeluaran berhasil dihapus dan stok telah dikembalikan.');
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Gagal menghapus pengeluaran: ' . ($apiResponse['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Pengeluaran gagal dihapus: ' . $e->getMessage());
        }
    }
}
