<?php

namespace App\Models;

use App\Models\User;
use App\Models\Department;
use App\Models\MasterBarang;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksis';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'tgl_transaksi' => 'datetime',
        ];
    }

    public function Department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function MasterBarang()
    {
        return $this->belongsTo(MasterBarang::class, 'kode_barang', 'kode_barang')->with('User');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'pembuat_id', 'id');
    }

    public function Verifikator()
    {
        return $this->belongsTo(User::class, 'verifikator_id', 'id');
    }
}
