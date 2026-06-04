{{-- Modul 13 - SPV Kategori Masalah --}}
{{-- Ringkas: daftar kategori masalah. --}}
@extends('layouts.app')

@section('title', 'Kategori Masalah - SPV')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Kategori Masalah</h1>
        <p class="text-muted mb-0">Kelola kategori masalah untuk keluhan dan ticket.</p>
    </div>
    <a href="{{ route('spv.kategori.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Kategori
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('spv.kategori.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="status_kategori" class="form-label">Status</label>
                    <select name="status_kategori" id="status_kategori" class="form-select">
                        <option value="">Semua status</option>
                        <option value="aktif" @selected(request('status_kategori') === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(request('status_kategori') === 'nonaktif')>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label for="keyword" class="form-label">Pencarian</label>
                    <input type="text" name="keyword" id="keyword" class="form-control" value="{{ request('keyword') }}" placeholder="Nama kategori atau deskripsi">
                </div>
                <div class="col-md-12 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    <a href="{{ route('spv.kategori.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

@php
    $statusBadge = [
        'aktif' => 'success',
        'nonaktif' => 'secondary',
    ];
@endphp

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Jumlah Keluhan</th>
                        <th>Jumlah Ticket</th>
                        <th>Created At</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kategoriList as $kategori)
                        @php
                            $statusClass = $statusBadge[$kategori->status_kategori] ?? 'secondary';
                        @endphp
                        <tr>
                            <td>{{ $kategori->nama_kategori }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($kategori->deskripsi, 60) }}</td>
                            <td><span class="badge bg-{{ $statusClass }}">{{ ucfirst($kategori->status_kategori) }}</span></td>
                            <td>{{ $kategori->keluhans_count }}</td>
                            <td>{{ $kategori->tickets_count }}</td>
                            <td>{{ $kategori->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('spv.kategori.show', $kategori) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                    <a href="{{ route('spv.kategori.edit', $kategori) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#toggleStatus{{ $kategori->id }}">
                                        {{ $kategori->status_kategori === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@foreach ($kategoriList as $kategori)
    <div class="modal fade" id="toggleStatus{{ $kategori->id }}" tabindex="-1" aria-labelledby="toggleStatusLabel{{ $kategori->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="toggleStatusLabel{{ $kategori->id }}">Konfirmasi Ubah Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin {{ $kategori->status_kategori === 'aktif' ? 'menonaktifkan' : 'mengaktifkan' }} kategori <strong>{{ $kategori->nama_kategori }}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <form method="POST" action="{{ route('spv.kategori.toggle_status', $kategori) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-warning">Ya, Ubah Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
