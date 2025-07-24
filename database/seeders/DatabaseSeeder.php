<?php

namespace Database\Seeders;

use App\Models\Bidang;
use App\Models\User;
use App\Models\Jabatan;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Data Seeder Department
        $bidangs = [
            [
                'nama' => 'admin',
                'status' => 'aktif'
            ],
            [
                'nama' => 'Peternakan',
                'status' => 'aktif'
            ],
            [
                'nama' => 'Tanaman Pangan',
                'status' => 'aktif'
            ],
            [
                'nama' => 'Perikanan',
                'status' => 'aktif'
            ],
            [
                'nama' => 'Ketahanan Pangan',
                'status' => 'aktif'
            ],
            [
                'nama' => 'Sekretariat',
                'status' => 'aktif'
            ],
            [
                'nama' => 'PUSKEWAN DAN RPH',
                'status' => 'aktif'
            ],
        ];

        foreach ($bidangs as $bidang) {
            Bidang::create($bidang);
        }

        // Data Seeder Jabatan
        $jabatans = [
            [
                'nama' => 'super admin',
                'status' => 'aktif'
            ],
            [
                'nama' => 'admin',
                'status' => 'aktif'
            ],
            [
                'nama' => 'User',
                'status' => 'aktif'
            ],
        ];

        foreach ($jabatans as $jabatan) {
            Jabatan::create($jabatan);
        }

        // Data Seeder User
        $users = [
            // administrator
            [
                'jabatan_id' => 2,
                'bidang_id' => 1,
                'nip' => 111111111,
                'nama' => 'Administrator',
                'email' => 'administrator@gmail.com',
                'password' => Hash::make('password'),
            ],
            // peternakan
            [
                'jabatan_id' => 3,
                'bidang_id' => 2,
                'nip' => 222222222,
                'nama' => 'peternakan',
                'email' => 'peternakan@gmail.com',
                'password' => Hash::make('password'),
            ]

        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
