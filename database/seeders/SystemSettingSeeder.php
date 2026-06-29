<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'nama_sistem',
                'value' => 'Technician Assist Ticket System',
                'description' => 'Nama sistem yang ditampilkan di aplikasi',
            ],
            [
                'key' => 'nama_organisasi',
                'value' => 'Laboran',
                'description' => 'Nama organisasi pemilik sistem',
            ],
            [
                'key' => 'batas_upload_mb',
                'value' => '2',
                'description' => 'Batas ukuran file upload dalam MB',
            ],
            [
                'key' => 'format_lampiran',
                'value' => 'jpg,jpeg,png,pdf',
                'description' => 'Format file lampiran yang diizinkan (pisahkan dengan koma)',
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'description' => 'Status maintenance mode aplikasi (0=nonaktif, 1=aktif)',
            ],
            [
                'key' => 'google_allowed_domain',
                'value' => '',
                'description' => 'Domain Google yang diizinkan untuk login (kosong = semua domain)',
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'description' => $setting['description'],
                ]
            );
        }
    }
}
