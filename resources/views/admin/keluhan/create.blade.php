@extends('layouts.app')

@section('title', 'Tambah Keluhan - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Tambah Keluhan</h1>
        <p class="text-muted mb-0">Masukkan detail keluhan dari pelapor.</p>
    </div>
    <a href="{{ route('admin.keluhan.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.keluhan.store') }}">
            @csrf
            @include('admin.keluhan.partials.form')

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('admin.keluhan.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Keluhan</button>
            </div>
        </form>
    </div>
</div>
@endsection
