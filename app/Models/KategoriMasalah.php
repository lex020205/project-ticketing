<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriMasalah extends Model
{
    protected $table = 'kategori_masalah';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'status_kategori',
    ];

    public function keluhans()
    {
        return $this->hasMany(Keluhan::class, 'kategori_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'kategori_id');
    }
}