{{-- Modul 14 - Finalisasi Dashboard Statistik per Role --}}
{{-- Ringkas: dashboard statistik admin. --}}
@extends('layouts.app')

@section('title', 'Dashboard Admin - Sistem Ticketing Laboran')

@section('extra_css')
    @include('partials.ui.shadcn-dashboard-styles')
@endsection

@section('content')
@php
    $prioritasBadge = [
        'rendah' => 'secondary',
        'sedang' => 'primary',
        'tinggi' => 'warning',
        'darurat' => 'danger',
    ];
    $statusTicketBadge = [
        'menunggu_penugasan' => 'secondary',
        'ditugaskan' => 'info',
        'sedang_dikerjakan' => 'primary',
        'menunggu_alat' => 'warning',
        'eskalasi' => 'danger',
        'menunggu_verifikasi' => 'warning',
        'ditutup' => 'success',
        'dibatalkan' => 'dark',
    ];
    $statusKeluhanBadge = [
        'baru' => 'secondary',
        'valid' => 'success',
        'tidak_valid' => 'danger',
        'menjadi_ticket' => 'info',
    ];
@endphp

    <div class="dashboard-shell dashboard-container">
        <div class="page-hero">
            <span class="page-kicker"><i class="bi bi-speedometer2"></i> Dashboard Admin</span>
            <h1 class="page-title">Kelola keluhan masuk, ticket, dan verifikasi pekerjaan.</h1>
        </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-blue">
                    <i class="bi bi-chat-left-text"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $totalKeluhan }}</div>
                    <div class="stat-label">Total Keluhan</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-green">
                    <i class="bi bi-bell"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $keluhanBaru }}</div>
                    <div class="stat-label">Keluhan Baru</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-purple">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $keluhanValid }}</div>
                    <div class="stat-label">Keluhan Valid</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-green">
                    <i class="bi bi-ticket"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $ticketAktif }}</div>
                    <div class="stat-label">Ticket Aktif</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-orange">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $ticketMenungguPenugasan }}</div>
                    <div class="stat-label">Menunggu Penugasan</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-purple">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $ticketMenungguVerifikasi }}</div>
                    <div class="stat-label">Menunggu Verifikasi</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-blue">
                    <i class="bi bi-door-closed"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $ticketDitutup }}</div>
                    <div class="stat-label">Ticket Ditutup</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-red">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $ticketDarurat }}</div>
                    <div class="stat-label">Ticket Darurat</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-modern table-shell mb-4">
        <div class="card-header">
            <h5 class="mb-0 fw-bold">Keluhan Terbaru</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Kode Keluhan</th>
                            <th>Nama Pelapor</th>
                            <th>Jenis Pelapor</th>
                            <th>Kategori</th>
                            <th>Prioritas</th>
                            <th>Status</th>
                            <th>Tanggal Keluhan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($keluhanTerbaru as $keluhan)
                            @php
                                $statusClass = $statusKeluhanBadge[$keluhan->status_keluhan] ?? 'secondary';
                                $prioritasClass = $prioritasBadge[$keluhan->prioritas_awal] ?? 'secondary';
                            @endphp
                            <tr>
                                <td class="ps-4">{{ $keluhan->kode_keluhan }}</td>
                                <td>{{ $keluhan->nama_pelapor }}</td>
                                <td>{{ ucfirst($keluhan->jenis_pelapor) }}</td>
                                <td>{{ $keluhan->kategori?->nama_kategori ?? '-' }}</td>
                                <td><span class="badge bg-{{ $prioritasClass }}">{{ ucfirst($keluhan->prioritas_awal) }}</span></td>
                                <td><span class="badge bg-{{ $statusClass }}">{{ str_replace('_', ' ', $keluhan->status_keluhan) }}</span></td>
                                <td>{{ $keluhan->tanggal_keluhan ? \Illuminate\Support\Carbon::parse($keluhan->tanggal_keluhan)->format('d-m-Y') : '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.keluhan.show', $keluhan) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card card-modern table-shell">
        <div class="card-header">
            <h5 class="mb-0 fw-bold">Ticket Terbaru</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Kode Ticket</th>
                            <th>Nama Pelapor</th>
                            <th>Kategori</th>
                            <th>Teknisi</th>
                            <th>Prioritas</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ticketTerbaru as $ticket)
                            @php
                                $prioritasClass = $ticket->prioritas ? ($prioritasBadge[$ticket->prioritas] ?? 'secondary') : 'secondary';
                                $statusClass = $statusTicketBadge[$ticket->status_ticket] ?? 'secondary';
                                $statusTextClass = in_array($ticket->status_ticket, ['menunggu_alat', 'menunggu_verifikasi'], true) ? 'text-dark' : '';
                            @endphp
                            <tr>
                                <td class="ps-4">{{ $ticket->kode_ticket }}</td>
                                <td>{{ $ticket->keluhan?->nama_pelapor ?? '-' }}</td>
                                <td>{{ $ticket->kategori?->nama_kategori ?? '-' }}</td>
                                <td>{{ $ticket->teknisi?->name ?? '-' }}</td>
                                <td><span class="badge bg-{{ $prioritasClass }}">{{ $ticket->prioritas ? ucfirst($ticket->prioritas) : '-' }}</span></td>
                                <td><span class="badge bg-{{ $statusClass }} {{ $statusTextClass }}">{{ str_replace('_', ' ', $ticket->status_ticket) }}</span></td>
                                <td>{{ $ticket->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
