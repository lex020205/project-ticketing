{{-- Modul 14 - Finalisasi Dashboard Statistik per Role --}}
{{-- Ringkas: dashboard statistik teknisi. --}}
@extends('layouts.app')

@section('title', 'Dashboard Teknisi - Sistem Ticketing Laboran')

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
@endphp

<div class="dashboard-container">
    <div class="mb-4">
        <h1 class="fw-bold mb-2">Dashboard Teknisi</h1>
        <p class="text-muted">Lihat ticket yang ditugaskan dan update progress pengerjaan.</p>
    </div>

    <div class="row g-3 g-md-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-blue">
                    <i class="bi bi-ticket"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $totalTicket }}</div>
                    <div class="stat-label">Total Ticket</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-blue">
                    <i class="bi bi-person-check"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $ticketDitugaskan }}</div>
                    <div class="stat-label">Ditugaskan</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-green">
                    <i class="bi bi-gear"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $ticketSedangDikerjakan }}</div>
                    <div class="stat-label">Dikerjakan</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-orange">
                    <i class="bi bi-hourglass"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $ticketMenungguAlat }}</div>
                    <div class="stat-label">Menunggu Alat</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-red">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $ticketEskalasi }}</div>
                    <div class="stat-label">Eskalasi</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-purple">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $ticketMenungguVerifikasi }}</div>
                    <div class="stat-label">Verifikasi</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-green">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $ticketDitutup }}</div>
                    <div class="stat-label">Ditutup</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-red">
                    <i class="bi bi-exclamation-diamond"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $ticketDarurat }}</div>
                    <div class="stat-label">Darurat</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-modern mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">Ticket Saya Terbaru</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Kode Ticket</th>
                            <th>Nama Pelapor</th>
                            <th>Kategori</th>
                            <th>Lokasi Keluhan</th>
                            <th>Prioritas</th>
                            <th>Status</th>
                            <th>Tanggal Ditugaskan</th>
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
                                <td>{{ $ticket->keluhan?->lokasi_keluhan ?? '-' }}</td>
                                <td><span class="badge bg-{{ $prioritasClass }}">{{ $ticket->prioritas ? ucfirst($ticket->prioritas) : '-' }}</span></td>
                                <td><span class="badge bg-{{ $statusClass }} {{ $statusTextClass }}">{{ str_replace('_', ' ', $ticket->status_ticket) }}</span></td>
                                <td>{{ $ticket->tanggal_ditugaskan ? \Illuminate\Support\Carbon::parse($ticket->tanggal_ditugaskan)->format('d-m-Y H:i') : '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('teknisi.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">Detail</a>
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

            <!-- Mobile View (iPhone 13 friendly) -->
            <div class="d-block d-md-none">
                <div class="list-group list-group-flush">
                    @forelse ($ticketTerbaru as $ticket)
                        @php
                            $prioritasClass = $ticket->prioritas ? ($prioritasBadge[$ticket->prioritas] ?? 'secondary') : 'secondary';
                            $statusClass = $statusTicketBadge[$ticket->status_ticket] ?? 'secondary';
                            $statusTextClass = in_array($ticket->status_ticket, ['menunggu_alat', 'menunggu_verifikasi'], true) ? 'text-dark' : '';
                        @endphp
                        <div class="list-group-item p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-primary">{{ $ticket->kode_ticket }}</span>
                                <span class="badge bg-{{ $statusClass }} {{ $statusTextClass }}" style="font-size:0.75rem;">{{ str_replace('_', ' ', $ticket->status_ticket) }}</span>
                            </div>
                            <div class="mb-2">
                                <div class="text-muted small">Pelapor</div>
                                <div class="fw-semibold" style="font-size:0.95rem;">{{ $ticket->keluhan?->nama_pelapor ?? '-' }}</div>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <div class="text-muted small">Kategori</div>
                                    <div class="fw-semibold text-truncate" style="max-width:130px;font-size:0.9rem;">{{ $ticket->kategori?->nama_kategori ?? '-' }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted small">Prioritas</div>
                                    <span class="badge bg-{{ $prioritasClass }}">{{ $ticket->prioritas ? ucfirst($ticket->prioritas) : '-' }}</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                <span class="text-muted small">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $ticket->tanggal_ditugaskan ? \Illuminate\Support\Carbon::parse($ticket->tanggal_ditugaskan)->format('d-m-Y') : '-' }}
                                </span>
                                <a href="{{ route('teknisi.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary px-3" style="font-size:0.8rem; border-radius:15px;">
                                    Detail <i class="bi bi-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">Belum ada ticket baru.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="card card-modern">
        <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">Progress Terbaru Saya</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Kode Ticket</th>
                            <th>Catatan</th>
                            <th>Status Sebelumnya</th>
                            <th>Status Baru</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($progressTerbaru as $progress)
                            <tr>
                                <td class="ps-4">{{ $progress->ticket?->kode_ticket ?? '-' }}</td>
                                <td>{{ $progress->catatan }}</td>
                                <td>{{ str_replace('_', ' ', $progress->status_sebelumnya) }}</td>
                                <td>{{ str_replace('_', ' ', $progress->status_baru) }}</td>
                                <td>{{ $progress->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile View (iPhone 13 friendly) -->
            <div class="d-block d-md-none">
                <div class="list-group list-group-flush">
                    @forelse ($progressTerbaru as $progress)
                        <div class="list-group-item p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-primary">{{ $progress->ticket?->kode_ticket ?? '-' }}</span>
                                <span class="text-muted small" style="font-size:0.8rem;">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $progress->created_at?->format('d-m-Y H:i') ?? '-' }}
                                </span>
                            </div>
                            <div class="mb-2">
                                <div class="text-muted small">Catatan</div>
                                <div class="fw-semibold text-secondary" style="font-size:0.9rem;">{{ $progress->catatan }}</div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-2" style="font-size:0.85rem;">
                                <span class="badge bg-secondary opacity-75">{{ str_replace('_', ' ', $progress->status_sebelumnya) }}</span>
                                <i class="bi bi-arrow-right text-muted"></i>
                                <span class="badge bg-primary">{{ str_replace('_', ' ', $progress->status_baru) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">Belum ada progres terbaru.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .dashboard-container {
        max-width: 1400px;
    }

    .stat-card {
        background: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-icon-blue {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .stat-icon-green {
        background-color: #dcfce7;
        color: #166534;
    }

    .stat-icon-orange {
        background-color: #fed7aa;
        color: #92400e;
    }

    .stat-icon-purple {
        background-color: #e9d5ff;
        color: #6b21a8;
    }

    .stat-icon-red {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .stat-content {
        flex: 1;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e293b;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
    }

    .card-modern {
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .card-header {
        border-bottom: 1px solid #e2e8f0;
        padding: 1.25rem;
    }

    .table {
        border-collapse: collapse;
    }

    .table thead th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 600;
        border-bottom: 2px solid #e2e8f0;
        padding: 1rem;
        font-size: 0.9rem;
    }

    .table tbody td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Mobile media query customizations */
    @media (max-width: 576px) {
        .stat-card {
            padding: 0.85rem;
            gap: 0.5rem;
            border-radius: 0.5rem;
        }
        .stat-icon {
            width: 36px;
            height: 36px;
            font-size: 1.1rem;
            border-radius: 0.375rem;
        }
        .stat-value {
            font-size: 1.25rem;
            line-height: 1.2;
        }
        .stat-label {
            font-size: 0.7rem;
            line-height: 1.2;
        }
    }
</style>
@endsection
