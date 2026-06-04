<?php

namespace App\Http\Controllers\Spv;

use App\Http\Controllers\Controller;
use App\Models\KategoriMasalah;
use App\Models\Ticket;
use App\Models\TicketEskalasi;
use App\Models\User;

// Modul 14 - Finalisasi Dashboard Statistik per Role
// Ringkas: ringkasan statistik dashboard SPV.
class DashboardController extends Controller
{
    public function index()
    {
        $totalTicket = Ticket::count();
        $ticketAktif = Ticket::whereNotIn('status_ticket', ['ditutup', 'dibatalkan'])->count();
        $ticketDarurat = Ticket::where('prioritas', 'darurat')
            ->whereNotIn('status_ticket', ['ditutup', 'dibatalkan'])
            ->count();
        $ticketEskalasi = Ticket::where('status_ticket', 'eskalasi')->count();
        $eskalasiMenunggu = TicketEskalasi::where('status_eskalasi', 'menunggu')->count();
        $menungguVerifikasi = Ticket::where('status_ticket', 'menunggu_verifikasi')->count();
        $ticketDitutup = Ticket::where('status_ticket', 'ditutup')->count();

        $teknisiAktif = User::where('status_user', 'aktif')
            ->whereHas('role', function ($query) {
                $query->where('nama_role', 'Teknisi');
            })
            ->count();

        $userAktif = User::where('status_user', 'aktif')->count();
        $kategoriAktif = KategoriMasalah::where('status_kategori', 'aktif')->count();

        $urgentTickets = Ticket::with(['keluhan', 'kategori', 'teknisi'])
            ->whereIn('prioritas', ['darurat', 'tinggi'])
            ->whereNotIn('status_ticket', ['ditutup', 'dibatalkan'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $eskalasiMenungguList = TicketEskalasi::with(['ticket.keluhan', 'pengaju'])
            ->where('status_eskalasi', 'menunggu')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $teknisiWorkload = User::where('status_user', 'aktif')
            ->whereHas('role', function ($query) {
                $query->where('nama_role', 'Teknisi');
            })
            ->withCount([
                'ticketsSebagaiTeknisi as total_ticket',
                'ticketsSebagaiTeknisi as ticket_aktif' => function ($query) {
                    $query->whereNotIn('status_ticket', ['ditutup', 'dibatalkan']);
                },
                'ticketsSebagaiTeknisi as ticket_selesai' => function ($query) {
                    $query->where('status_ticket', 'ditutup');
                },
                'ticketsSebagaiTeknisi as ticket_eskalasi' => function ($query) {
                    $query->where('status_ticket', 'eskalasi');
                },
            ])
            ->orderBy('name')
            ->get();

        return view('spv.dashboard', compact(
            'totalTicket',
            'ticketAktif',
            'ticketDarurat',
            'ticketEskalasi',
            'eskalasiMenunggu',
            'menungguVerifikasi',
            'ticketDitutup',
            'teknisiAktif',
            'userAktif',
            'kategoriAktif',
            'urgentTickets',
            'eskalasiMenungguList',
            'teknisiWorkload'
        ));
    }
}
