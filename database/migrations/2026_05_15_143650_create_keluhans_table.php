<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keluhans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_keluhan', 30)->unique();

            $table->string('nama_pelapor', 100);
            $table->enum('jenis_pelapor', ['dosen', 'mahasiswa', 'staff', 'panitia', 'lainnya']);
            $table->string('kontak_pelapor', 50)->nullable();

            $table->foreignId('kategori_id')
                ->constrained('kategori_masalah')
                ->restrictOnDelete();

            $table->string('lokasi_keluhan', 150)->nullable();
            $table->text('detail_lokasi')->nullable();
            $table->text('deskripsi_keluhan');

            $table->enum('prioritas_awal', ['rendah', 'sedang', 'tinggi', 'darurat'])->default('sedang');
            $table->enum('status_keluhan', ['baru', 'valid', 'tidak_valid', 'menjadi_ticket'])->default('baru');

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->date('tanggal_keluhan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keluhans');
    }
};