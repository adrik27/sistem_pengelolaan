<?php

namespace App\Models;

use App\Models\Penerimaan;
use App\Models\Pengeluaran;
use App\Models\SaldoAwal;
use App\Models\StokPersediaanBidang;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{

    protected $table = 'bidangs';
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

    public function Pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class);
    }

    public function Penerimaan()
    {
        return $this->hasMany(Penerimaan::class);
    }
}
