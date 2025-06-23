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
        Schema::create('data_masters', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang');
            $table->string('nama');
            $table->string('kategori');
            $table->string('satuan');
            $table->integer('harga');
            $table->integer('qty_awal')->default(0);
            $table->integer('qty_digunakan')->default(0);
            $table->foreignId('pembuat_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_masters');
    }
};
