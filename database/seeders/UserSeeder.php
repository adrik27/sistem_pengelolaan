<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::create([
        //     'jabatan_id' => 1,
        //     'bidang_id' => 1,
        //     'nama' => 'Admin',
        //     'nip' => '123456789',
        //     'email' => 'admin',
        //     'password' => Hash::make('password'),
        // ]);

        // User::create([
        //     'jabatan_id' => 3,
        //     'bidang_id' => 2,
        //     'nama' => 'Peternakan',
        //     'nip' => '987654321',
        //     'email' => 'peternakan',
        //     'password' => Hash::make('password'),
        // ]);

        User::create([
            'jabatan_id' => 2,
            'bidang_id' => 1,
            'nama' => 'administrator',
            'nip' => '111111111',
            'email' => 'administrator',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'jabatan_id' => 3,
            'bidang_id' => 3,
            'nama' => 'Tanaman Pangan',
            'nip' => '987654320',
            'email' => 'tanamanpangan',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'jabatan_id' => 3,
            'bidang_id' => 4,
            'nama' => 'Perikanan',
            'nip' => '987654322',
            'email' => 'perikanan',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'jabatan_id' => 3,
            'bidang_id' => 5,
            'nama' => 'Ketahanan Pangan',
            'nip' => '9876543213',
            'email' => 'ketahanpangan',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'jabatan_id' => 3,
            'bidang_id' => 6,
            'nama' => 'Sekretariat',
            'nip' => '987654324',
            'email' => 'sekretariat',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'jabatan_id' => 3,
            'bidang_id' => 7,
            'nama' => 'PUSKEWAN DAN RPH',
            'nip' => '987654325',
            'email' => 'puskewandanrph',
            'password' => Hash::make('password'),
        ]);
    }
}
