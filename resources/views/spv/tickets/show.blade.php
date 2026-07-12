{{-- Modul 9 - SPV Monitoring Semua Ticket --}}
{{-- Ringkas: detail ticket dan aksi SPV. --}}
@extends('layouts.app')

@section('title', 'Detail Ticket - SPV')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Detail Ticket</h1>
        <p class="text-muted mb-0">Monitoring lengkap ticket dan tindakan SPV.</p>
    </div>
    <a href="{{ route('spv.tickets.index') }}" class="btn btn-outline-secondary">
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
        'sedang' => 'primary',
        'tinggi' => 'warning',
        'darurat' => 'danger',
    ];
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
    $statusEskalasiBadge = [
        'menunggu' => 'warning',
        'disetujui' => 'success',
        'ditolak' => 'danger',
        'selesai' => 'secondary',
    ];
    $prioritas = $ticket->prioritas;
    $prioritasClass = $prioritas ? ($prioritasBadge[$prioritas] ?? 'secondary') : 'secondary';
    $statusClass = $statusBadge[$ticket->status_ticket] ?? 'secondary';
    $statusTextClass = in_array($ticket->status_ticket, ['menunggu_alat', 'menunggu_verifikasi'], true) ? 'text-dark' : '';
    $jenisLabel = [
        'butuh_bantuan' => 'Butuh Bantuan Teknisi Lain',
        'butuh_alat' => 'Butuh Alat / Sparepart',
        'dialihkan' => 'Minta Dialihkan ke Teknisi Lain',
        'keputusan_spv' => 'Butuh Keputusan SPV',
    ];
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
                <span class="badge bg-{{ $statusClass }} {{ $statusTextClass }}">{{ str_replace('_', ' ', $ticket->status_ticket) }}</span>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Prioritas</div>
                <span class="badge bg-{{ $prioritasClass }}">{{ $prioritas ? ucfirst($prioritas) : '-' }}</span>
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
                <div class="text-muted small">Verified By</div>
                <div class="fw-semibold">{{ $ticket->verifiedBy?->name ?? '-' }}</div>
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
            <div class="col-md-4">
                <div class="text-muted small">Tanggal Ditutup</div>
                <div class="fw-semibold">{{ $ticket->tanggal_ditutup ? \Illuminate\Support\Carbon::parse($ticket->tanggal_ditutup)->format('d-m-Y H:i') : '-' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
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
                <div class="text-muted small">Detail Lokasi</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->detail_lokasi ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Prioritas Awal</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->prioritas_awal ? ucfirst($ticket->keluhan->prioritas_awal) : '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Status Keluhan</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->status_keluhan ? str_replace('_', ' ', $ticket->keluhan->status_keluhan) : '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Tanggal Keluhan</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->tanggal_keluhan ? \Illuminate\Support\Carbon::parse($ticket->keluhan->tanggal_keluhan)->format('d-m-Y') : '-' }}</div>
            </div>
            <div class="col-12">
                <div class="text-muted small">Deskripsi Keluhan</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->deskripsi_keluhan ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Ubah Prioritas</h5>
                <form method="POST" action="{{ route('spv.tickets.prioritas', $ticket) }}">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="prioritas" class="form-label">Prioritas</label>
                        <select name="prioritas" id="prioritas" class="form-select" required>
                            <option value="">Pilih prioritas</option>
                            @foreach (['rendah', 'sedang', 'tinggi', 'darurat'] as $value)
                                <option value="{{ $value }}" @selected(old('prioritas', $ticket->prioritas) === $value)>{{ ucfirst($value) }}</option>
                            @endforeach
                        </select>
                        @error('prioritas')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan Prioritas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Assign Ulang Teknisi</h5>
                <form method="POST" action="{{ route('spv.tickets.assign_teknisi', $ticket) }}">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="teknisi_id" class="form-label">Teknisi Baru</label>
                        <select name="teknisi_id" id="teknisi_id" class="form-select" required>
                            <option value="">Pilih teknisi</option>
                            @foreach ($teknisiList as $teknisi)
                                <option value="{{ $teknisi->id }}" @selected((string) old('teknisi_id') === (string) $teknisi->id)>{{ $teknisi->name }}</option>
                            @endforeach
                        </select>
                        @error('teknisi_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="catatan_assign" class="form-label">Catatan Assign</label>
                        <textarea name="catatan_assign" id="catatan_assign" class="form-control" rows="3" required>{{ old('catatan_assign') }}</textarea>
                        @error('catatan_assign')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan Assign</button>
                    </div>
                </form>
            </div>
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

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Lampiran</h5>
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

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Riwayat Eskalasi</h5>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Jenis</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th>Diajukan Oleh</th>
                        <th>Diputuskan Oleh</th>
                        <th>Keputusan</th>
                        <th>Waktu Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ticket->eskalasi as $eskalasi)
                        @php
                            $statusClass = $statusEskalasiBadge[$eskalasi->status_eskalasi] ?? 'secondary';
                            $statusTextClass = $eskalasi->status_eskalasi === 'menunggu' ? 'text-dark' : '';
                        @endphp
                        <tr>
                            <td>{{ $jenisLabel[$eskalasi->jenis_eskalasi] ?? $eskalasi->jenis_eskalasi }}</td>
                            <td>{{ $eskalasi->alasan }}</td>
                            <td>
                                <span class="badge bg-{{ $statusClass }} {{ $statusTextClass }}">{{ str_replace('_', ' ', $eskalasi->status_eskalasi) }}</span>
                            </td>
                            <td>{{ $eskalasi->pengaju?->name ?? '-' }}</td>
                            <td>{{ $eskalasi->pemutus?->name ?? '-' }}</td>
                            <td>{{ $eskalasi->keputusan ?? '-' }}</td>
                            <td>{{ $eskalasi->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">Belum ada eskalasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Riwayat Verifikasi</h5>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Hasil</th>
                        <th>Catatan</th>
                        <th>Verifier</th>
                        <th>Tanggal Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ticket->verifikasi as $verifikasi)
                        <tr>
                            <td>{{ ucfirst($verifikasi->hasil_verifikasi) }}</td>
                            <td>{{ $verifikasi->catatan_verifikasi ?? '-' }}</td>
                            <td>{{ $verifikasi->verifier?->name ?? '-' }}</td>
                            <td>{{ $verifikasi->tanggal_verifikasi ? \Illuminate\Support\Carbon::parse($verifikasi->tanggal_verifikasi)->format('d-m-Y H:i') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">Belum ada verifikasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
