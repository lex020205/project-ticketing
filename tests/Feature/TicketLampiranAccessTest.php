<?php

namespace Tests\Feature;

use App\Models\KategoriMasalah;
use App\Models\Keluhan;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketLampiran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TicketLampiranAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_spv_can_preview_ticket_lampiran(): void
    {
        Storage::fake('public');
        $lampiran = $this->makeLampiran();
        $spv = $this->makeUser('SPV');

        $response = $this->actingAs($spv)->get(route('ticket-lampiran.show', $lampiran));

        $response->assertOk();
    }

    public function test_super_admin_can_preview_ticket_lampiran(): void
    {
        Storage::fake('public');
        $lampiran = $this->makeLampiran();
        $superAdmin = $this->makeUser('Super Admin');

        $response = $this->actingAs($superAdmin)->get(route('ticket-lampiran.show', $lampiran));

        $response->assertOk();
    }

    public function test_other_teknisi_cannot_preview_ticket_lampiran(): void
    {
        Storage::fake('public');
        $lampiran = $this->makeLampiran();
        $otherTeknisi = $this->makeUser('Teknisi');

        $response = $this->actingAs($otherTeknisi)->get(route('ticket-lampiran.show', $lampiran));

        $response->assertForbidden();
    }

    private function makeLampiran(): TicketLampiran
    {
        $teknisi = $this->makeUser('Teknisi');
        $admin = $this->makeUser('Admin');
        $kategori = KategoriMasalah::create([
            'nama_kategori' => 'Jaringan',
            'status_kategori' => 'aktif',
        ]);

        $keluhan = Keluhan::create([
            'kode_keluhan' => 'KL-001',
            'nama_pelapor' => 'Pelapor',
            'jenis_pelapor' => 'staff',
            'kontak_pelapor' => '0812345678',
            'kategori_id' => $kategori->id,
            'lokasi_keluhan' => 'Lab',
            'deskripsi_keluhan' => 'Koneksi bermasalah.',
            'prioritas_awal' => 'sedang',
            'status_keluhan' => 'menjadi_ticket',
            'created_by' => $admin->id,
            'tanggal_keluhan' => now()->toDateString(),
        ]);

        $ticket = Ticket::create([
            'kode_ticket' => 'TCK-001',
            'keluhan_id' => $keluhan->id,
            'kategori_id' => $kategori->id,
            'teknisi_id' => $teknisi->id,
            'assigned_by' => $admin->id,
            'prioritas' => 'sedang',
            'status_ticket' => 'menunggu_verifikasi',
        ]);

        Storage::disk('public')->put('ticket-lampiran/bukti.png', 'fake-image');

        return TicketLampiran::create([
            'ticket_id' => $ticket->id,
            'uploaded_by' => $teknisi->id,
            'nama_file' => 'bukti.png',
            'path_file' => 'ticket-lampiran/bukti.png',
            'keterangan' => 'Foto hasil pengerjaan.',
        ]);
    }

    private function makeUser(string $roleName): User
    {
        $role = Role::firstOrCreate(
            ['nama_role' => $roleName],
            ['deskripsi' => $roleName]
        );

        return User::factory()->create([
            'role_id' => $role->id,
            'status_user' => 'aktif',
        ]);
    }
}
