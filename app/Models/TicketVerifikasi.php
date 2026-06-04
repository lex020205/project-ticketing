<?php

// Modul 8 - Admin/SPV Verifikasi Ticket Selesai
// Ringkas: hasil verifikasi penyelesaian ticket.

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketVerifikasi extends Model
{
    protected $table = 'ticket_verifikasi';

    protected $fillable = [
        'ticket_id',
        'verified_by',
        'hasil_verifikasi',
        'catatan_verifikasi',
        'tanggal_verifikasi',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}