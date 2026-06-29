<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Role;

use Illuminate\Support\Arr;

class DashboardController extends Controller
{
    public function index()
    {
        // User counts
        $totalUser = User::count();
        $totalAdmin = User::whereHas('role', fn($q)=> $q->where('nama_role','Admin'))->count();
        $totalSpv = User::whereHas('role', fn($q)=> $q->where('nama_role','SPV'))->count();
        $totalTeknisi = User::whereHas('role', fn($q)=> $q->where('nama_role','Teknisi'))->count();

        // Ticket counts
        $ticketQuery = Ticket::query();
        $totalTicket = (clone $ticketQuery)->count();
        $ticketAktif = (clone $ticketQuery)->whereNotIn('status_ticket', ['ditutup'])->count();
        $ticketDitugaskan = (clone $ticketQuery)->where('status_ticket', 'ditugaskan')->count();
        $ticketSedang = (clone $ticketQuery)->where('status_ticket', 'sedang_dikerjakan')->count();
        $ticketMenungguAlat = (clone $ticketQuery)->where('status_ticket', 'menunggu_alat')->count();
        $ticketMenungguVerifikasi = (clone $ticketQuery)->where('status_ticket', 'menunggu_verifikasi')->count();
        $ticketDitutup = (clone $ticketQuery)->where('status_ticket', 'ditutup')->count();
        $ticketEskalasi = (clone $ticketQuery)->where('status_ticket', 'eskalasi')->count();
        $ticketDarurat = (clone $ticketQuery)->where('prioritas', 'darurat')->count();

        // Recent tickets for monitoring table
        $tickets = Ticket::with(['keluhan', 'kategori', 'teknisi'])
            ->orderByDesc('id')
            ->limit(25)
            ->get();

        // Chart data: tickets per status and per month
        $ticketsPerStatus = Ticket::selectRaw('status_ticket, COUNT(*) as cnt')
            ->groupBy('status_ticket')
            ->pluck('cnt', 'status_ticket')
            ->toArray();

        $ticketsPerMonth = Ticket::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as cnt")
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('cnt', 'ym')
            ->toArray();

        $ticketsPerCategory = Ticket::selectRaw('kategori_id, COUNT(*) as cnt')
            ->groupBy('kategori_id')
            ->with('kategori')
            ->get()
            ->mapWithKeys(fn($t) => [$t->kategori?->nama_kategori ?? 'Uncategorized' => $t->cnt])
            ->toArray();

        return view('super-admin.dashboard', compact(
            'totalUser', 'totalAdmin', 'totalSpv', 'totalTeknisi',
            'totalTicket', 'ticketAktif', 'ticketDitugaskan', 'ticketSedang',
            'ticketMenungguAlat', 'ticketMenungguVerifikasi', 'ticketDitutup', 'ticketEskalasi', 'ticketDarurat',
            'tickets', 'ticketsPerStatus', 'ticketsPerMonth', 'ticketsPerCategory'
        ));
    }
}
