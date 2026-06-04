<?php

// Modul 6/7 - Eskalasi Ticket
// Ringkas: pengajuan dan keputusan eskalasi.

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketEskalasi extends Model
{
    protected $table = 'ticket_eskalasi';

    protected $fillable = [
        'ticket_id',
        'diajukan_oleh',
        'jenis_eskalasi',
        'alasan',
        'status_eskalasi',
        'diputuskan_oleh',
        'keputusan',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function pengaju()
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    public function pemutus()
    {
        return $this->belongsTo(User::class, 'diputuskan_oleh');
    }
}