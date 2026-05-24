@extends('layouts.app')

@section('title', 'Buat Ticket - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Buat Ticket</h1>
        <p class="text-muted mb-0">Buat ticket dari keluhan yang valid.</p>
    </div>
    <a href="{{ route('admin.keluhan.show', $keluhan) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Ringkasan Keluhan</h5>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="text-muted small">Kode Keluhan</div>
                <div class="fw-semibold">{{ $keluhan->kode_keluhan }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Nama Pelapor</div>
                <div class="fw-semibold">{{ $keluhan->nama_pelapor }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Kategori</div>
                <div class="fw-semibold">{{ $keluhan->kategori?->nama_kategori ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Prioritas Awal</div>
                <div class="fw-semibold">{{ ucfirst($keluhan->prioritas_awal) }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Tanggal Keluhan</div>
                <div class="fw-semibold">{{ \Illuminate\Support\Carbon::parse($keluhan->tanggal_keluhan)->format('d-m-Y') }}</div>
            </div>
            <div class="col-12">
                <div class="text-muted small">Deskripsi</div>
                <div class="fw-semibold">{{ $keluhan->deskripsi_keluhan }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.keluhan.store_ticket', $keluhan) }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="kategori_id" class="form-label">Kategori Masalah</label>
                    <select name="kategori_id" id="kategori_id" class="form-select" required>
                        <option value="">Pilih kategori</option>
                        @foreach ($kategoriList as $kategori)
                            <option value="{{ $kategori->id }}" @selected((string) old('kategori_id', $keluhan->kategori_id) === (string) $kategori->id)>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="prioritas" class="form-label">Prioritas</label>
                    <select name="prioritas" id="prioritas" class="form-select" required>
                        <option value="">Pilih prioritas</option>
                        @foreach (['rendah', 'sedang', 'tinggi', 'darurat'] as $prioritas)
                            <option value="{{ $prioritas }}" @selected(old('prioritas', $keluhan->prioritas_awal) === $prioritas)>
                                {{ ucfirst($prioritas) }}
                            </option>
                        @endforeach
                    </select>
                    @error('prioritas')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="teknisi_id" class="form-label">Teknisi (Opsional)</label>
                    <select name="teknisi_id" id="teknisi_id" class="form-select">
                        <option value="">Belum ditentukan</option>
                        @foreach ($teknisiList as $teknisi)
                            <option value="{{ $teknisi->id }}" @selected((string) old('teknisi_id') === (string) $teknisi->id)>
                                {{ $teknisi->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teknisi_id')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('admin.keluhan.show', $keluhan) }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Ticket</button>
            </div>
        </form>
    </div>
</div>
@endsection
