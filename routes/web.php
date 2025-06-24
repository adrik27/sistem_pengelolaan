<?php

use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\SaldoAwalController;
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
    Route::get('/dashboard', [DashboardController::class, 'tampil_dashboard'])->name('tampil_dashboard');
    Route::get('/dashboard-admin', [DashboardController::class, 'tampil_dashboard'])->name('tampil_dashboard');

    // ## Data Master
    Route::get('/data-master', [DataMasterController::class, 'tampil_data_master'])->name('tampil_data_master');
    Route::post('/data-master', [DataMasterController::class, 'create_data_master'])->name('create_data_master');
    Route::post('/data-master/tambah-stok', [DataMasterController::class, 'create_stok_data_master'])->name('create_stok_data_master');
    Route::post('/data-master/edit/{id}', [DataMasterController::class, 'update_data_master'])->name('update_data_master');
    Route::post('/data-master/hapus/{id}', [DataMasterController::class, 'delete_data_master'])->name('delete_data_master');
    Route::get('/export-excel', [DataMasterController::class, 'export'])->name('export');

    // ## Saldo Awal
    Route::get('/saldo-awal', [SaldoAwalController::class, 'tampil_saldo_awal'])->name('tampil_saldo_awal');
    Route::post('/saldo-awal', [SaldoAwalController::class, 'create_saldo_awal'])->name('create_saldo_awal');
    Route::post('/saldo-awal/edit/{id}', [SaldoAwalController::class, 'update_saldo_awal'])->name('update_saldo_awal');
    Route::post('/saldo-awal/hapus/{id}', [SaldoAwalController::class, 'hapus_saldo_awal'])->name('hapus_saldo_awal');
    

    // ## Penerimaan (Transaksi Masuk)
    Route::get('/get-harga-barang/{kode}', [DataMasterController::class, 'getHarga']);
    Route::get('/get-harga-barang-keluar/{kode}', [DataMasterController::class, 'getHargaKeluar']);

    Route::get('/penerimaan', [TransaksiController::class, 'tampil_transaksi_masuk'])->name('tampil_transaksi_masuk');
    Route::post('/penerimaan', [TransaksiController::class, 'tampil_transaksi_masuk'])->name('tampil_transaksi_masuk');
    Route::post('/penerimaan/create', [TransaksiController::class, 'create_transaksi_masuk'])->name('create_transaksi_masuk');
    Route::post('/penerimaan/verifikasi/{id}', [TransaksiController::class, 'verifikasi_transaksi_masuk'])->name('verifikasi_transaksi_masuk');
    Route::post('/penerimaan/tolak/{id}', [TransaksiController::class, 'tolak_transaksi_masuk'])->name('tolak_transaksi_masuk');
    Route::post('/penerimaan/update/{id}', [TransaksiController::class, 'update_transaksi_masuk'])->name('update_transaksi_masuk');
    Route::post('/penerimaan/hapus/{id}', [TransaksiController::class, 'hapus_transaksi_masuk'])->name('hapus_transaksi_masuk');
    
    // ## Pengeluaran (Transaksi Keluar)
    Route::get('/pengeluaran', [TransaksiController::class, 'tampil_transaksi_keluar'])->name('tampil_transaksi_keluar');
    Route::post('/pengeluaran', [TransaksiController::class, 'tampil_transaksi_keluar'])->name('tampil_transaksi_keluar');
    Route::post('/pengeluaran/create', [TransaksiController::class, 'create_transaksi_keluar'])->name('create_transaksi_keluar');
    Route::post('/pengeluaran/verifikasi/{id}', [TransaksiController::class, 'verifikasi_transaksi_keluar'])->name('verifikasi_transaksi_keluar');
    Route::post('/pengeluaran/tolak/{id}', [TransaksiController::class, 'tolak_transaksi_keluar'])->name('tolak_transaksi_keluar');
    Route::post('/pengeluaran/update/{id}', [TransaksiController::class, 'update_transaksi_keluar'])->name('update_transaksi_keluar');
    Route::post('/pengeluaran/hapus/{id}', [TransaksiController::class, 'hapus_transaksi_keluar'])->name('hapus_transaksi_keluar');

    // ## Logout
    Route::post('/logout', [AuthenticateController::class, 'proses_logout'])->name('proses_logout');
});
