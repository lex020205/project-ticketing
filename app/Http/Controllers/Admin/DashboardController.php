<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluhan;
use App\Models\Ticket;

// Modul 14 - Finalisasi Dashboard Statistik per Role
// Ringkas: ringkasan statistik dashboard admin.
class DashboardController extends Controller
{
    public function index()
    {
        $totalKeluhan = Keluhan::count();
        $keluhanBaru = Keluhan::where('status_keluhan', 'baru')->count();
        $keluhanValid = Keluhan::where('status_keluhan', 'valid')->count();

        $ticketAktif = Ticket::whereNotIn('status_ticket', ['ditutup', 'dibatalkan'])->count();
        $ticketMenungguPenugasan = Ticket::where('status_ticket', 'menunggu_penugasan')->count();
        $ticketMenungguVerifikasi = Ticket::where('status_ticket', 'menunggu_verifikasi')->count();
        $ticketDitutup = Ticket::where('status_ticket', 'ditutup')->count();
        $ticketDarurat = Ticket::where('prioritas', 'darurat')
            ->whereNotIn('status_ticket', ['ditutup', 'dibatalkan'])
            ->count();

        $keluhanTerbaru = Keluhan::with('kategori')
            ->orderByDesc('tanggal_keluhan')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $ticketTerbaru = Ticket::with(['keluhan', 'kategori', 'teknisi'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalKeluhan',
            'keluhanBaru',
            'keluhanValid',
            'ticketAktif',
            'ticketMenungguPenugasan',
            'ticketMenungguVerifikasi',
            'ticketDitutup',
            'ticketDarurat',
            'keluhanTerbaru',
            'ticketTerbaru'
        ));
    }
}
