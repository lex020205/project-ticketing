@extends('layouts.app')

@section('title', 'Daftar Eskalasi - SPV')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Daftar Eskalasi</h1>
        <p class="text-muted mb-0">Eskalasi ticket yang menunggu keputusan.</p>
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
                        <th>Teknisi Pengaju</th>
                        <th>Jenis Eskalasi</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th>Prioritas</th>
                        <th>Tanggal Pengajuan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $jenisLabel = [
                            'butuh_bantuan' => 'Butuh Bantuan Teknisi Lain',
                            'butuh_alat' => 'Butuh Alat / Sparepart',
                            'dialihkan' => 'Minta Dialihkan ke Teknisi Lain',
                            'keputusan_spv' => 'Butuh Keputusan SPV',
                        ];
                        $prioritasBadge = [
                            'rendah' => 'secondary',
                            'sedang' => 'info',
                            'tinggi' => 'warning',
                            'darurat' => 'danger',
                        ];
                    @endphp
                    @forelse ($eskalasiList as $eskalasi)
                        <tr>
                            <td>{{ $eskalasi->ticket?->kode_ticket ?? '-' }}</td>
                            <td>{{ $eskalasi->ticket?->keluhan?->nama_pelapor ?? '-' }}</td>
                            <td>{{ $eskalasi->pengaju?->name ?? '-' }}</td>
                            <td>{{ $jenisLabel[$eskalasi->jenis_eskalasi] ?? $eskalasi->jenis_eskalasi }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($eskalasi->alasan, 50) }}</td>
                            <td>
                                <span class="badge bg-warning text-dark">menunggu</span>
                            </td>
                            <td>
                                @php
                                    $prioritas = $eskalasi->ticket?->prioritas;
                                    $badgeClass = $prioritas ? ($prioritasBadge[$prioritas] ?? 'secondary') : 'secondary';
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}">{{ $prioritas ? ucfirst($prioritas) : '-' }}</span>
                            </td>
                            <td>{{ $eskalasi->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('spv.eskalasi.show', $eskalasi) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Belum ada eskalasi menunggu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
