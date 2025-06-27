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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_transaksi');
            $table->foreignId('department_id');
            $table->string('kode_barang', 50);
            $table->string('nama_barang');
            $table->string('nama_satuan');
            $table->integer('qty');
            $table->integer('harga_satuan');
            $table->integer('total_harga');
            $table->string('status')->default('pending');
            $table->string('jenis_transaksi')->default('pending');
            $table->foreignId('pembuat_id')->nullable();
            $table->foreignId('verifikator_id')->nullable();
            $table->timestamps();
            $table->string('keterangan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
