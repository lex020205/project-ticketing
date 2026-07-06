@extends('layouts.app')

@section('extra_css')
    @include('partials.ui.shadcn-dashboard-styles')
@endsection

@section('content')
<div class="dashboard-shell container">
    <div class="page-hero">
        <span class="page-kicker"><i class="bi bi-file-earmark-text"></i> Laporan Global</span>
        <h1 class="page-title">Laporan Global</h1>
        <p class="page-subtitle">Ringkasan operasional untuk melihat volume ticket, kondisi penyelesaian, serta aktivitas terbaru lintas role.</p>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="surface-card p-4 h-100">
                <div class="text-muted small text-uppercase fw-semibold mb-2">Total User</div>
                <div class="fs-2 fw-bold">{{ $totalUsers }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="surface-card p-4 h-100">
                <div class="text-muted small text-uppercase fw-semibold mb-2">Total Ticket</div>
                <div class="fs-2 fw-bold">{{ $totalTickets }}</div>
                <div class="text-muted small">{{ $thisMonthTickets }} ticket bulan ini</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="surface-card p-4 h-100">
                <div class="text-muted small text-uppercase fw-semibold mb-2">Ticket Darurat</div>
                <div class="fs-2 fw-bold">{{ $urgentTickets }}</div>
                <div class="text-muted small">{{ $escalatedTickets }} ticket sedang eskalasi</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="surface-card p-4 h-100">
                <h5 class="fw-bold mb-3">Status Ticket</h5>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($statusBreakdown as $status => $total)
                                <tr>
                                    <td>{{ str_replace('_', ' ', ucfirst($status)) }}</td>
                                    <td class="text-end fw-semibold">{{ $total }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">Belum ada data ticket.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="surface-card p-4 h-100">
                <h5 class="fw-bold mb-3">Ticket Terbaru</h5>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Ticket</th>
                                <th>Status</th>
                                <th>Teknisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentTickets as $ticket)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $ticket->kode_ticket ?? ('TICKET-' . $ticket->id) }}</div>
                                        <div class="text-muted small">{{ $ticket->kategori?->nama_kategori ?? 'Tanpa kategori' }}</div>
                                    </td>
                                    <td>{{ str_replace('_', ' ', ucfirst($ticket->status_ticket ?? '-')) }}</td>
                                    <td>{{ $ticket->teknisi?->name ?? 'Belum ditugaskan' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Belum ada ticket terbaru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="surface-card p-4">
        <h5 class="fw-bold mb-3">Aktivitas Terbaru</h5>
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Aksi</th>
                        <th>Modul</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentLogs as $log)
                        <tr>
                            <td>{{ $log->user?->name ?? 'System' }}</td>
                            <td>{{ $log->action }}</td>
                            <td>{{ $log->module }}</td>
                            <td>{{ $log->created_at?->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">Belum ada aktivitas yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
