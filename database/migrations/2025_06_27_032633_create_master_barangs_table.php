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
        Schema::create('master_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
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
        Schema::dropIfExists('master_barangs');
    }
};
