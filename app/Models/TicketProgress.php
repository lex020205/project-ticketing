<?php

// Modul 4/8/9/10 - Progress Ticket
// Ringkas: riwayat status dan catatan pekerjaan.

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketProgress extends Model
{
    protected $table = 'ticket_progress';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'catatan',
        'status_sebelumnya',
        'status_baru',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}