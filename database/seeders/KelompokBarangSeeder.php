<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelompokBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['kode_kelompok' => '1.1.12.01.01.0001', 'nama' => 'Bahan Bangunan dan Konstruksi'],
            ['kode_kelompok' => '1.1.12.01.01.0002', 'nama' => 'Bahan Kimia'],
            ['kode_kelompok' => '1.1.12.01.01.0004', 'nama' => 'Bahan Bakar dan Pelumas'],
            ['kode_kelompok' => '1.1.12.01.01.0005', 'nama' => 'Bahan Baku'],
            ['kode_kelompok' => '1.1.12.01.01.0006', 'nama' => 'Bahan Kimia Nuklir'],
            ['kode_kelompok' => '1.1.12.01.01.0007', 'nama' => 'Barang Dalam Proses'],
            ['kode_kelompok' => '1.1.12.01.01.0008', 'nama' => 'Bahan/Bibit Tanaman'],
            ['kode_kelompok' => '1.1.12.01.01.0009', 'nama' => 'Isi Tabung Pemadam Kebakaran'],
            ['kode_kelompok' => '1.1.12.01.01.0010', 'nama' => 'Isi Tabung Gas'],
            ['kode_kelompok' => '1.1.12.01.01.0011', 'nama' => 'Bahan/Bibit Ternak/Bibit Ikan'],
            ['kode_kelompok' => '1.1.12.01.01.0012', 'nama' => 'Bahan Lainnya'],
            ['kode_kelompok' => '1.1.12.01.02.0001', 'nama' => 'Suku Cadang Alat Angkutan'],
            ['kode_kelompok' => '1.1.12.01.02.0002', 'nama' => 'Suku Cadang Alat Besar'],
            ['kode_kelompok' => '1.1.12.01.02.0003', 'nama' => 'Suku Cadang Alat Kedokteran'],
            ['kode_kelompok' => '1.1.12.01.02.0004', 'nama' => 'Suku Cadang Alat Laboratorium'],
            ['kode_kelompok' => '1.1.12.01.02.0005', 'nama' => 'Suku Cadang Alat Pemancar'],
            ['kode_kelompok' => '1.1.12.01.02.0006', 'nama' => 'Suku Cadang Alat Studio dan Komunikasi'],
            ['kode_kelompok' => '1.1.12.01.02.0007', 'nama' => 'Suku Cadang Alat Pertanian'],
            ['kode_kelompok' => '1.1.12.01.02.0008', 'nama' => 'Suku Cadang Alat Bengkel'],
            ['kode_kelompok' => '1.1.12.01.02.0010', 'nama' => 'Persediaan dari Belanja Bantuan Sosial'],
            ['kode_kelompok' => '1.1.12.01.02.0011', 'nama' => 'Suku Cadang Lainnya'],
            ['kode_kelompok' => '1.1.12.01.03.0001', 'nama' => 'Alat Tulis Kantor'],
            ['kode_kelompok' => '1.1.12.01.03.0002', 'nama' => 'Kertas dan Cover'],
            ['kode_kelompok' => '1.1.12.01.03.0003', 'nama' => 'Bahan Cetak'],
            ['kode_kelompok' => '1.1.12.01.03.0004', 'nama' => 'Benda Pos'],
            ['kode_kelompok' => '1.1.12.01.03.0005', 'nama' => 'Persediaan Dokumen/Administrasi Tender'],
            ['kode_kelompok' => '1.1.12.01.03.0006', 'nama' => 'Bahan Komputer'],
            ['kode_kelompok' => '1.1.12.01.03.0007', 'nama' => 'Perabot Kantor'],
            ['kode_kelompok' => '1.1.12.01.03.0008', 'nama' => 'Alat Listrik'],
            ['kode_kelompok' => '1.1.12.01.03.0009', 'nama' => 'Perlengkapan Dinas'],
            ['kode_kelompok' => '1.1.12.01.03.0010', 'nama' => 'Kaporlap dan Perlengkapan Satwa'],
            ['kode_kelompok' => '1.1.12.01.03.0011', 'nama' => 'Perlengkapan Pendukung Olahraga'],
            ['kode_kelompok' => '1.1.12.01.03.0012', 'nama' => 'Suvenir/Cendera Mata'],
            ['kode_kelompok' => '1.1.12.01.03.0013', 'nama' => 'Alat/Bahan Untuk Kegiatan Kantor Lainnya'],
            ['kode_kelompok' => '1.1.12.01.04.0001', 'nama' => 'Obat'],
            ['kode_kelompok' => '1.1.12.01.04.0002', 'nama' => 'Obat-obatan Lainnya'],
            ['kode_kelompok' => '1.1.12.01.05.0001', 'nama' => 'Persediaan Untuk Dijual/Diserahkan Kepada Masyarakat'],
            ['kode_kelompok' => '1.1.12.01.05.0002', 'nama' => 'Persediaan Untuk Dijual/Diserahkan Lainnya'],
            ['kode_kelompok' => '1.1.12.01.06.0001', 'nama' => 'Persediaan Untuk Tujuan Strategis/Berjaga-Jaga'],
            ['kode_kelompok' => '1.1.12.01.06.0002', 'nama' => 'Persediaan Untuk Tujuan Strategis/Berjaga-Jaga Lainnya'],
            ['kode_kelompok' => '1.1.12.01.07.0001', 'nama' => 'Natura'],
            ['kode_kelompok' => '1.1.12.01.07.0002', 'nama' => 'Pakan'],
            ['kode_kelompok' => '1.1.12.01.07.0003', 'nama' => 'Natura dan Pakan Lainnya'],
            ['kode_kelompok' => '1.1.12.01.08.0001', 'nama' => 'Persediaan Penelitian Biologi'],
            ['kode_kelompok' => '1.1.12.01.08.0002', 'nama' => 'Persediaan Penelitian Biologi Lainnya'],
            ['kode_kelompok' => '1.1.12.01.08.0003', 'nama' => 'Persediaan Penelitian Teknologi'],
            ['kode_kelompok' => '1.1.12.01.08.0004', 'nama' => 'Persediaan Penelitian Lainnya'],
            ['kode_kelompok' => '1.1.12.01.09.0001', 'nama' => 'Persediaan Dalam Proses'],
            ['kode_kelompok' => '1.1.12.01.09.0002', 'nama' => 'Persediaan Dalam Proses Lainnya'],
            ['kode_kelompok' => '1.1.12.02.01.0001', 'nama' => 'Komponen Jembatan Baja'],
            ['kode_kelompok' => '1.1.12.02.01.0002', 'nama' => 'Komponen Jembatan Pratekan'],
            ['kode_kelompok' => '1.1.12.02.01.0003', 'nama' => 'Komponen Peralatan'],
            ['kode_kelompok' => '1.1.12.02.01.0004', 'nama' => 'Komponen Rambu-Rambu'],
            ['kode_kelompok' => '1.1.12.02.01.0005', 'nama' => 'Attachment'],
            ['kode_kelompok' => '1.1.12.02.01.0006', 'nama' => 'Komponen Lainnya'],
            ['kode_kelompok' => '1.1.12.02.02.0001', 'nama' => 'Pipa Air Besi Tuang (DCI)'],
            ['kode_kelompok' => '1.1.12.02.02.0002', 'nama' => 'Pipa Asbes Semen (ACP)'],
            ['kode_kelompok' => '1.1.12.02.02.0003', 'nama' => 'Pipa Baja'],
            ['kode_kelompok' => '1.1.12.02.02.0004', 'nama' => 'Pipa Beton Pratekan'],
            ['kode_kelompok' => '1.1.12.02.02.0005', 'nama' => 'Pipa Fiber Glass'],
            ['kode_kelompok' => '1.1.12.02.02.0006', 'nama' => 'Pipa Plastik PVC (UPVC)'],
            ['kode_kelompok' => '1.1.12.02.02.0007', 'nama' => 'Pipa Lainnya'],
            ['kode_kelompok' => '1.1.12.03.01.0001', 'nama' => 'Komponen Bekas'],
            ['kode_kelompok' => '1.1.12.03.01.0002', 'nama' => 'Pipa Bekas'],
            ['kode_kelompok' => '1.1.12.03.01.0003', 'nama' => 'Komponen Bekas dan Pipa Bekas Lainnya'],
        ];

        DB::table('kelompok_barangs')->insert($data);
    }
}