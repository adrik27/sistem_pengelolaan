<?php

use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LaporanPersediaanController;
use App\Http\Controllers\MasterBarangController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\SaldoAwalController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect('/login');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthenticateController::class, 'tampil_login'])->name('tampil_login');
    Route::post('/login', [AuthenticateController::class, 'proses_login'])->name('proses_login');
});

Route::group(['middleware' => 'auth'], function () {
    // ## Dashboard Admin
    Route::get('/get-data', [DashboardController::class, 'getDatas']);

    Route::get('/dashboard', [DashboardController::class, 'tampil_dashboard'])->name('tampil_dashboard');
    // Route::get('/dashboard-admin', [DashboardController::class, 'tampil_dashboard'])->name('tampil_dashboard'); 
    // ^ Tidak terpakai: Route ini duplikat dari '/dashboard'. Cukup gunakan satu route untuk dashboard.

    // ## Data Master
    Route::get('/data-master', [DataMasterController::class, 'tampil_data_master'])->name('tampil_data_master');
    Route::post('/data-master', [DataMasterController::class, 'create_data_master'])->name('create_data_master');
    Route::post('/data-master/tambah-stok', [DataMasterController::class, 'create_stok_data_master'])->name('create_stok_data_master');
    Route::post('/data-master/edit/{id}', [DataMasterController::class, 'update_data_master'])->name('update_data_master');
    Route::post('/data-master/hapus/{id}', [DataMasterController::class, 'delete_data_master'])->name('delete_data_master');
    // Route::get('/export-excel', [DataMasterController::class, 'export'])->name('export');
    // ^ Tidak terpakai: Method 'export' di dalam DataMasterController isinya sudah di-comment.

    // ## Penerimaan (Transaksi Masuk)
    Route::get('/get-harga-barang/{kode}', [DataMasterController::class, 'getHarga']);
    Route::get('/get-harga-barang-keluar/{kode}', [DataMasterController::class, 'getHargaKeluar']);

    Route::get('/penerimaan', [TransaksiController::class, 'penerimaan'])->name('tampil_transaksi_masuk');
    Route::post('/penerimaan', [TransaksiController::class, 'tampil_transaksi_masuk'])->name('tampil_transaksi_masuk');
    Route::get('/penerimaan/create', [TransaksiController::class, 'tambah_penerimaan'])->name('tambah_penerimaan');
    // Route::post('/penerimaan/create', [TransaksiController::class, 'create_transaksi_masuk'])->name('create_transaksi_masuk'); 
    // ^ Tidak terpakai: Sudah di-comment dari awal, fungsionalitasnya digantikan oleh 'penerimaan.store' yang menggunakan PenerimaanController.
    Route::post('/penerimaan/verifikasi/{id}', [TransaksiController::class, 'verifikasi_transaksi_masuk'])->name('verifikasi_transaksi_masuk');
    Route::post('/penerimaan/tolak/{id}', [TransaksiController::class, 'tolak_transaksi_masuk'])->name('tolak_transaksi_masuk');
    Route::post('/penerimaan/update/{id}', [TransaksiController::class, 'update_transaksi_masuk'])->name('update_transaksi_masuk');
    Route::post('/penerimaan/hapus/{id}', [TransaksiController::class, 'hapus_transaksi_masuk'])->name('hapus_transaksi_masuk');

    // ## Pengeluaran (Transaksi Keluar)
    Route::get('/pengeluaran', [TransaksiController::class, 'tampil_transaksi_keluar'])->name('tampil_transaksi_keluar');
    Route::post('/pengeluaran', [TransaksiController::class, 'tampil_transaksi_keluar'])->name('tampil_transaksi_keluar');
    // Route::post('/pengeluaran/create', [TransaksiController::class, 'create_transaksi_keluar'])->name('create_transaksi_keluar');
    // Route::post('/pengeluaran/verifikasi/{id}', [TransaksiController::class, 'verifikasi_transaksi_keluar'])->name('verifikasi_transaksi_keluar');
    // Route::post('/pengeluaran/tolak/{id}', [TransaksiController::class, 'tolak_transaksi_keluar'])->name('tolak_transaksi_keluar');
    // Route::post('/pengeluaran/update/{id}', [TransaksiController::class, 'update_transaksi_keluar'])->name('update_transaksi_keluar');
    // Route::post('/pengeluaran/hapus/{id}', [TransaksiController::class, 'hapus_transaksi_keluar'])->name('hapus_transaksi_keluar');

    // ## Laporan Persediaan
    Route::get('/laporan-persediaan', [LaporanPersediaanController::class, 'tampil_laporan_persediaan'])->name('tampil_laporan_persediaan');
    Route::post('/laporan-persediaan', [LaporanPersediaanController::class, 'tampil_laporan_persediaan'])->name('search_laporan_persediaan');


    // ## Stock Opname
    Route::get('/stock-opname', [StockOpnameController::class, 'tampil_stock_opname'])->name('tampil_stock_opname');
    Route::post('/stock-opname', [StockOpnameController::class, 'tampil_stock_opname'])->name('cari_stock_opname');
    Route::post('/stock-opname/ambil-data', [StockOpnameController::class, 'ambil_stock_opname'])->name('ambil_stock_opname');

    // ## Logout
    Route::post('/logout', [AuthenticateController::class, 'proses_logout'])->name('proses_logout');


    // riwayat transaksi
    Route::get('/riwayat-transaksi', [MasterBarangController::class, 'tampil_riwayat_transaksi'])->name('tampil_riwayat_transaksi');

    Route::get('/seluruh_data_barang', [MasterBarangController::class, 'search'])->name('seluruh_data_barang');

    Route::post('/penerimaan/store', [PenerimaanController::class, 'store'])->name('penerimaan.store');
    Route::get('/laporan/penerimaan/data', [LaporanController::class, 'getDataPenerimaan'])->name('laporan.penerimaan.data');

    Route::post('/pengeluaran/store', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
    Route::post('/pengeluaran/update/{id}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
    Route::post('/pengeluaran/delete/{id}', [PengeluaranController::class, 'delete'])->name('pengeluaran.delete');

    Route::get('/laporan/penerimaan/cetak', [LaporanController::class, 'cetakBeritaAcara'])->name('laporan.penerimaan.cetak');
});
