<?php

// Modul 1/12 - Auth, Role Access, dan User Management
// Ringkas: menambah role, status, dan kontak user.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')
                ->nullable()
                ->after('id')
                ->constrained('roles')
                ->nullOnDelete();

            $table->string('nomor_telepon', 20)->nullable()->after('password');
            $table->enum('status_user', ['aktif', 'nonaktif'])->default('aktif')->after('nomor_telepon');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['role_id', 'nomor_telepon', 'status_user']);
        });
    }
};