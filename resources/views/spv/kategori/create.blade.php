{{-- Modul 13 - SPV Kategori Masalah --}}
{{-- Ringkas: form tambah kategori. --}}
@extends('layouts.app')

@section('title', 'Tambah Kategori - SPV')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Tambah Kategori</h1>
        <p class="text-muted mb-0">Masukkan data kategori masalah baru.</p>
    </div>
    <a href="{{ route('spv.kategori.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('spv.kategori.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label for="nama_kategori" class="form-label">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" value="{{ old('nama_kategori') }}" required>
                </div>
                <div class="col-12 col-md-6">
                    <label for="status_kategori" class="form-label">Status</label>
                    <select name="status_kategori" id="status_kategori" class="form-select" required>
                        <option value="">Pilih status</option>
                        <option value="aktif" @selected(old('status_kategori') === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(old('status_kategori') === 'nonaktif')>Nonaktif</option>
                    </select>
                </div>
                <div class="col-12 col-md-12">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4">{{ old('deskripsi') }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('spv.kategori.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>
@endsection
