<?php

// Modul 2 - Admin Keluhan
// Ringkas: data keluhan yang menjadi dasar ticket.

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keluhan extends Model
{
    protected $fillable = [
        'kode_keluhan',
        'nama_pelapor',
        'jenis_pelapor',
        'kontak_pelapor',
        'kategori_id',
        'lokasi_keluhan',
        'detail_lokasi',
        'deskripsi_keluhan',
        'prioritas_awal',
        'status_keluhan',
        'created_by',
        'tanggal_keluhan',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriMasalah::class, 'kategori_id');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function ticket()
    {
        return $this->hasOne(Ticket::class);
    }
}