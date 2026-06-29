<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// Modul 15 - Testing, Quality Check, dan Demo Data
// Ringkas: entry point seeding demo data.
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            KategoriMasalahSeeder::class,
            UserSeeder::class,
            SuperAdminSeeder::class,
            SystemSettingSeeder::class,
            DemoTicketSeeder::class,
        ]);
    }
}
