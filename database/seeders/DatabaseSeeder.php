<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User dengan 4 Role Berbeda
        $admin = User::create([
            'name' => 'Admin Fakultas',
            'identifier' => 'ADMIN001',
            'email' => 'admin@siperu.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $pjLab = User::create([
            'name' => 'Bapak PJ Lab',
            'identifier' => 'PJLAB001',
            'email' => 'pjlab@siperu.com',
            'password' => Hash::make('password'),
            'role' => 'pj_ruangan',
        ]);

       User::create([
    'name' => 'Dosen Pengampu', 'identifier' => 'DOSEN001', 'email' => 'dosen@siperu.com',
    'password' => Hash::make('password'), 'role' => 'peminjam', 'category' => 'dosen',
]);

User::create([
    'name' => 'Mahasiswa Peminjam', 'identifier' => 'MHS001', 'email' => 'mahasiswa@siperu.com',
    'password' => Hash::make('12345678'), 'role' => 'peminjam', 'category' => 'mahasiswa',
]);

        // 2. Buat Data Ruangan Kelas & Lab
        

        $pjKelas = User::create([
    'name' => 'Ibu PJ Kelas',
    'identifier' => 'PJKLS001',
    'email' => 'pjkelas@siperu.com',
    'password' => Hash::make('password'),
    'role' => 'pj_ruangan',
]);

$rooms = [
    ['Laboratorium Komputer 1', 'laboratorium', 40, $pjLab,   ['Komputer', 'Proyektor', 'AC']],
    ['Laboratorium Komputer 2', 'laboratorium', 40, $pjLab,   ['Komputer', 'Proyektor', 'AC']],
    ['Laboratorium Jaringan',   'laboratorium', 30, $pjLab,   ['Router', 'Switch', 'AC']],
    ['Ruang Kelas A1',          'kelas',        50, $pjKelas, ['Proyektor', 'Papan tulis', 'AC']],
    ['Ruang Seminar',           'kelas',        100, $pjKelas, ['Proyektor', 'Sound system', 'AC']],
];

foreach ($rooms as [$name, $type, $capacity, $pj, $facilities]) {
    Room::create([
        'name' => $name,
        'type' => $type,
        'capacity' => $capacity,
        'facilities' => $facilities,
        'pj_user_id' => $pj->id,
    ]);
}
    }
}