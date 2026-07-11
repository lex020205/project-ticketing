<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('periode_awal')->nullable();
            $table->date('periode_akhir')->nullable();
            $table->string('nama_file')->nullable();
            $table->string('email_tujuan')->nullable();
            $table->string('status')->default('berhasil');
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_logs');
    }
};
