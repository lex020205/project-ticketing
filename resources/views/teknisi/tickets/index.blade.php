{{-- Modul 4 - Teknisi Ticket Saya dan Progress Pengerjaan --}}
{{-- Ringkas: daftar ticket milik teknisi. --}}
@extends('layouts.app')

@section('title', 'Ticket Saya - Teknisi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Ticket Saya</h1>
        <p class="text-muted mb-0">Daftar ticket yang ditugaskan kepada Anda.</p>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

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

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kode Ticket</th>
                        <th>Nama Pelapor</th>
                        <th>Kategori</th>
                        <th>Lokasi Keluhan</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Tanggal Ditugaskan</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        @php
                            $prioritasClass = $ticket->prioritas ? ($prioritasBadge[$ticket->prioritas] ?? 'secondary') : 'secondary';
                            $statusClass = $statusTicketBadge[$ticket->status_ticket] ?? 'secondary';
                            $statusTextClass = in_array($ticket->status_ticket, ['menunggu_alat', 'menunggu_verifikasi'], true) ? 'text-dark' : '';
                        @endphp
                        <tr>
                            <td class="ps-4 fw-medium">{{ $ticket->kode_ticket }}</td>
                            <td>{{ $ticket->keluhan?->nama_pelapor ?? '-' }}</td>
                            <td>{{ $ticket->kategori?->nama_kategori ?? '-' }}</td>
                            <td>{{ $ticket->keluhan?->lokasi_keluhan ?? '-' }}</td>
                            <td><span class="badge bg-{{ $prioritasClass }}">{{ $ticket->prioritas ? ucfirst($ticket->prioritas) : '-' }}</span></td>
                            <td>
                                <span class="badge bg-{{ $statusClass }} {{ $statusTextClass }}">
                                    {{ str_replace('_', ' ', $ticket->status_ticket) }}
                                </span>
                            </td>
                            <td>
                                {{ $ticket->tanggal_ditugaskan ? \Illuminate\Support\Carbon::parse($ticket->tanggal_ditugaskan)->format('d-m-Y') : '-' }}
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('teknisi.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada ticket.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile view card list -->
        <div class="d-block d-md-none">
            <div class="list-group list-group-flush">
                @forelse ($tickets as $ticket)
                    @php
                        $prioritasClass = $ticket->prioritas ? ($prioritasBadge[$ticket->prioritas] ?? 'secondary') : 'secondary';
                        $statusClass = $statusTicketBadge[$ticket->status_ticket] ?? 'secondary';
                        $statusTextClass = in_array($ticket->status_ticket, ['menunggu_alat', 'menunggu_verifikasi'], true) ? 'text-dark' : '';
                    @endphp
                    <div class="list-group-item p-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-primary">{{ $ticket->kode_ticket }}</span>
                            <span class="badge bg-{{ $statusClass }} {{ $statusTextClass }}" style="font-size: 0.75rem;">
                                {{ str_replace('_', ' ', $ticket->status_ticket) }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <div class="text-muted small">Pelapor</div>
                            <div class="fw-semibold" style="font-size:0.95rem;">{{ $ticket->keluhan?->nama_pelapor ?? '-' }}</div>
                        </div>
                        <div class="mb-2">
                            <div class="text-muted small">Lokasi</div>
                            <div class="fw-semibold text-secondary" style="font-size:0.9rem;">
                                <i class="bi bi-geo-alt me-1"></i>{{ $ticket->keluhan?->lokasi_keluhan ?? '-' }}
                            </div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <div class="text-muted small">Kategori</div>
                                <div class="fw-semibold text-truncate" style="max-width: 130px; font-size:0.9rem;">{{ $ticket->kategori?->nama_kategori ?? '-' }}</div>
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
                            <a href="{{ route('teknisi.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary px-3" style="font-size: 0.8rem; border-radius: 15px;">
                                Detail <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">Belum ada ticket.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
