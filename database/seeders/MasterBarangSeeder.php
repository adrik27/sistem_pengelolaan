<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoriMap = DB::table('kategoris')->pluck('id', 'nama_kategori');

        $barang = [
            // Kategori: Bahan Bangunan dan Konstruksi
            ['kode_barang' => '1.1.12.01.01.0001.00775', 'nama' => 'Karung Plastik Kecil', 'kategori_nama' => 'Bahan Bangunan dan Konstruksi', 'satuan' => 'Buah', 'qty_sisa' => 285.00, 'harga' => 6000.00, 'jumlah' => 1710000.00],
            // Kategori: Bahan Kimia
            ['kode_barang' => '1.1.12.01.01.0002.00011', 'nama' => 'Alkohol 70% (1 liter)', 'kategori_nama' => 'Bahan Kimia', 'satuan' => 'Botol', 'qty_sisa' => 6.00, 'harga' => 50000.00, 'jumlah' => 300000.00],
            ['kode_barang' => '1.1.12.01.01.0002.00089', 'nama' => 'Desinfektan (1 Liter)', 'kategori_nama' => 'Bahan Kimia', 'satuan' => 'Jerigen', 'qty_sisa' => 25.00, 'harga' => 133000.00, 'jumlah' => 3325000.00],
            ['kode_barang' => '1.1.12.01.01.0002.00143', 'nama' => 'Klerat', 'kategori_nama' => 'Bahan Kimia', 'satuan' => 'Kg', 'qty_sisa' => 16.00, 'harga' => 64000.00, 'jumlah' => 1024000.00],
            // Kategori: Barang Dalam Proses
            ['kode_barang' => '1.1.12.01.01.0007.00001', 'nama' => 'Gabah Kering Giling (GKG) (Spesifikasi: Kadar air maksimum 13-14%, Kadar hampa/ kotoran maksimum 3%)', 'kategori_nama' => 'Barang Dalam Proses', 'satuan' => 'Kg', 'qty_sisa' => 9500.00, 'harga' => 7000.00, 'jumlah' => 66500000.00],
            // Kategori: Bahan Lainnya
            ['kode_barang' => '1.1.12.01.01.0012.00176', 'nama' => 'Spuit 10 co', 'kategori_nama' => 'Bahan Lainnya', 'satuan' => 'Buah', 'qty_sisa' => 1145.00, 'harga' => 2200.00, 'jumlah' => 2519000.00],
            ['kode_barang' => '1.1.12.01.01.0012.00178', 'nama' => 'Spuit 3 cc', 'kategori_nama' => 'Bahan Lainnya', 'satuan' => 'Buah', 'qty_sisa' => 1455.00, 'harga' => 3400.00, 'jumlah' => 4947000.00],
            ['kode_barang' => '1.1.12.01.01.0012.00179', 'nama' => 'Spuit 5 cc', 'kategori_nama' => 'Bahan Lainnya', 'satuan' => 'Buah', 'qty_sisa' => 990.00, 'harga' => 3400.00, 'jumlah' => 3366000.00],
            // Kategori: Suku Cadang Alat Angkutan
            ['kode_barang' => '1.1.12.01.02.0001.00001', 'nama' => 'ACCU/Aki 6 valt', 'kategori_nama' => 'Suku Cadang Alat Angkutan', 'satuan' => 'Buah', 'qty_sisa' => 12.00, 'harga' => 205000.00, 'jumlah' => 2460000.00],
            ['kode_barang' => '1.1.12.01.02.0001.00021', 'nama' => 'Ban dalam roda dua (Ban dalam roda dua)', 'kategori_nama' => 'Suku Cadang Alat Angkutan', 'satuan' => 'Buah', 'qty_sisa' => 5.00, 'harga' => 30000.00, 'jumlah' => 150000.00],
            ['kode_barang' => '1.1.12.01.02.0001.00059', 'nama' => 'Gear set motor (Gear set / rantai roda sepeda motor)', 'kategori_nama' => 'Suku Cadang Alat Angkutan', 'satuan' => 'Set', 'qty_sisa' => 9.00, 'harga' => 190000.00, 'jumlah' => 1710000.00],
            // Kategori: Suku Cadang Alat Kedokteran
            ['kode_barang' => '1.1.12.01.02.0003.00036', 'nama' => 'Sarung tangan non steril', 'kategori_nama' => 'Suku Cadang Alat Kedokteran', 'satuan' => 'Dus', 'qty_sisa' => 15.00, 'harga' => 153000.00, 'jumlah' => 2295000.00],
            // Kategori: Alat Tulis Kantor [cite: 6, 9]
            ['kode_barang' => '1.1.12.01.03.0001.00005', 'nama' => 'Ballpoint/ Pulpen/Pena (Setara Standart)', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buah', 'qty_sisa' => 46.00, 'harga' => 3100.00, 'jumlah' => 142600.00],
            ['kode_barang' => '1.1.12.01.03.0001.00006', 'nama' => 'Ballpoint/ Pulpen/Pena (Setara Faster)', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buah', 'qty_sisa' => 58.00, 'harga' => 4100.00, 'jumlah' => 237800.00],
            ['kode_barang' => '1.1.12.01.03.0001.00016', 'nama' => 'Binder Clip 105', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Dus', 'qty_sisa' => 30.00, 'harga' => 15000.00, 'jumlah' => 450000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00017', 'nama' => 'Binder clip 107', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Dus', 'qty_sisa' => 54.00, 'harga' => 4500.00, 'jumlah' => 243000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00018', 'nama' => 'Binder clip 260', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Dus', 'qty_sisa' => 24.00, 'harga' => 20400.00, 'jumlah' => 489600.00],
            ['kode_barang' => '1.1.12.01.03.0001.00023', 'nama' => 'Buku Agenda Surat Keluar', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buah', 'qty_sisa' => 5.00, 'harga' => 22000.00, 'jumlah' => 110000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00027', 'nama' => 'Buku Folio bergaris Isi 100 lembar', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buah', 'qty_sisa' => 4.00, 'harga' => 19250.00, 'jumlah' => 77000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00028', 'nama' => 'Buku Folio bergaris Isi 200 lembar', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buah', 'qty_sisa' => 5.00, 'harga' => 55000.00, 'jumlah' => 275000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00033', 'nama' => 'Buku Kuitansi Besar', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buku', 'qty_sisa' => 14.00, 'harga' => 7000.00, 'jumlah' => 98000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00041', 'nama' => 'Buku tulis Isi 38 lembar', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buah', 'qty_sisa' => 9.00, 'harga' => 4500.00, 'jumlah' => 40500.00],
            ['kode_barang' => '1.1.12.01.03.0001.00044', 'nama' => 'Business File', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Pak', 'qty_sisa' => 9.00, 'harga' => 28000.00, 'jumlah' => 252000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00047', 'nama' => 'Clip Besar (Dus (10 pack))', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Dus', 'qty_sisa' => 2.00, 'harga' => 45000.00, 'jumlah' => 90000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00061', 'nama' => 'Hechmachine / Stapler Besar (HD-50)', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buah', 'qty_sisa' => 3.00, 'harga' => 27000.00, 'jumlah' => 81000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00062', 'nama' => 'Hechmachine / Stapler Kecil (HD-10)', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buah', 'qty_sisa' => 4.00, 'harga' => 17000.00, 'jumlah' => 68000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00070', 'nama' => 'Isolasi Biasa', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Roll', 'qty_sisa' => 3.00, 'harga' => 6000.00, 'jumlah' => 18000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00075', 'nama' => 'Kertas Post-it / Sticky Note', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Pak', 'qty_sisa' => 14.00, 'harga' => 11000.00, 'jumlah' => 154000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00081', 'nama' => 'Lem Cair Kecil', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Botol', 'qty_sisa' => 4.00, 'harga' => 3200.00, 'jumlah' => 12800.00],
            ['kode_barang' => '1.1.12.01.03.0001.00082', 'nama' => 'Lem Cair Tanggung 75 ml.', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Botol', 'qty_sisa' => 15.00, 'harga' => 4500.00, 'jumlah' => 67500.00],
            ['kode_barang' => '1.1.12.01.03.0001.00095', 'nama' => 'Map Zipper Bag Plasticlip/reseling', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buah', 'qty_sisa' => 3.00, 'harga' => 21000.00, 'jumlah' => 63000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00102', 'nama' => 'Ordner Folio', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buah', 'qty_sisa' => 14.00, 'harga' => 20000.00, 'jumlah' => 280000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00108', 'nama' => 'Paper plate', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Dus', 'qty_sisa' => 1.00, 'harga' => 18000.00, 'jumlah' => 18000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00118', 'nama' => 'Penghapus / Tipe X Kertas (Setara Kenko)', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buah', 'qty_sisa' => 7.00, 'harga' => 11000.00, 'jumlah' => 77000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00122', 'nama' => 'Penghapus Papan Tulis', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buah', 'qty_sisa' => 1.00, 'harga' => 15000.00, 'jumlah' => 15000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00132', 'nama' => 'Pensil Hitam 2B', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buah', 'qty_sisa' => 2.00, 'harga' => 4500.00, 'jumlah' => 9000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00154', 'nama' => 'Pita Mesin Tik Biasa Rol Kecil', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Roll', 'qty_sisa' => 21.00, 'harga' => 16250.00, 'jumlah' => 341250.00],
            ['kode_barang' => '1.1.12.01.03.0001.00174', 'nama' => 'Refill / Isi ballpoint/pulpen Pentel MG8', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buah', 'qty_sisa' => 8.00, 'harga' => 26000.00, 'jumlah' => 208000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00184', 'nama' => 'Snelhecter Folio', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Pak', 'qty_sisa' => 14.00, 'harga' => 24000.00, 'jumlah' => 336000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00185', 'nama' => 'Snelhecter Folia', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buah', 'qty_sisa' => 226.00, 'harga' => 2800.00, 'jumlah' => 632800.00],
            ['kode_barang' => '1.1.12.01.03.0001.00186', 'nama' => 'Snelhecter Plastik', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buah', 'qty_sisa' => 81.00, 'harga' => 2800.00, 'jumlah' => 226800.00],
            ['kode_barang' => '1.1.12.01.03.0001.00187', 'nama' => 'Snelhecter Plastik Tebal', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Biji', 'qty_sisa' => 8.00, 'harga' => 7000.00, 'jumlah' => 56000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00193', 'nama' => 'Spidol Snowman Boardmarker', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Biji', 'qty_sisa' => 1.00, 'harga' => 9000.00, 'jumlah' => 9000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00194', 'nama' => 'Spidol Snowman Permanent', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Biji', 'qty_sisa' => 21.00, 'harga' => 7900.00, 'jumlah' => 165900.00],
            ['kode_barang' => '1.1.12.01.03.0001.00198', 'nama' => 'Stabilo Boss', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Biji', 'qty_sisa' => 14.00, 'harga' => 11000.00, 'jumlah' => 154000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00208', 'nama' => 'Stopmap Folio', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Pak', 'qty_sisa' => 21.00, 'harga' => 26000.00, 'jumlah' => 546000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00210', 'nama' => 'Stopmap Plastik', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buah', 'qty_sisa' => 76.00, 'harga' => 9000.00, 'jumlah' => 684000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00213', 'nama' => 'Stopmap- Snelhecter Plastik', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buah', 'qty_sisa' => 20.00, 'harga' => 2900.00, 'jumlah' => 58000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00218', 'nama' => 'Tinta Stempel', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Botol', 'qty_sisa' => 13.00, 'harga' => 4500.00, 'jumlah' => 58500.00],
            ['kode_barang' => '1.1.12.01.03.0001.00278', 'nama' => 'Cetak Karcis pungutan retribusi kertas CD ukuran 16x7 cm', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Lembar', 'qty_sisa' => 114546.00, 'harga' => 100.00, 'jumlah' => 11454600.00],
            ['kode_barang' => '1.1.12.01.03.0001.00311', 'nama' => 'Cetak Map Sedian', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Lembar', 'qty_sisa' => 677.00, 'harga' => 16000.00, 'jumlah' => 10832000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00399', 'nama' => 'binder klip 155.', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Dus', 'qty_sisa' => 26.00, 'harga' => 9000.00, 'jumlah' => 234000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00434', 'nama' => 'Isi Staples Besar GW no. 369 (Isi Staples Besar GW no. 369)', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Pack', 'qty_sisa' => 330.00, 'harga' => 2120.00, 'jumlah' => 699600.00], // Jumlah di PDF 699.500,00, hasil hitung 699.600,00
            ['kode_barang' => '1.1.12.01.03.0001.00438', 'nama' => 'Penghapus / Tipe x Cair (Penghapus / Tipe x Cair)', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buah', 'qty_sisa' => 16.00, 'harga' => 6000.00, 'jumlah' => 96000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00484', 'nama' => 'Klips (.)', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Dus', 'qty_sisa' => 89.00, 'harga' => 3983.00, 'jumlah' => 354487.00], // Jumlah di PDF 354.504,00, hasil hitung 354.487,00
            ['kode_barang' => '1.1.12.01.03.0001.00495', 'nama' => 'Kertas Faxs (.)', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Roll', 'qty_sisa' => 29.00, 'harga' => 18000.00, 'jumlah' => 522000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00523', 'nama' => 'Stabillo (,)', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'bh', 'qty_sisa' => 2.00, 'harga' => 12500.00, 'jumlah' => 25000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00525', 'nama' => 'Tinta stampel (.)', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'bil', 'qty_sisa' => 1.00, 'harga' => 4500.00, 'jumlah' => 4500.00],
            ['kode_barang' => '1.1.12.01.03.0001.00544', 'nama' => 'Kwitansi Besar (Kwitansi Besar)', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buku', 'qty_sisa' => 16.00, 'harga' => 7000.00, 'jumlah' => 112000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00593', 'nama' => 'Klip Besar No. 5 (.)', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Dos', 'qty_sisa' => 7.00, 'harga' => 38000.00, 'jumlah' => 266000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00597', 'nama' => 'Binder Klip No. 260 (.)', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Dos', 'qty_sisa' => 24.00, 'harga' => 22000.00, 'jumlah' => 528000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00599', 'nama' => 'Penghapus Pensil (Stip) (.)', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Buah', 'qty_sisa' => 5.00, 'harga' => 5000.00, 'jumlah' => 25000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00692', 'nama' => 'Penghapus / Tipe X Kertas Setara Kenko (Pak) (.)', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'pak', 'qty_sisa' => 5.00, 'harga' => 11000.00, 'jumlah' => 55000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00717', 'nama' => 'Plastik Sheet', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Pak', 'qty_sisa' => 17.00, 'harga' => 235000.00, 'jumlah' => 3995000.00],
            ['kode_barang' => '1.1.12.01.03.0001.00792', 'nama' => 'Cetak Kertas Kop Garuda Emas', 'kategori_nama' => 'Alat Tulis Kantor', 'satuan' => 'Rim', 'qty_sisa' => 1.00, 'harga' => 420000.00, 'jumlah' => 420000.00],
            // Kategori: Kertas dan Cover 
            ['kode_barang' => '1.1.12.01.03.0002.00001', 'nama' => 'Amplop Besar', 'kategori_nama' => 'Kertas dan Cover', 'satuan' => 'Pak', 'qty_sisa' => 7.00, 'harga' => 24500.00, 'jumlah' => 171500.00],
            ['kode_barang' => '1.1.12.01.03.0002.00002', 'nama' => 'Amplop Kecil', 'kategori_nama' => 'Kertas dan Cover', 'satuan' => 'Pak', 'qty_sisa' => 4.00, 'harga' => 13000.00, 'jumlah' => 52000.00],
            ['kode_barang' => '1.1.12.01.03.0002.00005', 'nama' => 'Amplop Sedang', 'kategori_nama' => 'Kertas dan Cover', 'satuan' => 'Pak', 'qty_sisa' => 14.00, 'harga' => 16000.00, 'jumlah' => 224000.00],
            ['kode_barang' => '1.1.12.01.03.0002.00008', 'nama' => 'Faximile Kertas Fax', 'kategori_nama' => 'Kertas dan Cover', 'satuan' => 'Roll', 'qty_sisa' => 7.00, 'harga' => 140000.00, 'jumlah' => 980000.00],
            ['kode_barang' => '1.1.12.01.03.0002.00025', 'nama' => 'Kertas HVS 70 gr Folio', 'kategori_nama' => 'Kertas dan Cover', 'satuan' => 'Rim', 'qty_sisa' => 75.00, 'harga' => 64000.00, 'jumlah' => 4800000.00],
            ['kode_barang' => '1.1.12.01.03.0002.00030', 'nama' => 'Kertas HVS Folio Warna (70 Gram)', 'kategori_nama' => 'Kertas dan Cover', 'satuan' => 'Rim', 'qty_sisa' => 2.00, 'harga' => 74000.00, 'jumlah' => 148000.00],
            // Kategori: Bahan Cetak 
            ['kode_barang' => '1.1.12.01.03.0003.00061', 'nama' => 'cetak stopmap bupati (.)', 'kategori_nama' => 'Bahan Cetak', 'satuan' => 'bh', 'qty_sisa' => 217.00, 'harga' => 10500.00, 'jumlah' => 2278500.00],
            ['kode_barang' => '1.1.12.01.03.0003.00062', 'nama' => 'cetak stopmap W. bupati (,)', 'kategori_nama' => 'Bahan Cetak', 'satuan' => 'bh', 'qty_sisa' => 300.00, 'harga' => 10500.00, 'jumlah' => 3150000.00],
            // Kategori: Benda Pos 
            ['kode_barang' => '1.1.12.01.03.0004.00001', 'nama' => 'Materai / Meterai 10.000', 'kategori_nama' => 'Benda Pos', 'satuan' => 'Lembar', 'qty_sisa' => 258.00, 'harga' => 11000.00, 'jumlah' => 2838000.00],
            // Kategori: Bahan Komputer 
            ['kode_barang' => '1.1.12.01.03.0006.00019', 'nama' => 'Cartridge Printer Canon Pixma Hitam', 'kategori_nama' => 'Bahan Komputer', 'satuan' => 'Buah', 'qty_sisa' => 9.00, 'harga' => 360000.00, 'jumlah' => 3240000.00],
            ['kode_barang' => '1.1.12.01.03.0006.00020', 'nama' => 'Cartridge Printer Canon Pixma Warna', 'kategori_nama' => 'Bahan Komputer', 'satuan' => 'Buah', 'qty_sisa' => 10.00, 'harga' => 395000.00, 'jumlah' => 3950000.00],
            ['kode_barang' => '1.1.12.01.03.0006.00149', 'nama' => 'Tinta Epson Original', 'kategori_nama' => 'Bahan Komputer', 'satuan' => 'Unit', 'qty_sisa' => 18.00, 'harga' => 100000.00, 'jumlah' => 1800000.00],
            ['kode_barang' => '1.1.12.01.03.0006.00163', 'nama' => 'Tinta Printer Hitam Compatible Canon, Epson, HP, Fujitsu, Brother, Fuji Xerox, Samsung, Kodak, Fargo', 'kategori_nama' => 'Bahan Komputer', 'satuan' => 'Buah', 'qty_sisa' => 14.00, 'harga' => 45000.00, 'jumlah' => 630000.00],
            ['kode_barang' => '1.1.12.01.03.0006.00185', 'nama' => 'Tinta Printer Epson 664 warna (Tinta Printer Epson 664 warna)', 'kategori_nama' => 'Bahan Komputer', 'satuan' => 'Pcs', 'qty_sisa' => 3.00, 'harga' => 100000.00, 'jumlah' => 300000.00],
            ['kode_barang' => '1.1.12.01.03.0006.00187', 'nama' => 'Tinta Printer Epson 001 warna (Tinta Printer Epson 001 warna)', 'kategori_nama' => 'Bahan Komputer', 'satuan' => 'Pcs', 'qty_sisa' => 3.00, 'harga' => 120000.00, 'jumlah' => 360000.00],
            ['kode_barang' => '1.1.12.01.03.0006.00190', 'nama' => 'Tinta Canon GL790 warna hitam (Tinta Canon GL790 warna hitam)', 'kategori_nama' => 'Bahan Komputer', 'satuan' => 'Pcs', 'qty_sisa' => 2.00, 'harga' => 53000.00, 'jumlah' => 106000.00],
            ['kode_barang' => '1.1.12.01.03.0006.00191', 'nama' => 'Tinta Printer Epson 003 hitam (Tinta Printer Epson 003 hitam)', 'kategori_nama' => 'Bahan Komputer', 'satuan' => 'Pcs', 'qty_sisa' => 3.00, 'harga' => 100000.00, 'jumlah' => 300000.00],
            // Kategori: Perabot Kantor 
            ['kode_barang' => '1.1.12.01.03.0007.00079', 'nama' => 'Isi ulang pewangi ruangan gantung', 'kategori_nama' => 'Perabot Kantor', 'satuan' => 'Buah', 'qty_sisa' => 5.00, 'harga' => 15500.00, 'jumlah' => 77500.00],
            ['kode_barang' => '1.1.12.01.03.0007.00080', 'nama' => 'Isi ulang pewangi ruangan matic', 'kategori_nama' => 'Perabot Kantor', 'satuan' => 'Buah', 'qty_sisa' => 19.00, 'harga' => 45000.00, 'jumlah' => 855000.00],
            ['kode_barang' => '1.1.12.01.03.0007.00145', 'nama' => 'Pengharum ruangan.', 'kategori_nama' => 'Perabot Kantor', 'satuan' => 'Buah', 'qty_sisa' => 3.00, 'harga' => 16000.00, 'jumlah' => 48000.00],
            ['kode_barang' => '1.1.12.01.03.0007.00170', 'nama' => 'Refil kain pel', 'kategori_nama' => 'Perabot Kantor', 'satuan' => 'Buah', 'qty_sisa' => 9.00, 'harga' => 15556.00, 'jumlah' => 140004.00], // Jumlah di PDF 140.000,00, hasil hitung 140.004,00
            ['kode_barang' => '1.1.12.01.03.0007.00174', 'nama' => 'Sapu cemara', 'kategori_nama' => 'Perabot Kantor', 'satuan' => 'Buah', 'qty_sisa' => 24.00, 'harga' => 65000.00, 'jumlah' => 1560000.00],
            ['kode_barang' => '1.1.12.01.03.0007.00176', 'nama' => 'Sapu lantai', 'kategori_nama' => 'Perabot Kantor', 'satuan' => 'Buah', 'qty_sisa' => 10.00, 'harga' => 24800.00, 'jumlah' => 248000.00],
            ['kode_barang' => '1.1.12.01.03.0007.00177', 'nama' => 'Sapu Lidi', 'kategori_nama' => 'Perabot Kantor', 'satuan' => 'Buah', 'qty_sisa' => 31.00, 'harga' => 48000.00, 'jumlah' => 1488000.00],
            ['kode_barang' => '1.1.12.01.03.0007.00198', 'nama' => 'Sikat Kamar Mandi', 'kategori_nama' => 'Perabot Kantor', 'satuan' => 'Buah', 'qty_sisa' => 10.00, 'harga' => 36000.00, 'jumlah' => 360000.00],
            ['kode_barang' => '1.1.12.01.03.0007.00201', 'nama' => 'Sikat WC', 'kategori_nama' => 'Perabot Kantor', 'satuan' => 'Buah', 'qty_sisa' => 7.00, 'harga' => 28000.00, 'jumlah' => 196000.00],
            ['kode_barang' => '1.1.12.01.03.0007.00202', 'nama' => 'Silikon cair/Cairan pengkilap ban', 'kategori_nama' => 'Perabot Kantor', 'satuan' => 'Liter', 'qty_sisa' => 4.00, 'harga' => 76000.00, 'jumlah' => 304000.00],
            ['kode_barang' => '1.1.12.01.03.0007.00229', 'nama' => 'Sulak bulu ayam', 'kategori_nama' => 'Perabot Kantor', 'satuan' => 'Buah', 'qty_sisa' => 3.00, 'harga' => 85000.00, 'jumlah' => 255000.00],
            ['kode_barang' => '1.1.12.01.03.0007.00261', 'nama' => 'Tongkat Pel', 'kategori_nama' => 'Perabot Kantor', 'satuan' => 'Buah', 'qty_sisa' => 15.00, 'harga' => 84000.00, 'jumlah' => 1260000.00],
            ['kode_barang' => '1.1.12.01.03.0007.00275', 'nama' => 'Pembersih Kaca Mobil', 'kategori_nama' => 'Perabot Kantor', 'satuan' => 'Buah', 'qty_sisa' => 8.00, 'harga' => 16218.00, 'jumlah' => 129744.00], // Jumlah di PDF 129.743,00, hasil hitung 129.744,00
            ['kode_barang' => '1.1.12.01.03.0007.00276', 'nama' => 'Pembersih kamar mandi', 'kategori_nama' => 'Perabot Kantor', 'satuan' => 'Buah', 'qty_sisa' => 17.00, 'harga' => 18000.00, 'jumlah' => 306000.00],
            ['kode_barang' => '1.1.12.01.03.0007.00280', 'nama' => 'Pembersih Porselen/Closet', 'kategori_nama' => 'Perabot Kantor', 'satuan' => 'Buah', 'qty_sisa' => 4.00, 'harga' => 18290.00, 'jumlah' => 73160.00], // Jumlah di PDF 73.159,00, hasil hitung 73.160,00
            ['kode_barang' => '1.1.12.01.03.0007.00281', 'nama' => 'Pembersih tangan botol', 'kategori_nama' => 'Perabot Kantor', 'satuan' => 'Buah', 'qty_sisa' => 27.00, 'harga' => 34000.00, 'jumlah' => 918000.00],
            ['kode_barang' => '1.1.12.01.03.0007.00285', 'nama' => 'Pengharum/Parfum mobil', 'kategori_nama' => 'Perabot Kantor', 'satuan' => 'Buah', 'qty_sisa' => 3.00, 'harga' => 68000.00, 'jumlah' => 204000.00],
            ['kode_barang' => '1.1.12.01.03.0007.00286', 'nama' => 'Pengharum Liquid / Ruangan', 'kategori_nama' => 'Perabot Kantor', 'satuan' => 'Buah', 'qty_sisa' => 9.00, 'harga' => 38500.00, 'jumlah' => 346500.00],
            ['kode_barang' => '1.1.12.01.03.0007.00349', 'nama' => 'Tissue halus/ruangan (Tissue halus/ruangan)', 'kategori_nama' => 'Perabot Kantor', 'satuan' => 'Buah', 'qty_sisa' => 109.00, 'harga' => 14800.00, 'jumlah' => 1613200.00],
            // Kategori: Alat Listrik [cite: 9, 12]
            ['kode_barang' => '1.1.12.01.03.0008.00039', 'nama' => 'Isolasi Band Kecil.', 'kategori_nama' => 'Alat Listrik', 'satuan' => 'Roll', 'qty_sisa' => 3.00, 'harga' => 7000.00, 'jumlah' => 21000.00],
            ['kode_barang' => '1.1.12.01.03.0008.00095', 'nama' => 'Lampu TL 18 watt', 'kategori_nama' => 'Alat Listrik', 'satuan' => 'Buah', 'qty_sisa' => 112.00, 'harga' => 87000.00, 'jumlah' => 9744000.00],
            ['kode_barang' => '1.1.12.01.03.0008.00300', 'nama' => 'BAtu Baterai alkaline (-)', 'kategori_nama' => 'Alat Listrik', 'satuan' => 'Buah', 'qty_sisa' => 24.00, 'harga' => 10000.00, 'jumlah' => 240000.00],
            ['kode_barang' => '1.1.12.01.03.0008.00336', 'nama' => 'Batu baterai A2 (Batu baterai A2)', 'kategori_nama' => 'Alat Listrik', 'satuan' => 'Buah', 'qty_sisa' => 99.00, 'harga' => 3000.00, 'jumlah' => 297000.00],
            // Kategori: Alat/Bahan Untuk Kegiatan Kantor Lainnya
            ['kode_barang' => '1.1.12.01.03.0013.00197', 'nama' => 'Pisau Cutter Besar (L-500)', 'kategori_nama' => 'Alat/Bahan Untuk Kegiatan Kantor Lainnya', 'satuan' => 'Buah', 'qty_sisa' => 2.00, 'harga' => 19000.00, 'jumlah' => 38000.00],
            ['kode_barang' => '1.1.12.01.03.0013.00201', 'nama' => 'Plak Band Kain 2', 'kategori_nama' => 'Alat/Bahan Untuk Kegiatan Kantor Lainnya', 'satuan' => 'Roll', 'qty_sisa' => 5.00, 'harga' => 17000.00, 'jumlah' => 85000.00],
            ['kode_barang' => '1.1.12.01.03.0013.00283', 'nama' => 'Semprot serangga / nyamuk', 'kategori_nama' => 'Alat/Bahan Untuk Kegiatan Kantor Lainnya', 'satuan' => 'Botol', 'qty_sisa' => 10.00, 'harga' => 39000.00, 'jumlah' => 390000.00],
            ['kode_barang' => '1.1.12.01.03.0013.00363', 'nama' => 'Toilet Colour Ball (12 bungkus)', 'kategori_nama' => 'Alat/Bahan Untuk Kegiatan Kantor Lainnya', 'satuan' => 'Buah', 'qty_sisa' => 4.00, 'harga' => 16875.00, 'jumlah' => 67500.00],
            ['kode_barang' => '1.1.12.01.03.0013.00465', 'nama' => 'Hand Sanitizer (.)', 'kategori_nama' => 'Alat/Bahan Untuk Kegiatan Kantor Lainnya', 'satuan' => 'bh', 'qty_sisa' => 3.00, 'harga' => 165000.00, 'jumlah' => 495000.00],
            ['kode_barang' => '1.1.12.01.03.0013.00470', 'nama' => 'Refill pembersih lantai (wipol/super pell)', 'kategori_nama' => 'Alat/Bahan Untuk Kegiatan Kantor Lainnya', 'satuan' => 'pcs', 'qty_sisa' => 26.00, 'harga' => 19000.00, 'jumlah' => 494000.00],
            // Kategori: Obat
            ['kode_barang' => '1.1.12.01.04.0001.00020', 'nama' => 'Anti bloat/obat kembung', 'kategori_nama' => 'Obat', 'satuan' => 'Botol', 'qty_sisa' => 5.00, 'harga' => 184000.00, 'jumlah' => 920000.00],
            ['kode_barang' => '1.1.12.01.04.0001.00022', 'nama' => 'Anti histamin', 'kategori_nama' => 'Obat', 'satuan' => 'Botol', 'qty_sisa' => 43.00, 'harga' => 144300.00, 'jumlah' => 6204900.00],
            ['kode_barang' => '1.1.12.01.04.0001.00024', 'nama' => 'Anti parasit spray', 'kategori_nama' => 'Obat', 'satuan' => 'Botol', 'qty_sisa' => 27.00, 'harga' => 177600.00, 'jumlah' => 4795200.00],
            ['kode_barang' => '1.1.12.01.04.0001.00025', 'nama' => 'Antibiotik penstrep', 'kategori_nama' => 'Obat', 'satuan' => 'Botol', 'qty_sisa' => 20.00, 'harga' => 283050.00, 'jumlah' => 5661000.00],
            ['kode_barang' => '1.1.12.01.04.0001.00026', 'nama' => 'Antibiotik Pernafasan Hewan Tylosin 20%', 'kategori_nama' => 'Obat', 'satuan' => 'Botol', 'qty_sisa' => 15.00, 'harga' => 227550.00, 'jumlah' => 3413250.00],
            ['kode_barang' => '1.1.12.01.04.0001.00027', 'nama' => 'Antibiotik sulfa', 'kategori_nama' => 'Obat', 'satuan' => 'Botol', 'qty_sisa' => 1.00, 'harga' => 175380.00, 'jumlah' => 175380.00],
            ['kode_barang' => '1.1.12.01.04.0001.00032', 'nama' => 'Antipiretik', 'kategori_nama' => 'Obat', 'satuan' => 'Botol', 'qty_sisa' => 78.00, 'harga' => 172050.00, 'jumlah' => 13419900.00],
            ['kode_barang' => '1.1.12.01.04.0001.00068', 'nama' => 'Erla SM (Salep antibiotik steril untuk mata)', 'kategori_nama' => 'Obat', 'satuan' => 'Tube', 'qty_sisa' => 93.00, 'harga' => 22200.00, 'jumlah' => 2064600.00],
            ['kode_barang' => '1.1.12.01.04.0001.00082', 'nama' => 'Intermectin Injeksi (Ukuran 50 ml)', 'kategori_nama' => 'Obat', 'satuan' => 'Botol', 'qty_sisa' => 14.00, 'harga' => 233100.00, 'jumlah' => 3263400.00],
            ['kode_barang' => '1.1.12.01.04.0001.00104', 'nama' => 'Muctisan (50 cc)', 'kategori_nama' => 'Obat', 'satuan' => 'Botol', 'qty_sisa' => 1.00, 'harga' => 860000.00, 'jumlah' => 860000.00],
            ['kode_barang' => '1.1.12.01.04.0001.00128', 'nama' => 'Oxytetracycline LA', 'kategori_nama' => 'Obat', 'satuan' => 'Botol', 'qty_sisa' => 38.00, 'harga' => 355200.00, 'jumlah' => 13497600.00],
            ['kode_barang' => '1.1.12.01.04.0001.00137', 'nama' => 'Penicilin LA injeksi', 'kategori_nama' => 'Obat', 'satuan' => 'Botol', 'qty_sisa' => 6.00, 'harga' => 283050.00, 'jumlah' => 1698300.00],
            ['kode_barang' => '1.1.12.01.04.0001.00158', 'nama' => 'Semen beku/straw Vaccin', 'kategori_nama' => 'Obat', 'satuan' => 'Dosis', 'qty_sisa' => 2010.00, 'harga' => 10000.00, 'jumlah' => 20100000.00],
            ['kode_barang' => '1.1.12.01.04.0001.00187', 'nama' => 'Verm O (V Bolus)', 'kategori_nama' => 'Obat', 'satuan' => 'Botol', 'qty_sisa' => 27.00, 'harga' => 377400.00, 'jumlah' => 10189800.00],
            ['kode_barang' => '1.1.12.01.04.0001.00190', 'nama' => 'Vitamin ADE', 'kategori_nama' => 'Obat', 'satuan' => 'Botol', 'qty_sisa' => 64.00, 'harga' => 177600.00, 'jumlah' => 11366400.00],
            ['kode_barang' => '1.1.12.01.04.0001.00191', 'nama' => 'Vitamin B compleks', 'kategori_nama' => 'Obat', 'satuan' => 'Botol', 'qty_sisa' => 98.00, 'harga' => 94350.00, 'jumlah' => 9246300.00],
            ['kode_barang' => '1.1.12.01.04.0001.00192', 'nama' => 'Vitamin B1', 'kategori_nama' => 'Obat', 'satuan' => 'Botol', 'qty_sisa' => 57.00, 'harga' => 88800.00, 'jumlah' => 5061600.00],
            ['kode_barang' => '1.1.12.01.04.0001.00196', 'nama' => 'Vitamin plus ATP', 'kategori_nama' => 'Obat', 'satuan' => 'Botol', 'qty_sisa' => 32.00, 'harga' => 227550.00, 'jumlah' => 7281600.00],
            // Kategori: Obat-obatan Lainnya
            ['kode_barang' => '1.1.12.01.04.0002.00020', 'nama' => 'Multivitamin', 'kategori_nama' => 'Obat-obatan Lainnya', 'satuan' => 'Botol', 'qty_sisa' => 41.00, 'harga' => 29970.00, 'jumlah' => 1228770.00],
            ['kode_barang' => '1.1.12.01.04.0002.00027', 'nama' => 'Pestisida', 'kategori_nama' => 'Obat-obatan Lainnya', 'satuan' => 'Botol', 'qty_sisa' => 13.00, 'harga' => 134850.00, 'jumlah' => 1753050.00],
            // Kategori: Natura dan Pakan Lainnya
            ['kode_barang' => '1.1.12.01.07.0003.00011', 'nama' => 'Air mineral gelas', 'kategori_nama' => 'Natura dan Pakan Lainnya', 'satuan' => 'Dos', 'qty_sisa' => 9.00, 'harga' => 43000.00, 'jumlah' => 387000.00],
            ['kode_barang' => '1.1.12.01.07.0003.00027', 'nama' => 'Air Minum Botol 330ML', 'kategori_nama' => 'Natura dan Pakan Lainnya', 'satuan' => 'Dos', 'qty_sisa' => 2.00, 'harga' => 48000.00, 'jumlah' => 96000.00],
        ];

        $dataToInsert = [];
        foreach ($barang as $item) {
            $dataToInsert[] = [
                'kode_barang' => $item['kode_barang'],
                'nama' => $item['nama'],
                'kategori_id' => $kategoriMap[$item['kategori_nama']],
                'satuan' => $item['satuan'],
                'qty_sisa' => $item['qty_sisa'],
                'harga' => $item['harga'],
                'jumlah' => $item['jumlah'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('master_barangs')->insert($dataToInsert);
    }
}
