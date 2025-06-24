<?php

namespace App\Models;

use App\Models\User;
use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Model;

class DataMaster extends Model
{
    protected $table = 'data_masters';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'tgl_buat' => 'datetime',
        ];
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
