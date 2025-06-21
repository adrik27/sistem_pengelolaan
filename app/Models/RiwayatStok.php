<?php

namespace App\Models;

use App\Models\DataMaster;
use Illuminate\Database\Eloquent\Model;

class RiwayatStok extends Model
{
    protected $table = 'riwayat_stoks';
    protected $guarded = ['id'];

    public function DataMaster()
    {
        return $this->belongsTo(DataMaster::class, 'kode_barang', 'kode_barang');
    }
}
