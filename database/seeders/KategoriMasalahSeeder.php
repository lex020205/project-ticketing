<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriMasalah;

class KategoriMasalahSeeder extends Seeder
{
    public function run(): void
    {
        $kategoriList = [
            [
                'nama_kategori' => 'Hardware',
                'deskripsi' => 'Masalah perangkat keras seperti PC, keyboard, mouse, printer',
            ],
            [
                'nama_kategori' => 'Software',
                'deskripsi' => 'Masalah aplikasi, sistem operasi, driver, instalasi software',
            ],
            [
                'nama_kategori' => 'Jaringan',
                'deskripsi' => 'Masalah internet, LAN, WiFi, kabel jaringan',
            ],
            [
                'nama_kategori' => 'Perangkat Kelas',
                'deskripsi' => 'Masalah proyektor, speaker, HDMI, display kelas',
            ],
            [
                'nama_kategori' => 'Perangkat Acara',
                'deskripsi' => 'Bantuan teknis acara, operator, sound, presentasi',
            ],
            [
                'nama_kategori' => 'Maintenance',
                'deskripsi' => 'Pengecekan, perawatan, pembersihan, update perangkat',
            ],
            [
                'nama_kategori' => 'Bantuan Teknis',
                'deskripsi' => 'Permintaan bantuan teknis umum dari dosen, mahasiswa, atau staff',
            ],
            [
                'nama_kategori' => 'Lainnya',
                'deskripsi' => 'Kategori lain di luar kategori utama',
            ],
        ];

        foreach ($kategoriList as $kategori) {
            KategoriMasalah::updateOrCreate(
                ['nama_kategori' => $kategori['nama_kategori']],
                [
                    'deskripsi' => $kategori['deskripsi'],
                    'status_kategori' => 'aktif',
                ]
            );
        }
    }
}