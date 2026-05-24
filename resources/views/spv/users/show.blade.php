@extends('layouts.app')

@section('title', 'Detail User - SPV')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Detail User</h1>
        <p class="text-muted mb-0">Informasi lengkap user.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('spv.users.edit', $user) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil-square"></i> Edit
        </a>
        <a href="{{ route('spv.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@php
    $roleBadge = [
        'Admin' => 'primary',
        'SPV' => 'warning',
        'Teknisi' => 'info',
    ];
    $statusBadge = [
        'aktif' => 'success',
        'nonaktif' => 'secondary',
    ];
    $roleName = $user->role?->nama_role ?? '-';
    $roleClass = $roleBadge[$roleName] ?? 'secondary';
    $statusClass = $statusBadge[$user->status_user] ?? 'secondary';
@endphp

<div class="card shadow-sm">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="text-muted small">Nama</div>
                <div class="fw-semibold">{{ $user->name }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Email</div>
                <div class="fw-semibold">{{ $user->email }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Role</div>
                <span class="badge bg-{{ $roleClass }}">{{ $roleName }}</span>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Nomor Telepon</div>
                <div class="fw-semibold">{{ $user->nomor_telepon ?? '-' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Status</div>
                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($user->status_user) }}</span>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Tanggal Dibuat</div>
                <div class="fw-semibold">{{ $user->created_at?->format('d-m-Y H:i') ?? '-' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Tanggal Diperbarui</div>
                <div class="fw-semibold">{{ $user->updated_at?->format('d-m-Y H:i') ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
