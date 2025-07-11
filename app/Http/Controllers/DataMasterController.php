<?php

namespace App\Http\Controllers;

use App\Exports\DataMasterExport;
use App\Models\Kategori;
use App\Models\MasterBarang;
use App\Models\StokPersediaanBidang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DataMasterController extends Controller
{
    public function export()
    {
        $datas = MasterBarang::all();

        // jika pdf
        // return Excel::download(new DataMasterExport($datas), 'data_master.pdf', \Maatwebsite\Excel\Excel::DOMPDF);

        // // jika excel
        // return Excel::download(new DataMasterExport($datas), 'data_master.xlsx');
    }
    public function tampil_data_master()
    {
        $datas = MasterBarang::query()->with('kategori')->get();
        $kategoris = Kategori::all();
        return view('Admin.DataMaster.tampil_data_master', [
            'datas' => $datas,
            'kategoris' => $kategoris,
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
        $duplikatDB = MasterBarang::whereIn('kode_barang', $kodeArray)->pluck('nama')->toArray();
        if (count($duplikatDB) > 0) {
            return redirect()->back()->with('error', 'Terdapat duplikasi data di database, dengan nama barang: ' . implode(', ', $duplikatDB));
        }

        DB::beginTransaction();
        try {
            foreach ($kodeArray as $i => $kodeBarang) {
                $nama     = $request->nama[$i];
                $kategori = $request->kategori[$i];
                $satuan   = $request->satuan[$i];
                $qty      = (int) $request->qty[$i];
                $harga    = (int) str_replace('.', '', $request->harga[$i]);
                $jumlah   = $qty * $harga;

                MasterBarang::create([
                    'kode_barang' => $kodeBarang,
                    'nama'        => $nama,
                    'kategori_id' => $kategori,
                    'satuan'      => $satuan,
                    'qty_sisa'    => $qty,
                    'harga'       => $harga,
                    'jumlah'      => $jumlah,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function create_stok_data_master(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'kode'      => 'required|array',
            'kode.*'    => 'required|string|exists:master_barangs,kode_barang', // Pastikan semua kode ada di DB
            'qty'       => 'required|array',
            'qty.*'     => 'required|integer|min:1', // Pastikan semua qty valid
        ], [
            'kode.*.exists' => 'Kode barang :input tidak ditemukan di database.',
        ]);

        $kodeArray = $request->kode;

        // 2. Cek Duplikasi dalam Input Form
        if (count($kodeArray) !== count(array_unique($kodeArray))) {
            return redirect()->back()->with('error', 'Terdapat duplikasi kode barang dalam input Anda.')->withInput();
        }

        // Memulai transaksi database untuk memastikan integritas data
        DB::beginTransaction();
        try {
            // 3. Ambil data semua barang yang relevan dalam satu query untuk efisiensi
            $masterBarangItems = MasterBarang::whereIn('kode_barang', $kodeArray)->get()->keyBy('kode_barang');

            foreach ($kodeArray as $index => $kodeBarang) {
                $item = $masterBarangItems[$kodeBarang];
                $qtyTambah = (int) $request->qty[$index];

                // 4. Update Stok di Tabel MasterBarang
                $item->qty_sisa += $qtyTambah;
                $item->jumlah = $item->qty_sisa * $item->harga; // Hitung ulang total jumlah harga
                $item->save();

                // 5. Catat Riwayat di Tabel Transaksi
                Transaksi::create([
                    'tgl_transaksi'   => now(),
                    'department_id' => Auth::user()->department_id,
                    'kode_barang'     => $item->kode_barang,
                    'nama_barang'     => $item->nama,
                    'nama_satuan'     => $item->satuan,
                    'qty'             => $qtyTambah,
                    'harga_satuan'    => $item->harga,
                    'total_harga'     => $qtyTambah * $item->harga,
                    'status'          => 'selesai', // Atau 'Menunggu Verifikasi', dll.
                    'jenis_transaksi' => 'masuk', // Menandakan ini adalah penambahan stok
                    'pembuat_id'      => Auth::id(), // ID user yang sedang login
                    // 'verifikator_id'  => null, // Bisa diisi nanti saat proses verifikasi
                    'keterangan'      => 'Penambahan stok dari data master.',
                ]);
            }

            // Jika semua proses berhasil, commit transaksi
            DB::commit();

            return redirect()->back()->with('success', 'Stok berhasil ditambah dan riwayat transaksi telah dicatat.');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, batalkan semua perubahan
            DB::rollBack();

            // Beri pesan error yang informatif
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function update_data_master(Request $request, $id)
    {
        $validated = $request->validate([
            'nama'     => 'required|string',
            'kategori' => 'required',
            'satuan'   => 'required|string',
            'harga'    => 'required|numeric',
            'qty_sisa' => 'numeric',
        ]);

        DB::beginTransaction();
        try {
            // Ambil data master berdasarkan id
            $dataMaster = MasterBarang::findOrFail($id);

            // cek qty lebih kecil dari sebelumnya atau tidak
            if ($request->qty_sisa < $dataMaster->qty_sisa) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Gagal memperbarui data: Qty tidak boleh kurang dari ' . $dataMaster->qty_sisa . ' !');
            }

            // Jika qty_sisa tidak diisi, gunakan qty_sisa sebelumnya
            if ($request->qty_sisa != null) {
                $qty = $request->qty_sisa;
            } else {
                $qty = $dataMaster;
            }


            // Update DataMaster
            $dataMaster->update([
                'nama'          => $request->nama,
                'kategori_id'   => $request->kategori,
                'satuan'        => $request->satuan,
                'harga'         => $request->harga,
                'qty_sisa'      => $qty,
                'jumlah'        => $qty * $request->harga,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function delete_data_master($id)
    {
        DB::beginTransaction();
        try {
            $dataMaster = MasterBarang::findOrFail($id);
            $dataMaster->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }





    public function getHarga($kode)
    {
        $barang = MasterBarang::where('kode_barang', $kode)->first();
        return response()->json([
            'harga' => $barang->harga,
            'sisa_qty' => $barang->qty_sisa
        ]);
    }
    public function getHargaKeluar($kode)
    {
        $barang = MasterBarang::where('kode_barang', $kode)->first();
        $stok_persediaan_bidang = StokPersediaanBidang::where('kode_barang', $kode)->where('department_id', Auth::user()->department_id)->first();
        return response()->json([
            'harga' => $barang->harga,
            'sisa_qty' => $stok_persediaan_bidang ? $stok_persediaan_bidang->qty : 0,
        ]);
    }
}
