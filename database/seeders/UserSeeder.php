<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

// Modul 15 - Testing, Quality Check, dan Demo Data
// Ringkas: data demo user untuk tiap role.
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('nama_role', 'Admin')->first();
        $spvRole = Role::where('nama_role', 'SPV')->first();
        $teknisiRole = Role::where('nama_role', 'Teknisi')->first();

        User::create([
            'name' => 'Admin Laboran',
            'email' => 'admin@laboran.test',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'nomor_telepon' => '081111111111',
            'status_user' => 'aktif',
        ]);

        User::create([
            'name' => 'SPV Laboran',
            'email' => 'spv@laboran.test',
            'password' => Hash::make('password'),
            'role_id' => $spvRole->id,
            'nomor_telepon' => '082222222222',
            'status_user' => 'aktif',
        ]);

        User::create([
            'name' => 'Teknisi Laboran',
            'email' => 'teknisi@laboran.test',
            'password' => Hash::make('password'),
            'role_id' => $teknisiRole->id,
            'nomor_telepon' => '083333333333',
            'status_user' => 'aktif',
        ]);
    }
}