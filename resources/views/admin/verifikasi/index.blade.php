{{-- Modul 8 - Admin/SPV Verifikasi Ticket Selesai --}}
{{-- Ringkas: daftar ticket menunggu verifikasi (admin). --}}
@extends('layouts.app')

@section('title', 'Verifikasi Ticket - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Verifikasi Ticket</h1>
        <p class="text-muted mb-0">Ticket yang menunggu verifikasi.</p>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm">
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
                        <th>Tanggal Selesai</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $prioritasBadge = [
                            'rendah' => 'secondary',
                            'sedang' => 'info',
                            'tinggi' => 'warning',
                            'darurat' => 'danger',
                        ];
                    @endphp
                    @forelse ($tickets as $ticket)
                        @php
                            $prioritas = $ticket->prioritas;
                            $badgeClass = $prioritas ? ($prioritasBadge[$prioritas] ?? 'secondary') : 'secondary';
                        @endphp
                        <tr>
                            <td>{{ $ticket->kode_ticket }}</td>
                            <td>{{ $ticket->keluhan?->nama_pelapor ?? '-' }}</td>
                            <td>{{ $ticket->kategori?->nama_kategori ?? '-' }}</td>
                            <td>{{ $ticket->teknisi?->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $badgeClass }}">{{ $prioritas ? ucfirst($prioritas) : '-' }}</span>
                            </td>
                            <td>{{ $ticket->tanggal_selesai ? \Illuminate\Support\Carbon::parse($ticket->tanggal_selesai)->format('d-m-Y H:i') : '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.verifikasi.show', $ticket) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada ticket menunggu verifikasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
