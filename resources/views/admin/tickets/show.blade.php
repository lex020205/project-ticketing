{{-- Modul 10 - Admin Monitoring Ticket dan Assign Teknisi --}}
{{-- Ringkas: detail ticket untuk monitoring admin. --}}
@extends('layouts.app')

@section('title', 'Detail Ticket - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Detail Ticket</h1>
        <p class="text-muted mb-0">Informasi lengkap ticket.</p>
    </div>
    <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="text-muted small">Kode Ticket</div>
                <div class="fw-semibold">{{ $ticket->kode_ticket }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Status Ticket</div>
                <div class="fw-semibold">{{ str_replace('_', ' ', $ticket->status_ticket) }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Prioritas</div>
                <div class="fw-semibold">{{ ucfirst($ticket->prioritas) }}</div>
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
            <div class="col-md-4">
                <div class="text-muted small">Tanggal Ditugaskan</div>
                <div class="fw-semibold">
                    {{ $ticket->tanggal_ditugaskan ? \Illuminate\Support\Carbon::parse($ticket->tanggal_ditugaskan)->format('d-m-Y H:i') : '-' }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Tanggal Mulai</div>
                <div class="fw-semibold">
                    {{ $ticket->tanggal_mulai ? \Illuminate\Support\Carbon::parse($ticket->tanggal_mulai)->format('d-m-Y H:i') : '-' }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Tanggal Selesai</div>
                <div class="fw-semibold">
                    {{ $ticket->tanggal_selesai ? \Illuminate\Support\Carbon::parse($ticket->tanggal_selesai)->format('d-m-Y H:i') : '-' }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Tanggal Ditutup</div>
                <div class="fw-semibold">
                    {{ $ticket->tanggal_ditutup ? \Illuminate\Support\Carbon::parse($ticket->tanggal_ditutup)->format('d-m-Y H:i') : '-' }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Data Keluhan</h5>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="text-muted small">Kode Keluhan</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->kode_keluhan ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Nama Pelapor</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->nama_pelapor ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Jenis Pelapor</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->jenis_pelapor ? ucfirst($ticket->keluhan->jenis_pelapor) : '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Kontak Pelapor</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->kontak_pelapor ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Lokasi Keluhan</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->lokasi_keluhan ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Tanggal Keluhan</div>
                <div class="fw-semibold">
                    {{ $ticket->keluhan?->tanggal_keluhan ? \Illuminate\Support\Carbon::parse($ticket->keluhan->tanggal_keluhan)->format('d-m-Y') : '-' }}
                </div>
            </div>
            <div class="col-12">
                <div class="text-muted small">Deskripsi Keluhan</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->deskripsi_keluhan ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
