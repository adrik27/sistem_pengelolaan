<?php

namespace App\Models;

use App\Models\TransaksiMasuk;
use Illuminate\Database\Eloquent\Model;

class DataMaster extends Model
{
    protected $table = 'data_masters';
    protected $guarded = ['id'];


    public function TransaksiMasuk()
    {
        return $this->hasMany(TransaksiMasuk::class);
    }
}
