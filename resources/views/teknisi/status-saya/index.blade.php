{{-- Modul 16 - Teknisi Status Saya --}}
{{-- Ringkas: ringkasan status ticket teknisi. --}}
@extends('layouts.app')

@section('title', 'Status Saya - Teknisi')

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

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Status Saya</h1>
        <p class="text-muted mb-0">Ringkasan status ticket yang ditangani.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Total Ticket Saya</div>
                <div class="fs-4 fw-bold">{{ $totalTicket }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Ticket Ditugaskan</div>
                <div class="fs-4 fw-bold">{{ $ticketDitugaskan }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Sedang Dikerjakan</div>
                <div class="fs-4 fw-bold">{{ $ticketSedangDikerjakan }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Menunggu Alat</div>
                <div class="fs-4 fw-bold">{{ $ticketMenungguAlat }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Eskalasi</div>
                <div class="fs-4 fw-bold">{{ $ticketEskalasi }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Menunggu Verifikasi</div>
                <div class="fs-4 fw-bold">{{ $ticketMenungguVerifikasi }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Ticket Ditutup</div>
                <div class="fs-4 fw-bold">{{ $ticketDitutup }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Ticket Darurat Saya</div>
                <div class="fs-4 fw-bold">{{ $ticketDarurat }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0 fw-bold">Ringkasan Ticket Teknisi</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kode Ticket</th>
                        <th>Nama Pelapor</th>
                        <th>Kategori</th>
                        <th>Prioritas</th>
                        <th>Status Ticket</th>
                        <th>Tanggal Ditugaskan</th>
                        <th class="text-end">Aksi</th>
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
                            <td>{{ $ticket->kode_ticket }}</td>
                            <td>{{ $ticket->keluhan?->nama_pelapor ?? '-' }}</td>
                            <td>{{ $ticket->kategori?->nama_kategori ?? '-' }}</td>
                            <td><span class="badge bg-{{ $prioritasClass }}">{{ $ticket->prioritas ? ucfirst($ticket->prioritas) : '-' }}</span></td>
                            <td><span class="badge bg-{{ $statusClass }} {{ $statusTextClass }}">{{ str_replace('_', ' ', $ticket->status_ticket) }}</span></td>
                            <td>{{ $ticket->tanggal_ditugaskan ? \Illuminate\Support\Carbon::parse($ticket->tanggal_ditugaskan)->format('d-m-Y H:i') : '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('teknisi.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada ticket.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
