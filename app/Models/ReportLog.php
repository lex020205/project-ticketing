<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportLog extends Model
{
    protected $fillable = [
        'user_id',
        'periode_awal',
        'periode_akhir',
        'nama_file',
        'email_tujuan',
        'status',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'periode_awal' => 'date',
            'periode_akhir' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
