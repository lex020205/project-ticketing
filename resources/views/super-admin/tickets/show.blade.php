{{-- Ringkas: detail ticket untuk monitoring Super Admin. --}}
@extends('layouts.app')

@section('extra_css')
    @include('partials.ui.shadcn-dashboard-styles')
@endsection

@section('title', 'Detail Ticket - Super Admin')

@section('content')
@php
    $statusBadge = [
        'menunggu_penugasan' => 'secondary',
        'ditugaskan' => 'primary',
        'sedang_dikerjakan' => 'info',
        'menunggu_alat' => 'warning',
        'eskalasi' => 'danger',
        'menunggu_verifikasi' => 'warning',
        'ditutup' => 'success',
        'dibatalkan' => 'secondary',
    ];
    $prioritasBadge = [
        'rendah' => 'secondary',
        'sedang' => 'primary',
        'tinggi' => 'warning',
        'darurat' => 'danger',
    ];
@endphp

<div class="dashboard-shell container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Detail Ticket</h1>
            <p class="text-muted mb-0">Monitoring lengkap ticket, progress, dan lampiran bukti pengerjaan.</p>
        </div>
        <a href="{{ route('super-admin.tickets.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card card-modern mb-4">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Data Ticket</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="text-muted small">Kode Ticket</div>
                    <div class="fw-semibold">{{ $ticket->kode_ticket }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Status Ticket</div>
                    <span class="badge bg-{{ $statusBadge[$ticket->status_ticket] ?? 'secondary' }}">{{ str_replace('_', ' ', $ticket->status_ticket) }}</span>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Prioritas</div>
                    <span class="badge bg-{{ $prioritasBadge[$ticket->prioritas] ?? 'secondary' }}">{{ $ticket->prioritas ? ucfirst($ticket->prioritas) : '-' }}</span>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Kategori</div>
                    <div class="fw-semibold">{{ $ticket->kategori?->nama_kategori ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Teknisi</div>
                    <div class="fw-semibold">{{ $ticket->teknisi?->name ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Assigned By</div>
                    <div class="fw-semibold">{{ $ticket->assignedBy?->name ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-modern mb-4">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Data Keluhan</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="text-muted small">Nama Pelapor</div>
                    <div class="fw-semibold">{{ $ticket->keluhan?->nama_pelapor ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Lokasi Keluhan</div>
                    <div class="fw-semibold">{{ $ticket->keluhan?->lokasi_keluhan ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Kontak Pelapor</div>
                    <div class="fw-semibold">{{ $ticket->keluhan?->kontak_pelapor ?? '-' }}</div>
                </div>
                <div class="col-12">
                    <div class="text-muted small">Deskripsi Keluhan</div>
                    <div class="fw-semibold">{{ $ticket->keluhan?->deskripsi_keluhan ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-modern mb-4">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Lampiran Bukti Pengerjaan</h5>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Preview</th>
                            <th>Nama File</th>
                            <th>Keterangan</th>
                            <th>Uploader</th>
                            <th>Waktu Upload</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ticket->lampiran as $lampiran)
                            @php
                                $extension = strtolower(pathinfo($lampiran->nama_file, PATHINFO_EXTENSION));
                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png'], true);
                                $fileUrl = route('ticket-lampiran.show', $lampiran);
                            @endphp
                            <tr>
                                <td>
                                    @if ($isImage)
                                        <img src="{{ $fileUrl }}" alt="{{ $lampiran->nama_file }}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <span class="badge bg-secondary">PDF</span>
                                    @endif
                                </td>
                                <td>{{ $lampiran->nama_file }}</td>
                                <td>{{ $lampiran->keterangan ?? '-' }}</td>
                                <td>{{ $lampiran->uploader?->name ?? '-' }}</td>
                                <td>{{ $lampiran->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ $fileUrl }}" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener">Lihat</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Belum ada lampiran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card card-modern">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Riwayat Progress</h5>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Status Sebelumnya</th>
                            <th>Status Baru</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ticket->progress as $progress)
                            <tr>
                                <td>{{ $progress->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                                <td>{{ $progress->user?->name ?? '-' }}</td>
                                <td>{{ str_replace('_', ' ', $progress->status_sebelumnya) }}</td>
                                <td>{{ str_replace('_', ' ', $progress->status_baru) }}</td>
                                <td>{{ $progress->catatan }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">Belum ada progress.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
