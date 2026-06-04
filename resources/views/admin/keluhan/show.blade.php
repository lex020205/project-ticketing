{{-- Modul 2 - Admin Keluhan --}}
{{-- Ringkas: detail keluhan dan tindakan validasi. --}}
@extends('layouts.app')

@section('title', 'Detail Keluhan - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Detail Keluhan</h1>
        <p class="text-muted mb-0">Informasi lengkap keluhan.</p>
    </div>
    <a href="{{ route('admin.keluhan.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="text-muted small">Kode Keluhan</div>
                <div class="fw-semibold">{{ $keluhan->kode_keluhan }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Status</div>
                <span class="badge bg-{{ $keluhan->status_keluhan === 'valid' ? 'success' : ($keluhan->status_keluhan === 'tidak_valid' ? 'danger' : 'secondary') }}">
                    {{ str_replace('_', ' ', $keluhan->status_keluhan) }}
                </span>
                @if ($keluhan->ticket)
                    <div class="mt-2">
                        <span class="badge bg-info text-dark">Keluhan sudah dibuat menjadi ticket</span>
                        <div class="mt-2">
                            <a href="{{ route('admin.tickets.show', $keluhan->ticket) }}" class="btn btn-sm btn-outline-primary">Lihat Ticket</a>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Tanggal Keluhan</div>
                <div class="fw-semibold">{{ \Illuminate\Support\Carbon::parse($keluhan->tanggal_keluhan)->format('d-m-Y') }}</div>
            </div>

            <div class="col-md-6">
                <div class="text-muted small">Nama Pelapor</div>
                <div class="fw-semibold">{{ $keluhan->nama_pelapor }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Jenis Pelapor</div>
                <div class="fw-semibold">{{ ucfirst($keluhan->jenis_pelapor) }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Kontak Pelapor</div>
                <div class="fw-semibold">{{ $keluhan->kontak_pelapor ?: '-' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Kategori</div>
                <div class="fw-semibold">{{ $keluhan->kategori?->nama_kategori ?? '-' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Lokasi Keluhan</div>
                <div class="fw-semibold">{{ $keluhan->lokasi_keluhan ?: '-' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Detail Lokasi</div>
                <div class="fw-semibold">{{ $keluhan->detail_lokasi ?: '-' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Prioritas Awal</div>
                <div class="fw-semibold">{{ ucfirst($keluhan->prioritas_awal) }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Dibuat Oleh</div>
                <div class="fw-semibold">{{ $keluhan->pembuat?->name ?? '-' }}</div>
            </div>
            <div class="col-12">
                <div class="text-muted small">Deskripsi Keluhan</div>
                <div class="fw-semibold">{{ $keluhan->deskripsi_keluhan }}</div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap gap-2">
    <a href="{{ route('admin.keluhan.edit', $keluhan) }}" class="btn btn-outline-primary">
        <i class="bi bi-pencil-square"></i> Edit
    </a>
    @if ($keluhan->status_keluhan === 'valid' && !$keluhan->ticket)
        <a href="{{ route('admin.keluhan.buat_ticket', $keluhan) }}" class="btn btn-primary">
            <i class="bi bi-ticket"></i> Buat Ticket
        </a>
    @endif
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalValidasi">
        <i class="bi bi-check-circle"></i> Validasi Keluhan
    </button>
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalTidakValid">
        <i class="bi bi-x-circle"></i> Tandai Tidak Valid
    </button>
    <a href="{{ route('admin.keluhan.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Keluhan
    </a>
</div>

<!-- Modal Validasi -->
<div class="modal fade" id="modalValidasi" tabindex="-1" aria-labelledby="modalValidasiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalValidasiLabel">Konfirmasi Validasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin memvalidasi keluhan <strong>{{ $keluhan->kode_keluhan }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <form method="POST" action="{{ route('admin.keluhan.validasi', $keluhan) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">Ya, Validasi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tidak Valid -->
<div class="modal fade" id="modalTidakValid" tabindex="-1" aria-labelledby="modalTidakValidLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTidakValidLabel">Konfirmasi Tidak Valid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menandai keluhan <strong>{{ $keluhan->kode_keluhan }}</strong> sebagai tidak valid?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <form method="POST" action="{{ route('admin.keluhan.tidak_valid', $keluhan) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger">Ya, Tandai Tidak Valid</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
