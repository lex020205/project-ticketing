{{-- Modul 8 - Admin/SPV Verifikasi Ticket Selesai --}}
{{-- Ringkas: detail verifikasi ticket (SPV). --}}
@extends('layouts.app')

@section('title', 'Detail Verifikasi Ticket - SPV')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Detail Verifikasi Ticket</h1>
        <p class="text-muted mb-0">Verifikasi ticket yang menunggu konfirmasi.</p>
    </div>
    <a href="{{ route('spv.verifikasi.index') }}" class="btn btn-outline-secondary">
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
    $prioritasBadge = [
        'rendah' => 'secondary',
        'sedang' => 'info',
        'tinggi' => 'warning',
        'darurat' => 'danger',
    ];
    $prioritas = $ticket->prioritas;
    $prioritasClass = $prioritas ? ($prioritasBadge[$prioritas] ?? 'secondary') : 'secondary';
@endphp

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Data Ticket</h5>
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
                <span class="badge bg-{{ $prioritasClass }}">{{ $prioritas ? ucfirst($prioritas) : '-' }}</span>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Teknisi</div>
                <div class="fw-semibold">{{ $ticket->teknisi?->name ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Tanggal Ditugaskan</div>
                <div class="fw-semibold">{{ $ticket->tanggal_ditugaskan ? \Illuminate\Support\Carbon::parse($ticket->tanggal_ditugaskan)->format('d-m-Y H:i') : '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Tanggal Mulai</div>
                <div class="fw-semibold">{{ $ticket->tanggal_mulai ? \Illuminate\Support\Carbon::parse($ticket->tanggal_mulai)->format('d-m-Y H:i') : '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Tanggal Selesai</div>
                <div class="fw-semibold">{{ $ticket->tanggal_selesai ? \Illuminate\Support\Carbon::parse($ticket->tanggal_selesai)->format('d-m-Y H:i') : '-' }}</div>
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
                <div class="text-muted small">Detail Lokasi</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->detail_lokasi ?? '-' }}</div>
            </div>
            <div class="col-12">
                <div class="text-muted small">Deskripsi Keluhan</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->deskripsi_keluhan ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Lampiran Bukti</h5>
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
                    @forelse ($lampiranList as $lampiran)
                        @php
                            $extension = strtolower(pathinfo($lampiran->nama_file, PATHINFO_EXTENSION));
                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png'], true);
                            $fileUrl = \Illuminate\Support\Facades\Storage::url($lampiran->path_file);
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

<div class="card shadow-sm mb-4">
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

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Verifikasi Ticket</h5>
        @if ($ticket->status_ticket === 'menunggu_verifikasi')
            <form method="POST" action="{{ route('spv.verifikasi.store', $ticket) }}">
                @csrf
                @method('PATCH')
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="hasil_verifikasi" class="form-label">Hasil Verifikasi</label>
                        <select name="hasil_verifikasi" id="hasil_verifikasi" class="form-select" required>
                            <option value="">Pilih hasil</option>
                            @foreach (['diterima' => 'Diterima', 'dikembalikan' => 'Dikembalikan'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('hasil_verifikasi') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('hasil_verifikasi')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-8">
                        <label for="catatan_verifikasi" class="form-label">Catatan Verifikasi</label>
                        <textarea name="catatan_verifikasi" id="catatan_verifikasi" class="form-control" rows="3">{{ old('catatan_verifikasi') }}</textarea>
                        @error('catatan_verifikasi')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Simpan Verifikasi</button>
                </div>
            </form>
        @else
            <div class="text-muted small">Ticket belum siap diverifikasi.</div>
        @endif
    </div>
</div>
@endsection
