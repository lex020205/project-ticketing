{{-- Modul 7 - SPV Keputusan Eskalasi --}}
{{-- Ringkas: detail eskalasi dan keputusan SPV. --}}
@extends('layouts.app')

@section('title', 'Detail Eskalasi - SPV')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Detail Eskalasi</h1>
        <p class="text-muted mb-0">Informasi eskalasi dan ticket terkait.</p>
    </div>
    <a href="{{ route('spv.eskalasi.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $jenisLabel = [
        'butuh_bantuan' => 'Butuh Bantuan Teknisi Lain',
        'butuh_alat' => 'Butuh Alat / Sparepart',
        'dialihkan' => 'Minta Dialihkan ke Teknisi Lain',
        'keputusan_spv' => 'Butuh Keputusan SPV',
    ];
    $statusBadge = [
        'menunggu' => 'warning',
        'disetujui' => 'success',
        'ditolak' => 'danger',
        'selesai' => 'secondary',
    ];
    $prioritasBadge = [
        'rendah' => 'secondary',
        'sedang' => 'info',
        'tinggi' => 'warning',
        'darurat' => 'danger',
    ];
    $badgeClass = $statusBadge[$eskalasi->status_eskalasi] ?? 'secondary';
@endphp

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Data Eskalasi</h5>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="text-muted small">Jenis Eskalasi</div>
                <div class="fw-semibold">{{ $jenisLabel[$eskalasi->jenis_eskalasi] ?? $eskalasi->jenis_eskalasi }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Status Eskalasi</div>
                <span class="badge bg-{{ $badgeClass }} text-dark">
                    {{ str_replace('_', ' ', $eskalasi->status_eskalasi) }}
                </span>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Teknisi Pengaju</div>
                <div class="fw-semibold">{{ $eskalasi->pengaju?->name ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Tanggal Pengajuan</div>
                <div class="fw-semibold">{{ $eskalasi->created_at?->format('d-m-Y H:i') ?? '-' }}</div>
            </div>
            <div class="col-12">
                <div class="text-muted small">Alasan</div>
                <div class="fw-semibold">{{ $eskalasi->alasan }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Data Ticket</h5>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="text-muted small">Kode Ticket</div>
                <div class="fw-semibold">{{ $eskalasi->ticket?->kode_ticket ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Kategori</div>
                <div class="fw-semibold">{{ $eskalasi->ticket?->kategori?->nama_kategori ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Prioritas</div>
                @php
                    $prioritas = $eskalasi->ticket?->prioritas;
                    $prioritasClass = $prioritas ? ($prioritasBadge[$prioritas] ?? 'secondary') : 'secondary';
                @endphp
                <span class="badge bg-{{ $prioritasClass }}">{{ $prioritas ? ucfirst($prioritas) : '-' }}</span>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Status Ticket</div>
                <div class="fw-semibold">{{ $eskalasi->ticket?->status_ticket ? str_replace('_', ' ', $eskalasi->ticket->status_ticket) : '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Teknisi Saat Ini</div>
                <div class="fw-semibold">{{ $eskalasi->ticket?->teknisi?->name ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Data Keluhan</h5>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="text-muted small">Nama Pelapor</div>
                <div class="fw-semibold">{{ $eskalasi->ticket?->keluhan?->nama_pelapor ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Jenis Pelapor</div>
                <div class="fw-semibold">{{ $eskalasi->ticket?->keluhan?->jenis_pelapor ? ucfirst($eskalasi->ticket->keluhan->jenis_pelapor) : '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Kontak Pelapor</div>
                <div class="fw-semibold">{{ $eskalasi->ticket?->keluhan?->kontak_pelapor ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Lokasi Keluhan</div>
                <div class="fw-semibold">{{ $eskalasi->ticket?->keluhan?->lokasi_keluhan ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Detail Lokasi</div>
                <div class="fw-semibold">{{ $eskalasi->ticket?->keluhan?->detail_lokasi ?? '-' }}</div>
            </div>
            <div class="col-12">
                <div class="text-muted small">Deskripsi Keluhan</div>
                <div class="fw-semibold">{{ $eskalasi->ticket?->keluhan?->deskripsi_keluhan ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>

@if ($eskalasi->status_eskalasi === 'menunggu')
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Keputusan SPV</h5>
            <form method="POST" action="{{ route('spv.eskalasi.keputusan', $eskalasi) }}">
                @csrf
                @method('PATCH')
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="keputusan_status" class="form-label">Keputusan</label>
                        <select name="keputusan_status" id="keputusan_status" class="form-select" required>
                            <option value="">Pilih keputusan</option>
                            @foreach (['disetujui' => 'Disetujui', 'ditolak' => 'Ditolak', 'selesai' => 'Selesai'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('keputusan_status') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('keputusan_status')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="status_ticket_baru" class="form-label">Status Ticket Baru</label>
                        <select name="status_ticket_baru" id="status_ticket_baru" class="form-select" required>
                            <option value="">Pilih status ticket</option>
                            @foreach (['sedang_dikerjakan' => 'Sedang dikerjakan', 'menunggu_alat' => 'Menunggu alat', 'ditugaskan' => 'Ditugaskan', 'dibatalkan' => 'Dibatalkan'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('status_ticket_baru') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status_ticket_baru')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="teknisi_baru_id" class="form-label">Teknisi Baru (Opsional)</label>
                        <select name="teknisi_baru_id" id="teknisi_baru_id" class="form-select">
                            <option value="">Tidak dialihkan</option>
                            @foreach ($teknisiList as $teknisi)
                                <option value="{{ $teknisi->id }}" @selected((string) old('teknisi_baru_id') === (string) $teknisi->id)>{{ $teknisi->name }}</option>
                            @endforeach
                        </select>
                        @error('teknisi_baru_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="catatan_keputusan" class="form-label">Catatan Keputusan</label>
                        <textarea name="catatan_keputusan" id="catatan_keputusan" class="form-control" rows="3" required>{{ old('catatan_keputusan') }}</textarea>
                        @error('catatan_keputusan')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Simpan Keputusan</button>
                </div>
            </form>
        </div>
    </div>
@else
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Keputusan SPV</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="text-muted small">Status Eskalasi</div>
                    <span class="badge bg-{{ $badgeClass }} text-dark">
                        {{ str_replace('_', ' ', $eskalasi->status_eskalasi) }}
                    </span>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Diputuskan Oleh</div>
                    <div class="fw-semibold">{{ $eskalasi->pemutus?->name ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Waktu Update</div>
                    <div class="fw-semibold">{{ $eskalasi->updated_at?->format('d-m-Y H:i') ?? '-' }}</div>
                </div>
                <div class="col-12">
                    <div class="text-muted small">Keputusan</div>
                    <div class="fw-semibold">{{ $eskalasi->keputusan ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Riwayat Progress Ticket</h5>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>User</th>
                        <th>Status Sebelumnya</th>
                        <th>Status Baru</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($progressList as $progress)
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
@endsection
