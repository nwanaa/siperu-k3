<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        User::create([
            'name' => 'Admin Fakultas',
            'identifier' => 'ADMIN001',
            'email' => 'admin@siperu.com',
            'password' => Hash::make('password'),
            'role' => User::ADMIN,
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | PJ
        |--------------------------------------------------------------------------
        |
        | Setiap PJ menangani satu kelompok ruangan.
        |
        */

        User::create([
            'name' => 'PJ Informatika',
            'identifier' => 'PJINF001',
            'email' => 'pj.informatika@siperu.com',
            'password' => Hash::make('password'),
            'role' => User::PJ,
            'pj_group' => 'informatika',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'PJ Sipil',
            'identifier' => 'PJSIPIL001',
            'email' => 'pj.sipil@siperu.com',
            'password' => Hash::make('password'),
            'role' => User::PJ,
            'pj_group' => 'sipil',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'PJ PWK',
            'identifier' => 'PJPWK001',
            'email' => 'pj.pwk@siperu.com',
            'password' => Hash::make('password'),
            'role' => User::PJ,
            'pj_group' => 'pwk',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'PJ Laboratorium ICT',
            'identifier' => 'PJICT001',
            'email' => 'pj.ict@siperu.com',
            'password' => Hash::make('password'),
            'role' => User::PJ,
            'pj_group' => 'lab_ict',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'PJ Laboratorium Terpadu',
            'identifier' => 'PJUTBK001',
            'email' => 'pj.terpadu@siperu.com',
            'password' => Hash::make('password'),
            'role' => User::PJ,
            'pj_group' => 'lab_terpadu',
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | PEMINJAM
        |--------------------------------------------------------------------------
        */

        User::create([
            'name' => 'Dosen Pengampu',
            'identifier' => 'DOSEN001',
            'email' => 'dosen@siperu.com',
            'password' => Hash::make('password'),
            'role' => User::PEMINJAM,
            'category' => 'dosen',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Mahasiswa Peminjam',
            'identifier' => 'MHS001',
            'email' => 'mahasiswa@siperu.com',
            'password' => Hash::make('12345678'),
            'role' => User::PEMINJAM,
            'category' => 'mahasiswa',
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | RUANGAN
        |--------------------------------------------------------------------------
        |
        | HP 1-3   -> Informatika
        | HP 4-6   -> Sipil
        | HP 7-9   -> PWK
        | ICT 1-4  -> Laboratorium ICT
        | UTBK 1-2 -> Laboratorium Terpadu
        |
        | Kapasitas dan fasilitas belum ditentukan dalam spesifikasi,
        | sehingga data seed hanya menggunakan data yang sudah diketahui.
        |
        */

        $rooms = [
            [
                'name' => 'HP 1',
                'building' => null,
                'type' => 'kelas',
                'pj_group' => 'informatika',
                'capacity' => 1,
                'facilities' => [],
            ],
            [
                'name' => 'HP 2',
                'building' => null,
                'type' => 'kelas',
                'pj_group' => 'informatika',
                'capacity' => 1,
                'facilities' => [],
            ],
            [
                'name' => 'HP 3',
                'building' => null,
                'type' => 'kelas',
                'pj_group' => 'informatika',
                'capacity' => 1,
                'facilities' => [],
            ],

            [
                'name' => 'HP 4',
                'building' => null,
                'type' => 'kelas',
                'pj_group' => 'sipil',
                'capacity' => 1,
                'facilities' => [],
            ],
            [
                'name' => 'HP 5',
                'building' => null,
                'type' => 'kelas',
                'pj_group' => 'sipil',
                'capacity' => 1,
                'facilities' => [],
            ],
            [
                'name' => 'HP 6',
                'building' => null,
                'type' => 'kelas',
                'pj_group' => 'sipil',
                'capacity' => 1,
                'facilities' => [],
            ],

            [
                'name' => 'HP 7',
                'building' => null,
                'type' => 'kelas',
                'pj_group' => 'pwk',
                'capacity' => 1,
                'facilities' => [],
            ],
            [
                'name' => 'HP 8',
                'building' => null,
                'type' => 'kelas',
                'pj_group' => 'pwk',
                'capacity' => 1,
                'facilities' => [],
            ],
            [
                'name' => 'HP 9',
                'building' => null,
                'type' => 'kelas',
                'pj_group' => 'pwk',
                'capacity' => 1,
                'facilities' => [],
            ],

            [
                'name' => 'Lab ICT 1',
                'building' => null,
                'type' => 'laboratorium',
                'pj_group' => 'lab_ict',
                'capacity' => 1,
                'facilities' => [],
            ],
            [
                'name' => 'Lab ICT 2',
                'building' => null,
                'type' => 'laboratorium',
                'pj_group' => 'lab_ict',
                'capacity' => 1,
                'facilities' => [],
            ],
            [
                'name' => 'Lab ICT 3',
                'building' => null,
                'type' => 'laboratorium',
                'pj_group' => 'lab_ict',
                'capacity' => 1,
                'facilities' => [],
            ],
            [
                'name' => 'Lab ICT 4',
                'building' => null,
                'type' => 'laboratorium',
                'pj_group' => 'lab_ict',
                'capacity' => 1,
                'facilities' => [],
            ],

            [
                'name' => 'Lab UTBK 1',
                'building' => null,
                'type' => 'laboratorium',
                'pj_group' => 'lab_terpadu',
                'capacity' => 1,
                'facilities' => [],
            ],
            [
                'name' => 'Lab UTBK 2',
                'building' => null,
                'type' => 'laboratorium',
                'pj_group' => 'lab_terpadu',
                'capacity' => 1,
                'facilities' => [],
            ],
        ];

        foreach ($rooms as $room) {
            Room::create([
                ...$room,
                'status' => 'available',
                'is_active' => true,
            ]);
        }
    }
}