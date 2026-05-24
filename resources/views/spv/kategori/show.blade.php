@extends('layouts.app')

@section('title', 'Detail Kategori - SPV')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Detail Kategori</h1>
        <p class="text-muted mb-0">Informasi kategori masalah.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('spv.kategori.edit', $kategori) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil-square"></i> Edit
        </a>
        <a href="{{ route('spv.kategori.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@php
    $statusBadge = [
        'aktif' => 'success',
        'nonaktif' => 'secondary',
    ];
    $prioritasBadge = [
        'rendah' => 'secondary',
        'sedang' => 'primary',
        'tinggi' => 'warning',
        'darurat' => 'danger',
    ];
    $statusTicketBadge = [
        'menunggu_penugasan' => 'secondary',
        'ditugaskan' => 'primary',
        'sedang_dikerjakan' => 'info',
        'menunggu_alat' => 'warning',
        'eskalasi' => 'danger',
        'menunggu_verifikasi' => 'warning',
        'ditutup' => 'success',
        'dibatalkan' => 'secondary',
    ];
    $statusClass = $statusBadge[$kategori->status_kategori] ?? 'secondary';
@endphp

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="text-muted small">Nama Kategori</div>
                <div class="fw-semibold">{{ $kategori->nama_kategori }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Status</div>
                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($kategori->status_kategori) }}</span>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Jumlah Keluhan</div>
                <div class="fw-semibold">{{ $kategori->keluhans_count }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Jumlah Ticket</div>
                <div class="fw-semibold">{{ $kategori->tickets_count }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Tanggal Dibuat</div>
                <div class="fw-semibold">{{ $kategori->created_at?->format('d-m-Y H:i') ?? '-' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Tanggal Diperbarui</div>
                <div class="fw-semibold">{{ $kategori->updated_at?->format('d-m-Y H:i') ?? '-' }}</div>
            </div>
            <div class="col-md-12">
                <div class="text-muted small">Deskripsi</div>
                <div class="fw-semibold">{{ $kategori->deskripsi ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h6 class="mb-0 fw-bold">Ticket Terbaru di Kategori Ini</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kode Ticket</th>
                        <th>Nama Pelapor</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Teknisi</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestTickets as $ticket)
                        @php
                            $prioritasClass = $ticket->prioritas ? ($prioritasBadge[$ticket->prioritas] ?? 'secondary') : 'secondary';
                            $statusClass = $statusTicketBadge[$ticket->status_ticket] ?? 'secondary';
                            $statusTextClass = in_array($ticket->status_ticket, ['menunggu_alat', 'menunggu_verifikasi'], true) ? 'text-dark' : '';
                        @endphp
                        <tr>
                            <td>{{ $ticket->kode_ticket }}</td>
                            <td>{{ $ticket->keluhan?->nama_pelapor ?? '-' }}</td>
                            <td><span class="badge bg-{{ $prioritasClass }}">{{ $ticket->prioritas ? ucfirst($ticket->prioritas) : '-' }}</span></td>
                            <td><span class="badge bg-{{ $statusClass }} {{ $statusTextClass }}">{{ str_replace('_', ' ', $ticket->status_ticket) }}</span></td>
                            <td>{{ $ticket->teknisi?->name ?? '-' }}</td>
                            <td>{{ $ticket->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada ticket.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
