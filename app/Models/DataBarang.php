<?php

namespace App\Models;

use App\Models\Pengeluaran;
use Illuminate\Database\Eloquent\Model;

class DataBarang extends Model
{
    //
    protected $table = 'data_barang';
    protected $guarded = ['id'];

    public function barang()
    {
        return $this->hasMany(Pengeluaran::class);
    }
}
