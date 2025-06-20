<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatans';
    protected $guarded = ['id'];

    public function User()
    {
        return $this->hasMany(User::class);
    }
}
