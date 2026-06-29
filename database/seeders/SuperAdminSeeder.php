<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::where('nama_role', 'Super Admin')->first();

        if (!$superAdminRole) {
            $superAdminRole = Role::create([
                'nama_role' => 'Super Admin',
                'deskripsi' => 'Hak akses tertinggi, dapat mengakses seluruh fitur dan fitur pengawasan khusus',
            ]);
        }

        User::updateOrCreate(
            ['email' => 'superadmin@laboran.test'],
            [
                'name' => 'Super Admin Laboran',
                'password' => 'SuperAdmin123!',
                'role_id' => $superAdminRole->id,
                'nomor_telepon' => '080000000000',
                'status_user' => 'aktif',
            ]
        );
    }
}
