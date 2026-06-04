{{-- Modul 11 - Laporan Admin dan SPV --}}
{{-- Ringkas: laporan dan filter ticket (admin). --}}
@extends('layouts.app')

@section('title', 'Laporan Admin - Sistem Ticketing Laboran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Laporan Admin</h1>
        <p class="text-muted mb-0">Ringkasan ticket dan detail laporan berdasarkan filter.</p>
    </div>
</div>

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
@endphp

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.laporan.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                    <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
                </div>
                <div class="col-md-3">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-md-3">
                    <label for="status_ticket" class="form-label">Status Ticket</label>
                    <select name="status_ticket" id="status_ticket" class="form-select">
                        <option value="">Semua status</option>
                        @foreach ($statusOptions as $value => $label)
                            <option value="{{ $value }}" @selected(request('status_ticket') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="prioritas" class="form-label">Prioritas</label>
                    <select name="prioritas" id="prioritas" class="form-select">
                        <option value="">Semua prioritas</option>
                        @foreach (['rendah', 'sedang', 'tinggi', 'darurat'] as $prioritas)
                            <option value="{{ $prioritas }}" @selected(request('prioritas') === $prioritas)>{{ ucfirst($prioritas) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="kategori_id" class="form-label">Kategori</label>
                    <select name="kategori_id" id="kategori_id" class="form-select">
                        <option value="">Semua kategori</option>
                        @foreach ($kategoriList as $kategori)
                            <option value="{{ $kategori->id }}" @selected((string) request('kategori_id') === (string) $kategori->id)>{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="teknisi_id" class="form-label">Teknisi</label>
                    <select name="teknisi_id" id="teknisi_id" class="form-select">
                        <option value="">Semua teknisi</option>
                        @foreach ($teknisiList as $teknisi)
                            <option value="{{ $teknisi->id }}" @selected((string) request('teknisi_id') === (string) $teknisi->id)>{{ $teknisi->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-2">
        <div class="card shadow-sm report-card">
            <div class="card-body">
                <div class="text-muted small">Total Ticket</div>
                <div class="fs-4 fw-bold">{{ $summary['total'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-2">
        <div class="card shadow-sm report-card">
            <div class="card-body">
                <div class="text-muted small">Menunggu Penugasan</div>
                <div class="fs-4 fw-bold">{{ $summary['menunggu_penugasan'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-2">
        <div class="card shadow-sm report-card">
            <div class="card-body">
                <div class="text-muted small">Sedang Dikerjakan</div>
                <div class="fs-4 fw-bold">{{ $summary['sedang_dikerjakan'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-2">
        <div class="card shadow-sm report-card">
            <div class="card-body">
                <div class="text-muted small">Menunggu Verifikasi</div>
                <div class="fs-4 fw-bold">{{ $summary['menunggu_verifikasi'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-2">
        <div class="card shadow-sm report-card">
            <div class="card-body">
                <div class="text-muted small">Ticket Ditutup</div>
                <div class="fs-4 fw-bold">{{ $summary['ditutup'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-2">
        <div class="card shadow-sm report-card">
            <div class="card-body">
                <div class="text-muted small">Ticket Eskalasi</div>
                <div class="fs-4 fw-bold">{{ $summary['eskalasi'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0 fw-bold">Ringkasan Ticket</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kode Ticket</th>
                        <th>Nama Pelapor</th>
                        <th>Kategori</th>
                        <th>Teknisi</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Tanggal Ditugaskan</th>
                        <th>Tanggal Selesai</th>
                        <th>Tanggal Ditutup</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        @php
                            $prioritas = $ticket->prioritas;
                            $prioritasClass = $prioritas ? ($prioritasBadge[$prioritas] ?? 'secondary') : 'secondary';
                            $statusClass = $statusBadge[$ticket->status_ticket] ?? 'secondary';
                            $statusTextClass = in_array($ticket->status_ticket, ['menunggu_alat', 'menunggu_verifikasi'], true) ? 'text-dark' : '';
                            $statusLabel = $statusOptions[$ticket->status_ticket] ?? str_replace('_', ' ', $ticket->status_ticket);
                        @endphp
                        <tr>
                            <td>{{ $ticket->kode_ticket }}</td>
                            <td>{{ $ticket->keluhan?->nama_pelapor ?? '-' }}</td>
                            <td>{{ $ticket->kategori?->nama_kategori ?? '-' }}</td>
                            <td>{{ $ticket->teknisi?->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $prioritasClass }}">{{ $prioritas ? ucfirst($prioritas) : '-' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $statusClass }} {{ $statusTextClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td>{{ $ticket->tanggal_ditugaskan ? \Illuminate\Support\Carbon::parse($ticket->tanggal_ditugaskan)->format('d-m-Y H:i') : '-' }}</td>
                            <td>{{ $ticket->tanggal_selesai ? \Illuminate\Support\Carbon::parse($ticket->tanggal_selesai)->format('d-m-Y H:i') : '-' }}</td>
                            <td>{{ $ticket->tanggal_ditutup ? \Illuminate\Support\Carbon::parse($ticket->tanggal_ditutup)->format('d-m-Y H:i') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Belum ada ticket.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('extra_css')
<style>
    .report-card {
        border-left: 4px solid #2563eb;
    }
</style>
@endsection
