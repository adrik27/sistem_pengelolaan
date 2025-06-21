<?php

use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaldoAwalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthenticateController::class, 'tampil_login']);
    Route::post('/login', [AuthenticateController::class, 'proses_login']);
});

Route::group(['middleware' => 'admin'], function () {
    // ## Dashboard Admin
    Route::get('/dashboard-admin', [DashboardController::class, 'tampil_dashboard']);
    // ## Saldo Awal
    Route::get('/saldo-awal', [SaldoAwalController::class, 'tampil_saldo_awal']);
    Route::post('/saldo-awal', [SaldoAwalController::class, 'create_saldo_awal']);
    Route::post('/saldo-awal/edit/{id}', [SaldoAwalController::class, 'update_saldo_awal']);
    // ## Logout
    Route::post('/logout', [AuthenticateController::class, 'proses_logout']);
});
