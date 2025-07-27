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
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidang_id')->nullable();
            $table->date('tanggal_pembukuan');
            $table->string('status_pengeluaran');
            $table->string('kode_barang'); // Akan kita ambil dari Select2
            $table->string('nama_barang'); // Simpan juga namanya untuk kemudahan
            $table->decimal('qty', 15, 2);
            // $table->decimal('harga_satuan', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};
