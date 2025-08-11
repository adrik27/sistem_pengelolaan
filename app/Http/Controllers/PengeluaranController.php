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
            Pengeluaran::create([
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

            DB::commit();
            return redirect()->back()->with('success', 'Pengeluaran berhasil disimpan dan stok telah diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Pengeluaran gagal disimpan: ' . $e->getMessage());
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
            DB::commit();

            return redirect()->back()->with('success', 'Pengeluaran berhasil diupdate dan stok telah disesuaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }


    // public function update(Request $request, $id)
    // {
    //     $tanggal    = $request->tanggal;
    //     $bulan      = $request->bulan;
    //     $bulan      = str_pad($bulan, 2, '0', STR_PAD_LEFT);
    //     $tahun      = $request->tahun;

    //     $tanggal_pembukuan = $tahun . '-' . $bulan . '-' . $tanggal;

    //     $request->validate([
    //         'qty' => 'required|numeric|min:0.01',
    //         'status_pengeluaran' => 'required',
    //     ]);

    //     $newQty = (float) str_replace(',', '.', $request->qty);
    //     $bidangId = Auth::user()->bidang_id;

    //     DB::beginTransaction();
    //     try {
    //         // 1. Ambil data pengeluaran asli
    //         $pengeluaran = Pengeluaran::findOrFail($id);
    //         $oldQty = (float) $pengeluaran->qty;

    //         // 2. Cari data stok dan barang
    //         $dataBarang = DataBarang::where('id', $request->kode_barang)->firstOrFail();
    //         $stokBidang = StokPersediaanBidang::where('kode_barang', $dataBarang->kode_barang)
    //             ->where('bidang_id', $bidangId)
    //             ->lockForUpdate()
    //             ->first();

    //         // 3. Cek apakah stok ada ?
    //         if (!$stokBidang) {
    //             DB::rollBack();
    //             return redirect()->back()->with('error', 'Gagal update: Stok untuk barang ini tidak ditemukan.');
    //         }

    //         // 4. Cek apakah stok mencukupi ?
    //         $stok_sebenarnya = $stokBidang->qty_sisa + $oldQty;

    //         if ($stok_sebenarnya < $newQty) {
    //             DB::rollBack();
    //             $stokTersedia = $stokBidang ? $stokBidang->qty_sisa : 0;
    //             return redirect()->back()->with('error', 'Pengeluaran gagal: Stok tidak mencukupi. Stok tersedia: ' . $stokTersedia);
    //         }

    //         // 5. Hitung selisih kuantitas
    //         $qtyDifference = $newQty - $oldQty;

    //         // 4. Cek apakah stok mencukupi untuk perubahan
    //         if ($qtyDifference > 0 && $qtyDifference > $stokBidang->qty_sisa) {
    //             DB::rollBack();
    //             return redirect()->back()->with('error', 'Gagal update: Stok tidak mencukupi untuk menambah jumlah pengeluaran. Stok tersedia: ' . $stokBidang->qty_sisa);
    //         }

    //         // 5. Update data pengeluaran
    //         $pengeluaran->update([
    //             'tanggal_pembukuan' => $tanggal_pembukuan,
    //             'keterangan' => $request->keterangan,
    //             'status_pengeluaran' => $request->status_pengeluaran,
    //             'qty' => $newQty,
    //         ]);

    //         // 6. Sesuaikan stok berdasarkan selisih
    //         $stokBidang->decrement('qty_sisa', $qtyDifference);
    //         DB::commit();
    //         return redirect()->back()->with('success', 'Pengeluaran berhasil diupdate dan stok telah disesuaikan.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
    //     }
    // }

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

            $pengeluaran->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Pengeluaran berhasil dihapus dan stok telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Pengeluaran gagal dihapus: ' . $e->getMessage());
        }
    }
}
