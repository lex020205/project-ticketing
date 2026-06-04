<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriMasalah;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

// Modul 11 - Laporan Admin dan SPV
// Ringkas: filter dan rekap laporan ticket admin.
class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $statusOptions = [
            'menunggu_penugasan' => 'Menunggu penugasan',
            'ditugaskan' => 'Ditugaskan',
            'sedang_dikerjakan' => 'Sedang dikerjakan',
            'menunggu_alat' => 'Menunggu alat',
            'eskalasi' => 'Eskalasi',
            'menunggu_verifikasi' => 'Menunggu verifikasi',
            'ditutup' => 'Ditutup',
            'dibatalkan' => 'Dibatalkan',
        ];

        $ticketQuery = Ticket::query()->with(['keluhan', 'kategori', 'teknisi']);
        $this->applyFilters($ticketQuery, $request);

        $summary = [
            'total' => (clone $ticketQuery)->count(),
            'menunggu_penugasan' => (clone $ticketQuery)->where('status_ticket', 'menunggu_penugasan')->count(),
            'sedang_dikerjakan' => (clone $ticketQuery)->where('status_ticket', 'sedang_dikerjakan')->count(),
            'menunggu_verifikasi' => (clone $ticketQuery)->where('status_ticket', 'menunggu_verifikasi')->count(),
            'ditutup' => (clone $ticketQuery)->where('status_ticket', 'ditutup')->count(),
            'eskalasi' => (clone $ticketQuery)->where('status_ticket', 'eskalasi')->count(),
        ];

        $tickets = (clone $ticketQuery)->orderByDesc('created_at')->get();

        $kategoriList = KategoriMasalah::where('status_kategori', 'aktif')
            ->orderBy('nama_kategori')
            ->get();

        $teknisiList = User::where('status_user', 'aktif')
            ->whereHas('role', function ($query) {
                $query->where('nama_role', 'Teknisi');
            })
            ->orderBy('name')
            ->get();

        return view('admin.laporan.index', compact('tickets', 'summary', 'statusOptions', 'kategoriList', 'teknisiList'));
    }

    private function applyFilters(Builder $query, Request $request): void
    {
        $query->when($request->filled('status_ticket'), function ($builder) use ($request) {
            $builder->where('status_ticket', $request->status_ticket);
        });

        $query->when($request->filled('prioritas'), function ($builder) use ($request) {
            $builder->where('prioritas', $request->prioritas);
        });

        $query->when($request->filled('kategori_id'), function ($builder) use ($request) {
            $builder->where('kategori_id', $request->kategori_id);
        });

        $query->when($request->filled('teknisi_id'), function ($builder) use ($request) {
            $builder->where('teknisi_id', $request->teknisi_id);
        });

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $tanggalAwal = Carbon::parse($request->tanggal_awal)->startOfDay();
            $tanggalAkhir = Carbon::parse($request->tanggal_akhir)->endOfDay();

            $query->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir]);
        }
    }
}
