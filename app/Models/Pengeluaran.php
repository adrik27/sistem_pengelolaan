<?php

namespace App\Models;

use App\Models\Bidang;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = 'pengeluarans';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_pembukuan' => 'date',
        'qty' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
    ];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id', 'id');
    }
}
