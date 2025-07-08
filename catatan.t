##### documentasi maatwebsite/excel = https://docs.laravel-excel.com/3.1/getting-started/

##### untuk install export maatwebsite/excel
 1. composer require maatwebsite/excel
 2. php artisan make:export namafile --model=namamodel //untuk membuat export file

##### jika ada error 
1. composer remove maatwebsite/excel
2. lalu install lagi

##### pastikan setting di php.ini 
1. ;extension=gd aktifkan tanpa tanda ";" 

##### export pdf rencana akan menggunakan = https://github.com/barryvdh/laravel-snappy

##### jika ingin seeder file tertentu php artisan db:seed --class=nama seeder tanpa blade



##### REVISI #####
# 1. jenis_transaksi rubah (masuk, keluar) => clear
# 2. status rubah (pending, selesai, tolak) => clear
# 3. tambah tabel stok_persediaan_bidang => clear

##### PROSES RIWAYAT TRANSAKSI #####
# 1. tmpil data transaksi masuk yang statusnya selesai saja.

##### PROSES TRANSAKSI MASUK #####
# 1. saat create masuk tidak mau masuk ke db => clear
# 2. saat verifikasi status rubah menjadi selesai dan barang yang selesai bisa digunakan di transaksi keluar.
# 3. saat verifikasi selesai, maka stok di tabel master barang (admin) akan berkurang dan saldo awal (admin) akan berkurang. Namun barang juga akan masuk ke tabel stok_persediaan_barang (user) dimana stok barang tersebut akan bertambah => clear

##### PROSES TRANSAKSI KELUAR #####
# 1. tidak perlu verifikasi ke admin
# 2. Saat menambah transaksi keluar stok barang di tabel stok_persediaan_bidang berdasarkan bidangnya akan berkurang. dan di tabel transaksi jenis_transaksi = keluar dan statusnya langsung selesai. 


##### MASTER BARANG #####
# 1. Bisa edit qty pada ta  nggal tertentu (sementara aktif terus)

##### MENU BARU #####
# 1. Tambah menu stock opname
# 2. Ada tombol ambil data stock akhir, saat di tekan akan mengambil data barang yang di insert di tabel stock opname
