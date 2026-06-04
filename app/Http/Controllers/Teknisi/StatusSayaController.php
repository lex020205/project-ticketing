<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\Ticket;

// Modul 16 - Teknisi Status Saya
// Ringkas: ringkasan status ticket teknisi.
class StatusSayaController extends Controller
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

        $tickets = Ticket::with(['keluhan', 'kategori'])
            ->where('teknisi_id', $teknisiId)
            ->orderByDesc('tanggal_ditugaskan')
            ->orderByDesc('id')
            ->get();

        return view('teknisi.status-saya.index', compact(
            'totalTicket',
            'ticketDitugaskan',
            'ticketSedangDikerjakan',
            'ticketMenungguAlat',
            'ticketEskalasi',
            'ticketMenungguVerifikasi',
            'ticketDitutup',
            'ticketDarurat',
            'tickets'
        ));
    }
}
