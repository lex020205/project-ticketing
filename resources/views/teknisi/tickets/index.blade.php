@extends('layouts.app')

@section('title', 'Ticket Saya - Teknisi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Ticket Saya</h1>
        <p class="text-muted mb-0">Daftar ticket yang ditugaskan kepada Anda.</p>
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
                        <th>Lokasi Keluhan</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Tanggal Ditugaskan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->kode_ticket }}</td>
                            <td>{{ $ticket->keluhan?->nama_pelapor ?? '-' }}</td>
                            <td>{{ $ticket->kategori?->nama_kategori ?? '-' }}</td>
                            <td>{{ $ticket->keluhan?->lokasi_keluhan ?? '-' }}</td>
                            <td>{{ ucfirst($ticket->prioritas) }}</td>
                            <td>
                                <span class="badge bg-{{ $ticket->status_ticket === 'ditugaskan' ? 'success' : 'secondary' }}">
                                    {{ str_replace('_', ' ', $ticket->status_ticket) }}
                                </span>
                            </td>
                            <td>
                                {{ $ticket->tanggal_ditugaskan ? \Illuminate\Support\Carbon::parse($ticket->tanggal_ditugaskan)->format('d-m-Y') : '-' }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('teknisi.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada ticket.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
