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
        Schema::table('penerimaan', function (Blueprint $table) {
            // Menambahkan kolom 'bidang_id' setelah kolom 'id'
            // Dibuat 'nullable' agar tidak error pada data yang sudah ada
            $table->unsignedBigInteger('bidang_id')->nullable()->after('id');

            // (Opsional tapi sangat disarankan) Menambahkan foreign key constraint
            // Saya asumsikan Anda punya tabel 'bidang' dengan primary key 'id'
            $table->foreign('bidang_id')->references('id')->on('bidangs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penerimaan', function (Blueprint $table) {
            // Hapus foreign key dulu sebelum menghapus kolomnya
            $table->dropForeign(['bidang_id']);
            $table->dropColumn('bidang_id');
        });
    }
};
