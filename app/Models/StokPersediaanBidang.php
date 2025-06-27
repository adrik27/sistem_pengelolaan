<?php

namespace App\Models;

use App\Models\Department;
use App\Models\MasterBarang;
use Illuminate\Database\Eloquent\Model;

class StokPersediaanBidang extends Model
{
    protected $table = 'stok_persediaan_bidangs';
    protected $guarded = ['id'];

    public function MasterBarang()
    {
        return $this->belongsTo(MasterBarang::class, 'kode_barang', 'kode_barang');
    }

    public function Department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
}
