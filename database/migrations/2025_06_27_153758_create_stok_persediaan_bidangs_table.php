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
        Schema::create('stok_persediaan_bidangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kelompok')->nullable();
            $table->string('kode_barang', 50);
            $table->foreignId('bidang_id')->nullable()->constrained('bidangs')->onDelete('set null');
            $table->string('nama_barang');
            $table->string('satuan');
            $table->decimal('qty_sisa', 15, 2)->default(0);
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_persediaan_bidangs');
    }
};
