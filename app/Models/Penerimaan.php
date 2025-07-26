<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penerimaan extends Model
{
    use HasFactory;

    /**
     * Tentukan nama tabel jika berbeda dari nama model plural.
     */
    protected $table = 'penerimaan';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'bidang_id',
        'tanggal_pembukuan',
        'supplier',
        'no_faktur',
        'status_penerimaan',
        'no_nota',
        'no_terima',
        'sumber_dana',
        'kode_barang',
        'nama_barang',
        'qty',
        'harga_satuan',
        'keterangan',
    ];

    /**
     * Casting tipe data otomatis.
     */
    protected $casts = [
        'tanggal_pembukuan' => 'date',
        'qty' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
    ];
}
