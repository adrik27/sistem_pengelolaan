<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategoris')->insert([
            ['nama_kategori' => 'Bahan Bangunan dan Konstruksi'], // [cite: 6]
            ['nama_kategori' => 'Bahan Kimia'], // [cite: 6]
            ['nama_kategori' => 'Barang Dalam Proses'], // [cite: 6]
            ['nama_kategori' => 'Bahan Lainnya'], // [cite: 6]
            ['nama_kategori' => 'Suku Cadang Alat Angkutan'], // [cite: 6]
            ['nama_kategori' => 'Suku Cadang Alat Kedokteran'], // [cite: 6]
            ['nama_kategori' => 'Alat Tulis Kantor'], // [cite: 6]
            ['nama_kategori' => 'Kertas dan Cover'], // [cite: 9]
            ['nama_kategori' => 'Bahan Cetak'], // [cite: 9]
            ['nama_kategori' => 'Benda Pos'], // [cite: 9]
            ['nama_kategori' => 'Bahan Komputer'], // [cite: 9]
            ['nama_kategori' => 'Perabot Kantor'], // [cite: 9]
            ['nama_kategori' => 'Alat Listrik'], // [cite: 9]
            ['nama_kategori' => 'Alat/Bahan Untuk Kegiatan Kantor Lainnya'], // [cite: 12]
            ['nama_kategori' => 'Obat'], // [cite: 12]
            ['nama_kategori' => 'Obat-obatan Lainnya'], // [cite: 12]
            ['nama_kategori' => 'Natura dan Pakan Lainnya'], // [cite: 12]
        ]);
    }
}
