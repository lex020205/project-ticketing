<?php

// Modul 1 - Auth, Role Access, dan Dashboard Awal
// Ringkas: master role untuk akses aplikasi.

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'nama_role',
        'deskripsi',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}