<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketLampiran extends Model
{
    protected $table = 'ticket_lampiran';

    protected $fillable = [
        'ticket_id',
        'uploaded_by',
        'nama_file',
        'path_file',
        'keterangan',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}