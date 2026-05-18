<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_verifikasi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ticket_id')
                ->constrained('tickets')
                ->cascadeOnDelete();

            $table->foreignId('verified_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->enum('hasil_verifikasi', ['diterima', 'dikembalikan']);
            $table->text('catatan_verifikasi')->nullable();
            $table->dateTime('tanggal_verifikasi');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_verifikasi');
    }
};