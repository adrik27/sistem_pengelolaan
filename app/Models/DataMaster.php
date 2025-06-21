<?php

namespace App\Models;

use App\Models\RiwayatStok;
use App\Models\RiwayatTransaksi;
use Illuminate\Database\Eloquent\Model;

class DataMaster extends Model
{
    protected $table = 'data_masters';
    protected $guarded = ['id'];

    public function RiwayatTransaksi()
    {
        return $this->hasMany(RiwayatTransaksi::class);
    }
    public function RiwayatStok()
    {
        return $this->hasOne(RiwayatStok::class, 'kode_barang', 'kode_barang');
    }
}
