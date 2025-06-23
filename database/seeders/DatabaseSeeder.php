<?php

namespace Database\Seeders;

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
        $departments = [
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
                'nama' => 'UPTD', 
                'status' => 'aktif'
            ],
            [
                'nama' => 'PUSKEWAN DAN RPH', 
                'status' => 'aktif'
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }

        // Data Seeder Jabatan
        $jabatans = [
            [
                'nama' => 'administrator', 
                'status' => 'aktif'
            ],
            [
                'nama' => 'Pengurus Barang', 
                'status' => 'aktif'
            ],
            [
                'nama' => 'Pengguna Barang', 
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
                'jabatan_id' => 1,
                'department_id' => 1,
                'nip' => 111111111,
                'nama' => 'Administrator',
                'email' => 'administrator@gmail.com',
                'password' => Hash::make('password'),
            ],
            // pengurus barang
            [
                'jabatan_id' => 2,
                'department_id' => 2,
                'nip' => 222222222,
                'nama' => 'Pengurus Barang',
                'email' => 'pengurusbarang@gmail.com',
                'password' => Hash::make('password'),
            ],
            // pengguna barang
            [
                'jabatan_id' => 3,
                'department_id' => 2,
                'nip' => 333333333,
                'nama' => 'Pengguna Barang',
                'email' => 'penggunabarang@gmail.com',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
