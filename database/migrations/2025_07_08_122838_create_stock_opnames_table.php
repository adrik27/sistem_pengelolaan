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
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->integer('tanggal');
            $table->string('kode_barang');
            $table->string('nama');
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('cascade');
            $table->string('satuan');
            $table->decimal('qty_sisa', 15, 0);
            $table->decimal('harga', 15, 0);
            $table->decimal('jumlah', 15, 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opnames');
    }
};
