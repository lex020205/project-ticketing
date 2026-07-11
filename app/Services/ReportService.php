<?php

namespace App\Services;

use App\Models\KategoriMasalah;
use App\Models\ReportLog;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReportService
{
    /**
     * Ambil ticket dengan filter aktif.
     */
    public function getFilteredTickets(array $filters): Collection
    {
        $query = Ticket::query()->with(['keluhan', 'kategori', 'teknisi']);

        $this->applyFilters($query, $filters);

        return $query->orderByDesc('created_at')->get();
    }

    /**
     * Hitung ringkasan statistik dari koleksi ticket.
     */
    public function getReportSummary(Collection $tickets): array
    {
        return [
            'total'                => $tickets->count(),
            'ditugaskan'           => $tickets->where('status_ticket', 'ditugaskan')->count(),
            'sedang_dikerjakan'    => $tickets->where('status_ticket', 'sedang_dikerjakan')->count(),
            'menunggu_alat'        => $tickets->where('status_ticket', 'menunggu_alat')->count(),
            'menunggu_verifikasi'  => $tickets->where('status_ticket', 'menunggu_verifikasi')->count(),
            'ditutup'              => $tickets->where('status_ticket', 'ditutup')->count(),
            'eskalasi'             => $tickets->where('status_ticket', 'eskalasi')->count(),
            'darurat'              => $tickets->where('prioritas', 'darurat')->count(),
            'prioritas_tinggi'     => $tickets->where('prioritas', 'tinggi')->count(),
            'prioritas_sedang'     => $tickets->where('prioritas', 'sedang')->count(),
            'prioritas_rendah'     => $tickets->where('prioritas', 'rendah')->count(),
        ];
    }

    /**
     * Ambil daftar kategori aktif.
     */
    public function getKategoriList(): Collection
    {
        return KategoriMasalah::where('status_kategori', 'aktif')
            ->orderBy('nama_kategori')
            ->get();
    }

    /**
     * Ambil daftar teknisi aktif.
     */
    public function getTeknisiList(): Collection
    {
        return User::where('status_user', 'aktif')
            ->whereHas('role', fn ($q) => $q->where('nama_role', 'Teknisi'))
            ->orderBy('name')
            ->get();
    }

    /**
     * Ambil riwayat generate laporan.
     */
    public function getReportLogs(int $limit = 20): Collection
    {
        return ReportLog::with('user')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Simpan log generate laporan.
     */
    public function createLog(array $data): ?ReportLog
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('report_logs')) {
                return null;
            }

            return ReportLog::create($data);
        } catch (\Throwable $e) {
            \Log::warning('ReportService::createLog failed', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Apply filter ke query ticket.
     */
    private function applyFilters(Builder $query, array $filters): void
    {
        if (!empty($filters['status_ticket'])) {
            $query->where('status_ticket', $filters['status_ticket']);
        }

        if (!empty($filters['prioritas'])) {
            $query->where('prioritas', $filters['prioritas']);
        }

        if (!empty($filters['kategori_id'])) {
            $query->where('kategori_id', $filters['kategori_id']);
        }

        if (!empty($filters['teknisi_id'])) {
            $query->where('teknisi_id', $filters['teknisi_id']);
        }

        if (!empty($filters['tanggal_awal']) && !empty($filters['tanggal_akhir'])) {
            $tanggalAwal = Carbon::parse($filters['tanggal_awal'])->startOfDay();
            $tanggalAkhir = Carbon::parse($filters['tanggal_akhir'])->endOfDay();

            $query->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir]);
        }
    }

    /**
     * Ambil data rekap teknisi untuk laporan PDF.
     * Mengelompokkan ticket selesai (ditutup) berdasarkan teknisi.
     *
     * @return array{rekap: \Illuminate\Support\Collection, summary: array}
     */
    public function getRekapTeknisiData(): array
    {
        $tickets = Ticket::where('status_ticket', 'ditutup')
            ->whereNotNull('teknisi_id')
            ->with(['keluhan', 'kategori', 'teknisi'])
            ->orderBy('teknisi_id')
            ->orderByDesc('tanggal_selesai')
            ->get();

        return $this->buildRekapTeknisiPayload($tickets);
    }

    /**
     * Bangun payload rekap teknisi dari koleksi ticket yang sudah terfilter.
     */
    public function buildRekapTeknisiPayload(Collection $tickets): array
    {
        $ticketSelesai = $tickets->filter(fn ($ticket) => $ticket->status_ticket === 'ditutup' && !empty($ticket->teknisi_id));

        $rekapPerTeknisi = $ticketSelesai->groupBy('teknisi_id')->map(function ($groupedTickets) {
            $teknisi = $groupedTickets->first()->teknisi;
            return [
                'nama_teknisi'   => $teknisi?->name ?? 'Tidak Diketahui',
                'jumlah_selesai' => $groupedTickets->count(),
                'tickets'        => $groupedTickets,
            ];
        })->values();

        $summary = [
            'jumlah_teknisi'       => User::where('status_user', 'aktif')
                ->whereHas('role', fn ($q) => $q->where('nama_role', 'Teknisi'))
                ->count(),
            'total_ticket_selesai' => $ticketSelesai->count(),
            'total_ticket_aktif'   => $tickets->whereNotIn('status_ticket', ['ditutup', 'dibatalkan'])->count(),
            'total_ticket_eskalasi'=> $tickets->where('status_ticket', 'eskalasi')->count(),
            'total_ticket_ditolak' => $tickets->where('status_ticket', 'dibatalkan')->count(),
        ];

        return [
            'rekap'   => $rekapPerTeknisi,
            'summary' => $summary,
        ];
    }
}
