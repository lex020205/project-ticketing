<?php

namespace Database\Seeders;

use App\Models\KategoriMasalah;
use App\Models\Keluhan;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketEskalasi;
use App\Models\TicketProgress;
use App\Models\TicketVerifikasi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DemoTicketSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('nama_role', 'Admin')->first();
        $spvRole = Role::where('nama_role', 'SPV')->first();
        $teknisiRole = Role::where('nama_role', 'Teknisi')->first();

        if (!$adminRole || !$spvRole || !$teknisiRole) {
            return;
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@laboran.test'],
            [
                'name' => 'Admin Laboran',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'nomor_telepon' => '081111111111',
                'status_user' => 'aktif',
            ]
        );

        $spv = User::firstOrCreate(
            ['email' => 'spv@laboran.test'],
            [
                'name' => 'SPV Laboran',
                'password' => Hash::make('password'),
                'role_id' => $spvRole->id,
                'nomor_telepon' => '082222222222',
                'status_user' => 'aktif',
            ]
        );

        $teknisi = User::firstOrCreate(
            ['email' => 'teknisi@laboran.test'],
            [
                'name' => 'Teknisi Laboran',
                'password' => Hash::make('password'),
                'role_id' => $teknisiRole->id,
                'nomor_telepon' => '083333333333',
                'status_user' => 'aktif',
            ]
        );

        $kategoriMap = KategoriMasalah::whereIn('nama_kategori', [
            'Hardware',
            'Software',
            'Perangkat Kelas',
            'Perangkat Acara',
            'Jaringan',
            'Maintenance',
        ])->get()->keyBy('nama_kategori');

        $now = Carbon::now();

        $cases = [
            [
                'kode_keluhan' => 'KLH-DEMO-001',
                'kode_ticket' => 'TCK-DEMO-001',
                'nama_pelapor' => 'Pak Budi',
                'jenis_pelapor' => 'dosen',
                'kategori' => 'Hardware',
                'lokasi_keluhan' => 'Ruang Dosen',
                'detail_lokasi' => 'PC dosen nomor 03 dekat pintu',
                'deskripsi_keluhan' => 'PC tidak menyala saat akan digunakan mengajar',
                'prioritas' => 'tinggi',
                'status_ticket' => 'menunggu_penugasan',
                'teknisi_id' => null,
                'tanggal_keluhan' => $now->copy()->subDays(7),
            ],
            [
                'kode_keluhan' => 'KLH-DEMO-002',
                'kode_ticket' => 'TCK-DEMO-002',
                'nama_pelapor' => 'Sinta',
                'jenis_pelapor' => 'mahasiswa',
                'kategori' => 'Software',
                'lokasi_keluhan' => 'Meja Helpdesk Laboran',
                'detail_lokasi' => 'Laptop mahasiswa dibawa langsung',
                'deskripsi_keluhan' => 'Aplikasi database tidak bisa dibuka',
                'prioritas' => 'sedang',
                'status_ticket' => 'ditugaskan',
                'teknisi_id' => $teknisi->id,
                'tanggal_keluhan' => $now->copy()->subDays(6),
                'tanggal_ditugaskan' => $now->copy()->subDays(6)->addHours(2),
            ],
            [
                'kode_keluhan' => 'KLH-DEMO-003',
                'kode_ticket' => 'TCK-DEMO-003',
                'nama_pelapor' => 'Pak Andi',
                'jenis_pelapor' => 'dosen',
                'kategori' => 'Perangkat Kelas',
                'lokasi_keluhan' => 'Kelas B204',
                'detail_lokasi' => 'Proyektor depan kelas',
                'deskripsi_keluhan' => 'Proyektor tidak menampilkan gambar dari laptop',
                'prioritas' => 'tinggi',
                'status_ticket' => 'sedang_dikerjakan',
                'teknisi_id' => $teknisi->id,
                'tanggal_keluhan' => $now->copy()->subDays(5),
                'tanggal_ditugaskan' => $now->copy()->subDays(5)->addHours(2),
                'tanggal_mulai' => $now->copy()->subDays(5)->addHours(3),
                'progress' => [
                    'catatan' => 'Teknisi mulai mengecek kabel HDMI dan port proyektor.',
                    'status_sebelumnya' => 'ditugaskan',
                    'status_baru' => 'sedang_dikerjakan',
                ],
            ],
            [
                'kode_keluhan' => 'KLH-DEMO-004',
                'kode_ticket' => 'TCK-DEMO-004',
                'nama_pelapor' => 'Staff Akademik',
                'jenis_pelapor' => 'staff',
                'kategori' => 'Hardware',
                'lokasi_keluhan' => 'Ruang Akademik',
                'detail_lokasi' => 'Printer administrasi',
                'deskripsi_keluhan' => 'Printer tidak menarik kertas',
                'prioritas' => 'sedang',
                'status_ticket' => 'menunggu_alat',
                'teknisi_id' => $teknisi->id,
                'tanggal_keluhan' => $now->copy()->subDays(4),
                'tanggal_ditugaskan' => $now->copy()->subDays(4)->addHours(2),
                'tanggal_mulai' => $now->copy()->subDays(4)->addHours(3),
                'progress' => [
                    'catatan' => 'Roller printer rusak dan menunggu sparepart.',
                    'status_sebelumnya' => 'sedang_dikerjakan',
                    'status_baru' => 'menunggu_alat',
                ],
            ],
            [
                'kode_keluhan' => 'KLH-DEMO-005',
                'kode_ticket' => 'TCK-DEMO-005',
                'nama_pelapor' => 'Panitia Seminar',
                'jenis_pelapor' => 'panitia',
                'kategori' => 'Perangkat Acara',
                'lokasi_keluhan' => 'Aula Kampus',
                'detail_lokasi' => 'Meja operator acara',
                'deskripsi_keluhan' => 'Sound system tidak keluar suara saat gladi bersih',
                'prioritas' => 'darurat',
                'status_ticket' => 'eskalasi',
                'teknisi_id' => $teknisi->id,
                'tanggal_keluhan' => $now->copy()->subDays(3),
                'tanggal_ditugaskan' => $now->copy()->subDays(3)->addHours(1),
                'tanggal_mulai' => $now->copy()->subDays(3)->addHours(2),
                'eskalasi' => [
                    'jenis_eskalasi' => 'butuh_bantuan',
                    'alasan' => 'Membutuhkan bantuan teknisi lain untuk pengecekan sound system.',
                ],
            ],
            [
                'kode_keluhan' => 'KLH-DEMO-006',
                'kode_ticket' => 'TCK-DEMO-006',
                'nama_pelapor' => 'Bu Rina',
                'jenis_pelapor' => 'dosen',
                'kategori' => 'Jaringan',
                'lokasi_keluhan' => 'Lab Komputer 1',
                'detail_lokasi' => 'Area meja dosen',
                'deskripsi_keluhan' => 'Internet LAN tidak tersambung',
                'prioritas' => 'tinggi',
                'status_ticket' => 'menunggu_verifikasi',
                'teknisi_id' => $teknisi->id,
                'tanggal_keluhan' => $now->copy()->subDays(2),
                'tanggal_ditugaskan' => $now->copy()->subDays(2)->addHours(1),
                'tanggal_mulai' => $now->copy()->subDays(2)->addHours(2),
                'tanggal_selesai' => $now->copy()->subDays(2)->addHours(6),
                'progress' => [
                    'catatan' => 'Kabel LAN sudah diganti dan koneksi sudah normal.',
                    'status_sebelumnya' => 'sedang_dikerjakan',
                    'status_baru' => 'menunggu_verifikasi',
                ],
            ],
            [
                'kode_keluhan' => 'KLH-DEMO-007',
                'kode_ticket' => 'TCK-DEMO-007',
                'nama_pelapor' => 'Pak Joko',
                'jenis_pelapor' => 'dosen',
                'kategori' => 'Maintenance',
                'lokasi_keluhan' => 'Lab Komputer 2',
                'detail_lokasi' => 'Semua PC lab',
                'deskripsi_keluhan' => 'Maintenance rutin dan pembersihan perangkat',
                'prioritas' => 'rendah',
                'status_ticket' => 'ditutup',
                'teknisi_id' => $teknisi->id,
                'tanggal_keluhan' => $now->copy()->subDays(1),
                'tanggal_ditugaskan' => $now->copy()->subDays(1)->addHours(1),
                'tanggal_mulai' => $now->copy()->subDays(1)->addHours(2),
                'tanggal_selesai' => $now->copy()->subDays(1)->addHours(6),
                'tanggal_ditutup' => $now->copy()->subDays(1)->addHours(8),
                'progress' => [
                    'catatan' => 'Maintenance rutin selesai dan perangkat bersih.',
                    'status_sebelumnya' => 'menunggu_verifikasi',
                    'status_baru' => 'ditutup',
                ],
                'verifikasi' => [
                    'hasil_verifikasi' => 'diterima',
                    'catatan_verifikasi' => 'Pekerjaan selesai sesuai laporan.',
                ],
            ],
        ];

        foreach ($cases as $case) {
            $kategori = $kategoriMap[$case['kategori']] ?? null;

            if (!$kategori) {
                continue;
            }

            $keluhan = Keluhan::updateOrCreate(
                ['kode_keluhan' => $case['kode_keluhan']],
                [
                    'nama_pelapor' => $case['nama_pelapor'],
                    'jenis_pelapor' => $case['jenis_pelapor'],
                    'kontak_pelapor' => null,
                    'kategori_id' => $kategori->id,
                    'lokasi_keluhan' => $case['lokasi_keluhan'],
                    'detail_lokasi' => $case['detail_lokasi'],
                    'deskripsi_keluhan' => $case['deskripsi_keluhan'],
                    'prioritas_awal' => $case['prioritas'],
                    'status_keluhan' => 'menjadi_ticket',
                    'created_by' => $admin->id,
                    'tanggal_keluhan' => $case['tanggal_keluhan']->format('Y-m-d'),
                ]
            );

            $ticket = Ticket::updateOrCreate(
                ['kode_ticket' => $case['kode_ticket']],
                [
                    'keluhan_id' => $keluhan->id,
                    'kategori_id' => $kategori->id,
                    'teknisi_id' => $case['teknisi_id'],
                    'assigned_by' => $admin->id,
                    'verified_by' => $case['status_ticket'] === 'ditutup' ? $spv->id : null,
                    'prioritas' => $case['prioritas'],
                    'status_ticket' => $case['status_ticket'],
                    'tanggal_ditugaskan' => $case['tanggal_ditugaskan'] ?? null,
                    'tanggal_mulai' => $case['tanggal_mulai'] ?? null,
                    'tanggal_selesai' => $case['tanggal_selesai'] ?? null,
                    'tanggal_ditutup' => $case['tanggal_ditutup'] ?? null,
                ]
            );

            if (!empty($case['progress'])) {
                TicketProgress::updateOrCreate(
                    [
                        'ticket_id' => $ticket->id,
                        'catatan' => $case['progress']['catatan'],
                    ],
                    [
                        'user_id' => $teknisi->id,
                        'status_sebelumnya' => $case['progress']['status_sebelumnya'],
                        'status_baru' => $case['progress']['status_baru'],
                    ]
                );
            }

            if (!empty($case['eskalasi'])) {
                TicketEskalasi::updateOrCreate(
                    [
                        'ticket_id' => $ticket->id,
                        'status_eskalasi' => 'menunggu',
                    ],
                    [
                        'diajukan_oleh' => $teknisi->id,
                        'jenis_eskalasi' => $case['eskalasi']['jenis_eskalasi'],
                        'alasan' => $case['eskalasi']['alasan'],
                        'diputuskan_oleh' => null,
                        'keputusan' => null,
                    ]
                );
            }

            if (!empty($case['verifikasi'])) {
                TicketVerifikasi::updateOrCreate(
                    ['ticket_id' => $ticket->id],
                    [
                        'verified_by' => $spv->id,
                        'hasil_verifikasi' => $case['verifikasi']['hasil_verifikasi'],
                        'catatan_verifikasi' => $case['verifikasi']['catatan_verifikasi'],
                        'tanggal_verifikasi' => $case['tanggal_ditutup'] ?? $now,
                    ]
                );
            }
        }
    }
}
