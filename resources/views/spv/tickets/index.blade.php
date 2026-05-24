@extends('layouts.app')

@section('title', 'Monitoring Ticket - SPV')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Monitoring Ticket</h1>
        <p class="text-muted mb-0">Pantau semua ticket dan filter berdasarkan kebutuhan.</p>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@php
    $statusOptions = [
        'menunggu_penugasan' => 'Menunggu penugasan',
        'ditugaskan' => 'Ditugaskan',
        'sedang_dikerjakan' => 'Sedang dikerjakan',
        'menunggu_alat' => 'Menunggu alat',
        'eskalasi' => 'Eskalasi',
        'menunggu_verifikasi' => 'Menunggu verifikasi',
        'ditutup' => 'Ditutup',
        'dibatalkan' => 'Dibatalkan',
    ];
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
        <form method="GET" action="{{ route('spv.tickets.index') }}">
            <div class="row g-3">
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
                <div class="col-md-3">
                    <label for="kategori_id" class="form-label">Kategori</label>
                    <select name="kategori_id" id="kategori_id" class="form-select">
                        <option value="">Semua kategori</option>
                        @foreach ($kategoriList as $kategori)
                            <option value="{{ $kategori->id }}" @selected((string) request('kategori_id') === (string) $kategori->id)>{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="teknisi_id" class="form-label">Teknisi</label>
                    <select name="teknisi_id" id="teknisi_id" class="form-select">
                        <option value="">Semua teknisi</option>
                        @foreach ($teknisiList as $teknisi)
                            <option value="{{ $teknisi->id }}" @selected((string) request('teknisi_id') === (string) $teknisi->id)>{{ $teknisi->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="keyword" class="form-label">Pencarian</label>
                    <input type="text" name="keyword" id="keyword" class="form-control" value="{{ request('keyword') }}" placeholder="Kode ticket, kode keluhan, nama pelapor, lokasi, deskripsi">
                </div>
                <div class="col-md-6 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    <a href="{{ route('spv.tickets.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kode Ticket</th>
                        <th>Kode Keluhan</th>
                        <th>Nama Pelapor</th>
                        <th>Kategori</th>
                        <th>Teknisi</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Tanggal Ditugaskan</th>
                        <th>Tanggal Selesai</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        @php
                            $prioritas = $ticket->prioritas;
                            $prioritasClass = $prioritas ? ($prioritasBadge[$prioritas] ?? 'secondary') : 'secondary';
                            $statusClass = $statusBadge[$ticket->status_ticket] ?? 'secondary';
                            $statusTextClass = in_array($ticket->status_ticket, ['menunggu_alat', 'menunggu_verifikasi'], true) ? 'text-dark' : '';
                        @endphp
                        <tr>
                            <td>{{ $ticket->kode_ticket }}</td>
                            <td>{{ $ticket->keluhan?->kode_keluhan ?? '-' }}</td>
                            <td>{{ $ticket->keluhan?->nama_pelapor ?? '-' }}</td>
                            <td>{{ $ticket->kategori?->nama_kategori ?? '-' }}</td>
                            <td>{{ $ticket->teknisi?->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $prioritasClass }}">{{ $prioritas ? ucfirst($prioritas) : '-' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $statusClass }} {{ $statusTextClass }}">
                                    {{ str_replace('_', ' ', $ticket->status_ticket) }}
                                </span>
                            </td>
                            <td>{{ $ticket->tanggal_ditugaskan ? \Illuminate\Support\Carbon::parse($ticket->tanggal_ditugaskan)->format('d-m-Y H:i') : '-' }}</td>
                            <td>{{ $ticket->tanggal_selesai ? \Illuminate\Support\Carbon::parse($ticket->tanggal_selesai)->format('d-m-Y H:i') : '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('spv.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">Belum ada ticket.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
