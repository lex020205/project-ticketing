<?php

// Modul 3-11 - Siklus Ticket (Admin, Teknisi, SPV)
// Ringkas: tabel inti ticket.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('kode_ticket', 30)->unique();

            $table->foreignId('keluhan_id')
                ->unique()
                ->constrained('keluhans')
                ->cascadeOnDelete();

            $table->foreignId('kategori_id')
                ->constrained('kategori_masalah')
                ->restrictOnDelete();

            $table->foreignId('teknisi_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('assigned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi', 'darurat'])
                ->default('sedang');

            $table->enum('status_ticket', [
                'menunggu_penugasan',
                'ditugaskan',
                'sedang_dikerjakan',
                'menunggu_alat',
                'eskalasi',
                'menunggu_verifikasi',
                'ditutup',
                'dibatalkan'
            ])->default('menunggu_penugasan');

            $table->dateTime('tanggal_ditugaskan')->nullable();
            $table->dateTime('tanggal_mulai')->nullable();
            $table->dateTime('tanggal_selesai')->nullable();
            $table->dateTime('tanggal_ditutup')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};