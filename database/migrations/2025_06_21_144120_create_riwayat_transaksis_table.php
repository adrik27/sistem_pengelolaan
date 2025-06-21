<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riwayat_transaksis', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_transaksi');
            $table->foreignId('department_id');
            $table->foreignId('kode_barang');
            $table->string('nama_barang');
            $table->string('nama_satuan');
            $table->integer('qty');
            $table->integer('harga_satuan');
            $table->integer('total_harga');
            $table->integer('saldo_awal');
            $table->integer('sisa_saldo');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_transaksis');
    }
};
