<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class ReportController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalTickets = Ticket::count();
        $openTickets = Ticket::where('status_ticket', '!=', 'ditutup')->count();
        $closedTickets = Ticket::where('status_ticket', 'ditutup')->count();
        $escalatedTickets = Ticket::where('status_ticket', 'eskalasi')->count();
        $urgentTickets = Ticket::where('prioritas', 'darurat')->count();
        $thisMonthTickets = Ticket::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $statusBreakdown = Ticket::selectRaw('status_ticket, COUNT(*) as total')
            ->groupBy('status_ticket')
            ->orderBy('status_ticket')
            ->pluck('total', 'status_ticket');

        $recentTickets = Ticket::with(['keluhan', 'kategori', 'teknisi'])
            ->latest()
            ->limit(10)
            ->get();

        $recentLogs = Schema::hasTable('activity_logs')
            ? ActivityLog::with('user')->latest()->limit(8)->get()
            : collect();

        return view('super-admin.laporan.index', compact(
            'totalUsers',
            'totalTickets',
            'openTickets',
            'closedTickets',
            'escalatedTickets',
            'urgentTickets',
            'thisMonthTickets',
            'statusBreakdown',
            'recentTickets',
            'recentLogs'
        ));
    }
}
