{{-- Modul 2 - Admin Keluhan --}}
{{-- Ringkas: daftar keluhan dan statusnya. --}}
@extends('layouts.app')

@section('title', 'Daftar Keluhan - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Daftar Keluhan</h1>
        <p class="text-muted mb-0">Kelola keluhan yang masuk dari pelapor.</p>
    </div>
    <a href="{{ route('admin.keluhan.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Keluhan
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kode Keluhan</th>
                        <th>Nama Pelapor</th>
                        <th>Jenis Pelapor</th>
                        <th>Kategori</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($keluhans as $keluhan)
                        <tr>
                            <td>{{ $keluhan->kode_keluhan }}</td>
                            <td>{{ $keluhan->nama_pelapor }}</td>
                            <td>{{ ucfirst($keluhan->jenis_pelapor) }}</td>
                            <td>{{ $keluhan->kategori?->nama_kategori ?? '-' }}</td>
                            <td>{{ ucfirst($keluhan->prioritas_awal) }}</td>
                            <td>
                                <span class="badge bg-{{ $keluhan->status_keluhan === 'valid' ? 'success' : ($keluhan->status_keluhan === 'tidak_valid' ? 'danger' : 'secondary') }}">
                                    {{ str_replace('_', ' ', $keluhan->status_keluhan) }}
                                </span>
                            </td>
                            <td>{{ \Illuminate\Support\Carbon::parse($keluhan->tanggal_keluhan)->format('d-m-Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.keluhan.show', $keluhan) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                <a href="{{ route('admin.keluhan.edit', $keluhan) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada keluhan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
