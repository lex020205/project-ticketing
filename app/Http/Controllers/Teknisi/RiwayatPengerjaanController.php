<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\TicketProgress;

// Modul 15 - Teknisi Riwayat Pengerjaan
// Ringkas: menampilkan riwayat progress teknisi.
class RiwayatPengerjaanController extends Controller
{
    public function index()
    {
        $progressList = TicketProgress::with(['ticket.keluhan', 'ticket.kategori'])
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        return view('teknisi.riwayat-pengerjaan.index', compact('progressList'));
    }
}
