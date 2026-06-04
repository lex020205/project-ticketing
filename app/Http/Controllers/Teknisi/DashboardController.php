<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketProgress;

// Modul 14 - Finalisasi Dashboard Statistik per Role
// Ringkas: ringkasan statistik dashboard teknisi.
class DashboardController extends Controller
{
    public function index()
    {
        $teknisiId = auth()->id();

        $ticketQuery = Ticket::where('teknisi_id', $teknisiId);

        $totalTicket = (clone $ticketQuery)->count();
        $ticketDitugaskan = (clone $ticketQuery)->where('status_ticket', 'ditugaskan')->count();
        $ticketSedangDikerjakan = (clone $ticketQuery)->where('status_ticket', 'sedang_dikerjakan')->count();
        $ticketMenungguAlat = (clone $ticketQuery)->where('status_ticket', 'menunggu_alat')->count();
        $ticketEskalasi = (clone $ticketQuery)->where('status_ticket', 'eskalasi')->count();
        $ticketMenungguVerifikasi = (clone $ticketQuery)->where('status_ticket', 'menunggu_verifikasi')->count();
        $ticketDitutup = (clone $ticketQuery)->where('status_ticket', 'ditutup')->count();
        $ticketDarurat = (clone $ticketQuery)->where('prioritas', 'darurat')->count();

        $ticketTerbaru = Ticket::with(['keluhan', 'kategori'])
            ->where('teknisi_id', $teknisiId)
            ->orderByDesc('tanggal_ditugaskan')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $progressTerbaru = TicketProgress::with('ticket')
            ->where('user_id', $teknisiId)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('teknisi.dashboard', compact(
            'totalTicket',
            'ticketDitugaskan',
            'ticketSedangDikerjakan',
            'ticketMenungguAlat',
            'ticketEskalasi',
            'ticketMenungguVerifikasi',
            'ticketDitutup',
            'ticketDarurat',
            'ticketTerbaru',
            'progressTerbaru'
        ));
    }
}
