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
            $table->foreignId('kode_barang')->nullable();
            $table->foreignId('department_id')->nullable();
            $table->decimal('qty', 15, 0)->default(0);
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
