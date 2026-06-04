{{-- Modul 15 - Teknisi Riwayat Pengerjaan --}}
{{-- Ringkas: daftar progress teknisi. --}}
@extends('layouts.app')

@section('title', 'Riwayat Pengerjaan - Teknisi')

@section('content')
@php
    $statusBadge = [
        'menunggu_penugasan' => 'secondary',
        'ditugaskan' => 'info',
        'sedang_dikerjakan' => 'primary',
        'menunggu_alat' => 'warning',
        'eskalasi' => 'danger',
        'menunggu_verifikasi' => 'warning',
        'ditutup' => 'success',
        'dibatalkan' => 'dark',
    ];
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Riwayat Pengerjaan</h1>
        <p class="text-muted mb-0">Riwayat progress pekerjaan yang Anda buat.</p>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kode Ticket</th>
                        <th>Catatan</th>
                        <th>Status Sebelumnya</th>
                        <th>Status Baru</th>
                        <th>Waktu Progress</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($progressList as $progress)
                        @php
                            $statusSebelumnya = $progress->status_sebelumnya;
                            $statusBaru = $progress->status_baru;
                            $statusSebelumnyaClass = $statusBadge[$statusSebelumnya] ?? 'secondary';
                            $statusBaruClass = $statusBadge[$statusBaru] ?? 'secondary';
                        @endphp
                        <tr>
                            <td>{{ $progress->ticket?->kode_ticket ?? '-' }}</td>
                            <td>{{ $progress->catatan }}</td>
                            <td>
                                <span class="badge bg-{{ $statusSebelumnyaClass }}">
                                    {{ $statusSebelumnya ? str_replace('_', ' ', $statusSebelumnya) : '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $statusBaruClass }}">
                                    {{ $statusBaru ? str_replace('_', ' ', $statusBaru) : '-' }}
                                </span>
                            </td>
                            <td>{{ $progress->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                            <td class="text-end">
                                @if ($progress->ticket)
                                    <a href="{{ route('teknisi.tickets.show', $progress->ticket) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada progress.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
