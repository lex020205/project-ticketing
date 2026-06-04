<?php

// Modul 6/7 - Eskalasi Ticket
// Ringkas: tabel pengajuan dan keputusan eskalasi.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_eskalasi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ticket_id')
                ->constrained('tickets')
                ->cascadeOnDelete();

            $table->foreignId('diajukan_oleh')
                ->constrained('users')
                ->restrictOnDelete();

            $table->enum('jenis_eskalasi', [
                'butuh_bantuan',
                'butuh_alat',
                'dialihkan',
                'keputusan_spv'
            ]);

            $table->text('alasan');

            $table->enum('status_eskalasi', [
                'menunggu',
                'disetujui',
                'ditolak',
                'selesai'
            ])->default('menunggu');

            $table->foreignId('diputuskan_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('keputusan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_eskalasi');
    }
};