<?php

namespace App\Models;

use App\Models\Department;
use Illuminate\Database\Eloquent\Model;

class SaldoAwal extends Model
{
    protected $table = 'saldo_awals';
    protected $guarded = ['id'];

    public function Department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
}
