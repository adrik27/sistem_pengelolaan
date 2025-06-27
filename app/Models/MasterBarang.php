<?php

namespace App\Models;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Models\StokPersediaanBidang;
use Illuminate\Database\Eloquent\Model;

class MasterBarang extends Model
{
    //

    protected $table = 'master_barangs';
    protected $guarded = ['id'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function StokPersediaan()
    {
        return $this->hasMany(StokPersediaanBidang::class);
    }

    public function Transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'pembuat_id', 'id');
    }
}
