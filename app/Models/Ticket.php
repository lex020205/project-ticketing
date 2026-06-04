<?php

// Modul 3-11 - Siklus Ticket (Admin, Teknisi, SPV)
// Ringkas: data inti ticket dan relasi prosesnya.

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'kode_ticket',
        'keluhan_id',
        'kategori_id',
        'teknisi_id',
        'assigned_by',
        'verified_by',
        'prioritas',
        'status_ticket',
        'tanggal_ditugaskan',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_ditutup',
    ];

    public function keluhan()
    {
        return $this->belongsTo(Keluhan::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriMasalah::class, 'kategori_id');
    }

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function progress()
    {
        return $this->hasMany(TicketProgress::class);
    }

    public function lampiran()
    {
        return $this->hasMany(TicketLampiran::class);
    }

    public function eskalasi()
    {
        return $this->hasMany(TicketEskalasi::class);
    }

    public function verifikasi()
    {
        return $this->hasMany(TicketVerifikasi::class);
    }
}