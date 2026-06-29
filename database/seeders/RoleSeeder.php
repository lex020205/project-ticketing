<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

// Modul 15 - Testing, Quality Check, dan Demo Data
// Ringkas: data demo role dasar.
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'nama_role' => 'Admin',
                'deskripsi' => 'Menginput keluhan dan membuat ticket',
            ],
            [
                'nama_role' => 'SPV',
                'deskripsi' => 'Mengawasi ticket, eskalasi, user, dan laporan',
            ],
            [
                'nama_role' => 'Teknisi',
                'deskripsi' => 'Mengerjakan ticket dan mengupdate progress',
            ],
            [
                'nama_role' => 'Super Admin',
                'deskripsi' => 'Hak akses tertinggi, dapat mengakses seluruh fitur dan fitur pengawasan khusus',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['nama_role' => $role['nama_role']],
                ['deskripsi' => $role['deskripsi']]
            );
        }
    }
}