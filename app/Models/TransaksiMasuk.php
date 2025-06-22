<?php

namespace App\Models;

use App\Models\DataMaster;
use App\Models\Department;
use Illuminate\Database\Eloquent\Model;

class TransaksiMasuk extends Model
{
    protected $table = 'transaksi_masuks';
    protected $guarded = ['id'];

    public function Department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function DataMaster()
    {
        return $this->belongsTo(DataMaster::class, 'kode_barang', 'kode_barang')->with('RiwayatStok');
    }
}
