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
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kode Ticket</th>
                        <th>Catatan</th>
                        <th>Status Sebelumnya</th>
                        <th>Status Baru</th>
                        <th>Waktu Progress</th>
                        <th class="text-end pe-4">Aksi</th>
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
                            <td class="ps-4 fw-medium">{{ $progress->ticket?->kode_ticket ?? '-' }}</td>
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
                            <td class="text-end pe-4">
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

        <!-- Mobile view card list -->
        <div class="d-block d-md-none">
            <div class="list-group list-group-flush">
                @forelse ($progressList as $progress)
                    @php
                        $statusSebelumnya = $progress->status_sebelumnya;
                        $statusBaru = $progress->status_baru;
                        $statusSebelumnyaClass = $statusBadge[$statusSebelumnya] ?? 'secondary';
                        $statusBaruClass = $statusBadge[$statusBaru] ?? 'secondary';
                    @endphp
                    <div class="list-group-item p-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-primary">{{ $progress->ticket?->kode_ticket ?? '-' }}</span>
                            <span class="text-muted small" style="font-size:0.8rem;">
                                <i class="bi bi-clock me-1"></i>
                                {{ $progress->created_at?->format('d-m-Y H:i') ?? '-' }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <div class="text-muted small">Catatan Progres</div>
                            <div class="fw-semibold text-secondary" style="font-size:0.9rem;">{{ $progress->catatan }}</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                            <div class="d-flex align-items-center gap-2" style="font-size:0.8rem;">
                                <span class="badge bg-{{ $statusSebelumnyaClass }} opacity-75">{{ $statusSebelumnya ? str_replace('_', ' ', $statusSebelumnya) : '-' }}</span>
                                <i class="bi bi-arrow-right text-muted"></i>
                                <span class="badge bg-{{ $statusBaruClass }}">{{ $statusBaru ? str_replace('_', ' ', $statusBaru) : '-' }}</span>
                            </div>
                            @if ($progress->ticket)
                                <a href="{{ route('teknisi.tickets.show', $progress->ticket) }}" class="btn btn-sm btn-outline-primary px-3" style="font-size: 0.8rem; border-radius: 15px;">
                                    Detail <i class="bi bi-chevron-right ms-1"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">Belum ada progress.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
