{{-- Modul 2 - Admin Keluhan --}}
{{-- Ringkas: form edit keluhan. --}}
@extends('layouts.app')

@section('title', 'Edit Keluhan - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Edit Keluhan</h1>
        <p class="text-muted mb-0">Perbarui detail keluhan.</p>
    </div>
    <a href="{{ route('admin.keluhan.show', $keluhan) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.keluhan.update', $keluhan) }}">
            @csrf
            @method('PUT')
            @include('admin.keluhan.partials.form', ['keluhan' => $keluhan])

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('admin.keluhan.show', $keluhan) }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Update Keluhan</button>
            </div>
        </form>
    </div>
</div>
@endsection
