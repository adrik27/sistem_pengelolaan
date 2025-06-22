<?php

use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\SaldoAwalController;
use App\Http\Controllers\TransaksiMasukController;
use App\Models\DataMaster;
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
    Route::get('/dashboard-admin', [DashboardController::class, 'tampil_dashboard'])->name('tampil_dashboard');

    // ## Data Master
    Route::get('/data-master', [DataMasterController::class, 'tampil_data_master'])->name('tampil_data_master');
    Route::post('/data-master', [DataMasterController::class, 'create_data_master'])->name('create_data_master');
    Route::post('/data-master/tambah-stok', [DataMasterController::class, 'create_stok_data_master'])->name('create_stok_data_master');
    Route::post('/data-master/edit/{id}', [DataMasterController::class, 'update_data_master'])->name('update_data_master');
    Route::post('/data-master/hapus/{id}', [DataMasterController::class, 'delete_data_master'])->name('delete_data_master');

    // ## Saldo Awal
    Route::get('/saldo-awal', [SaldoAwalController::class, 'tampil_saldo_awal'])->name('tampil_saldo_awal');
    Route::post('/saldo-awal', [SaldoAwalController::class, 'create_saldo_awal'])->name('create_saldo_awal');
    Route::post('/saldo-awal/edit/{id}', [SaldoAwalController::class, 'update_saldo_awal'])->name('update_saldo_awal');
    Route::post('/saldo-awal/hapus/{id}', [SaldoAwalController::class, 'hapus_saldo_awal'])->name('hapus_saldo_awal');
    
    // ## Penerimaan (Transaksi Masuk)
    Route::get('/penerimaan', [TransaksiMasukController::class, 'tampil_transaksi_masuk'])->name('tampil_transaksi_masuk');
    Route::get('/get-harga-barang/{kode}', [DataMasterController::class, 'getHarga']);
    Route::post('/penerimaan', [TransaksiMasukController::class, 'tampil_transaksi_masuk'])->name('tampil_transaksi_masuk');
    Route::post('/penerimaan/create', [TransaksiMasukController::class, 'create_transaksi_masuk'])->name('create_transaksi_masuk');

    // ## Logout
    Route::post('/logout', [AuthenticateController::class, 'proses_logout'])->name('proses_logout');
});
