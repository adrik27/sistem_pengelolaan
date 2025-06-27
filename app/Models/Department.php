<?php

namespace App\Models;

use App\Models\User;
use App\Models\SaldoAwal;
use App\Models\Transaksi;
use App\Models\StokPersediaanBidang;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{

    protected $table = 'departments';
    protected $guarded = ['id'];
    public function User()
    {
        return $this->hasMany(User::class);
    }
    public function SaldoAwal()
    {
        return $this->hasMany(SaldoAwal::class);
    }

    public function Transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function StokPersediaan()
    {
        return $this->hasMany(StokPersediaanBidang::class);
    }
}
