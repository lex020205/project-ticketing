<?php

namespace App\Http\Controllers\Spv;

use App\Http\Controllers\Controller;
use App\Models\KategoriMasalah;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

// Modul 11 - Laporan Admin dan SPV
// Ringkas: rekap dan ringkasan laporan ticket SPV.
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

        $ticketQuery = Ticket::query();
        $this->applyFilters($ticketQuery, $request);

        $summary = [
            'total' => (clone $ticketQuery)->count(),
            'aktif' => (clone $ticketQuery)->whereNotIn('status_ticket', ['ditutup', 'dibatalkan'])->count(),
            'darurat' => (clone $ticketQuery)->where('prioritas', 'darurat')->count(),
            'eskalasi' => (clone $ticketQuery)->where('status_ticket', 'eskalasi')->count(),
            'menunggu_verifikasi' => (clone $ticketQuery)->where('status_ticket', 'menunggu_verifikasi')->count(),
            'ditutup' => (clone $ticketQuery)->where('status_ticket', 'ditutup')->count(),
        ];

        $teknisiAktif = User::where('status_user', 'aktif')
            ->whereHas('role', function ($query) {
                $query->where('nama_role', 'Teknisi');
            })
            ->count();

        $kategoriAktif = KategoriMasalah::where('status_kategori', 'aktif')->count();

        $rekapStatusQuery = Ticket::query();
        $this->applyFilters($rekapStatusQuery, $request);
        $rekapStatus = $rekapStatusQuery
            ->select('status_ticket', DB::raw('count(*) as jumlah'))
            ->groupBy('status_ticket')
            ->orderBy('status_ticket')
            ->get();

        $rekapPrioritasQuery = Ticket::query();
        $this->applyFilters($rekapPrioritasQuery, $request);
        $rekapPrioritas = $rekapPrioritasQuery
            ->select('prioritas', DB::raw('count(*) as jumlah'))
            ->groupBy('prioritas')
            ->orderBy('prioritas')
            ->get();

        $rekapKategoriQuery = Ticket::query();
        $this->applyFilters($rekapKategoriQuery, $request);
        $rekapKategori = $rekapKategoriQuery
            ->join('kategori_masalah as kategori', 'tickets.kategori_id', '=', 'kategori.id')
            ->select('kategori.nama_kategori', DB::raw('count(tickets.id) as jumlah_ticket'))
            ->groupBy('kategori.nama_kategori')
            ->orderBy('kategori.nama_kategori')
            ->get();

        $rekapTeknisiQuery = Ticket::query();
        $this->applyFilters($rekapTeknisiQuery, $request);
        $rekapTeknisi = $rekapTeknisiQuery
            ->whereNotNull('tickets.teknisi_id')
            ->join('users as teknisi', 'tickets.teknisi_id', '=', 'teknisi.id')
            ->select(
                'teknisi.name as nama_teknisi',
                DB::raw('count(tickets.id) as total_ticket'),
                DB::raw("sum(case when tickets.status_ticket = 'ditutup' then 1 else 0 end) as ticket_selesai"),
                DB::raw("sum(case when tickets.status_ticket not in ('ditutup', 'dibatalkan') then 1 else 0 end) as ticket_aktif")
            )
            ->groupBy('teknisi.name')
            ->orderBy('teknisi.name')
            ->get();

        $latestQuery = Ticket::with(['keluhan', 'kategori', 'teknisi']);
        $this->applyFilters($latestQuery, $request);
        $latestTickets = $latestQuery
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $kategoriList = KategoriMasalah::where('status_kategori', 'aktif')
            ->orderBy('nama_kategori')
            ->get();

        $teknisiList = User::where('status_user', 'aktif')
            ->whereHas('role', function ($query) {
                $query->where('nama_role', 'Teknisi');
            })
            ->orderBy('name')
            ->get();

        return view('spv.laporan.index', compact(
            'summary',
            'statusOptions',
            'teknisiAktif',
            'kategoriAktif',
            'rekapStatus',
            'rekapPrioritas',
            'rekapKategori',
            'rekapTeknisi',
            'latestTickets',
            'kategoriList',
            'teknisiList'
        ));
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
