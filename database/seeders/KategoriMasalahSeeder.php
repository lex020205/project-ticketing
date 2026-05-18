<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriMasalah;

class KategoriMasalahSeeder extends Seeder
{
    public function run(): void
    {
        KategoriMasalah::insert([
            [
                'nama_kategori' => 'Hardware',
                'deskripsi' => 'Masalah perangkat keras seperti PC, keyboard, mouse, printer',
                'status_kategori' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Software',
                'deskripsi' => 'Masalah aplikasi, sistem operasi, driver, instalasi software',
                'status_kategori' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Jaringan',
                'deskripsi' => 'Masalah internet, LAN, WiFi, kabel jaringan',
                'status_kategori' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Perangkat Kelas',
                'deskripsi' => 'Masalah proyektor, speaker, HDMI, display kelas',
                'status_kategori' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Perangkat Acara',
                'deskripsi' => 'Bantuan teknis acara, operator, sound, presentasi',
                'status_kategori' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Maintenance',
                'deskripsi' => 'Pengecekan, perawatan, pembersihan, update perangkat',
                'status_kategori' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Bantuan Teknis',
                'deskripsi' => 'Permintaan bantuan teknis umum dari dosen, mahasiswa, atau staff',
                'status_kategori' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Lainnya',
                'deskripsi' => 'Kategori lain di luar kategori utama',
                'status_kategori' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}