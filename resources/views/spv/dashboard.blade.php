{{-- Modul 14 - Finalisasi Dashboard Statistik per Role --}}
{{-- Ringkas: dashboard statistik SPV. --}}
@extends('layouts.app')

@section('title', 'Dashboard SPV - Sistem Ticketing Laboran')

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
    $statusEskalasiBadge = [
        'menunggu' => 'warning',
        'disetujui' => 'success',
        'ditolak' => 'danger',
        'selesai' => 'secondary',
    ];
    $jenisLabel = [
        'butuh_bantuan' => 'Butuh Bantuan Teknisi Lain',
        'butuh_alat' => 'Butuh Alat / Sparepart',
        'dialihkan' => 'Minta Dialihkan ke Teknisi Lain',
        'keputusan_spv' => 'Butuh Keputusan SPV',
    ];
@endphp

    <div class="dashboard-shell dashboard-container">
        <div class="page-hero">
            <span class="page-kicker"><i class="bi bi-speedometer2"></i> Dashboard SPV</span>
            <h1 class="page-title">Monitoring ticket, eskalasi, user, dan laporan sistem.</h1>
            <p class="page-subtitle">Komponen statistik, tabel, dan aksi dibuat seragam agar lebih mudah dipindai dan terasa konsisten dengan halaman lain.</p>
        </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
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
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-green">
                    <i class="bi bi-activity"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $ticketAktif }}</div>
                    <div class="stat-label">Ticket Aktif</div>
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
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-orange">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $ticketEskalasi }}</div>
                    <div class="stat-label">Ticket Eskalasi</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-orange">
                    <i class="bi bi-hourglass"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $eskalasiMenunggu }}</div>
                    <div class="stat-label">Eskalasi Menunggu</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-purple">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $menungguVerifikasi }}</div>
                    <div class="stat-label">Menunggu Verifikasi</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-green">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $ticketDitutup }}</div>
                    <div class="stat-label">Ticket Ditutup</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-blue">
                    <i class="bi bi-tools"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $teknisiAktif }}</div>
                    <div class="stat-label">Teknisi Aktif</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-blue">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $userAktif }}</div>
                    <div class="stat-label">User Aktif</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-green">
                    <i class="bi bi-list-ul"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $kategoriAktif }}</div>
                    <div class="stat-label">Kategori Aktif</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-modern table-shell mb-4">
        <div class="card-header">
            <h5 class="mb-0 fw-bold">Ticket Urgent/Darurat</h5>
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
                        @forelse ($urgentTickets as $ticket)
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
                                    <a href="{{ route('spv.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">Detail</a>
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

    <div class="card card-modern table-shell mb-4">
        <div class="card-header">
            <h5 class="mb-0 fw-bold">Eskalasi Menunggu Keputusan</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Kode Ticket</th>
                            <th>Teknisi Pengaju</th>
                            <th>Jenis Eskalasi</th>
                            <th>Alasan</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($eskalasiMenungguList as $eskalasi)
                            @php
                                $statusClass = $statusEskalasiBadge[$eskalasi->status_eskalasi] ?? 'secondary';
                            @endphp
                            <tr>
                                <td class="ps-4">{{ $eskalasi->ticket?->kode_ticket ?? '-' }}</td>
                                <td>{{ $eskalasi->pengaju?->name ?? '-' }}</td>
                                <td>{{ $jenisLabel[$eskalasi->jenis_eskalasi] ?? $eskalasi->jenis_eskalasi }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($eskalasi->alasan, 50) }}</td>
                                <td><span class="badge bg-{{ $statusClass }}">{{ $eskalasi->status_eskalasi }}</span></td>
                                <td>{{ $eskalasi->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('spv.eskalasi.show', $eskalasi) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card card-modern table-shell">
        <div class="card-header">
            <h5 class="mb-0 fw-bold">Beban Kerja Teknisi</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Teknisi</th>
                            <th>Total Ticket</th>
                            <th>Ticket Aktif</th>
                            <th>Ticket Selesai</th>
                            <th>Ticket Eskalasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teknisiWorkload as $teknisi)
                            <tr>
                                <td class="ps-4">{{ $teknisi->name }}</td>
                                <td>{{ $teknisi->total_ticket }}</td>
                                <td>{{ $teknisi->ticket_aktif }}</td>
                                <td>{{ $teknisi->ticket_selesai }}</td>
                                <td>{{ $teknisi->ticket_eskalasi }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
