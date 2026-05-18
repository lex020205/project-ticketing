<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::insert([
            [
                'nama_role' => 'Admin',
                'deskripsi' => 'Menginput keluhan dan membuat ticket',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'SPV',
                'deskripsi' => 'Mengawasi ticket, eskalasi, user, dan laporan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'Teknisi',
                'deskripsi' => 'Mengerjakan ticket dan mengupdate progress',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}