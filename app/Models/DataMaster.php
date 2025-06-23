<?php

namespace App\Models;

use App\Models\User;
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

    public function User()
    {
        return $this->belongsTo(User::class, 'pembuat_id', 'id');
    }
}
