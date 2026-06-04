{{-- Modul 1 - Auth, Role Access, dan Dashboard Awal --}}
{{-- Ringkas: halaman profil user login. --}}
@extends('layouts.app')

@section('title', 'Profil User')

@section('content')
@php
    $initials = collect(explode(' ', trim($user->name)))
        ->filter()
        ->map(function ($part) {
            return strtoupper(substr($part, 0, 1));
        })
        ->take(2)
        ->implode('');
@endphp

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                            style="width: 64px; height: 64px; font-size: 1.5rem; font-weight: 600;">
                            {{ $initials ?: '-' }}
                        </div>
                        <div>
                            <h4 class="mb-1">{{ $user->name }}</h4>
                            <div class="text-muted">{{ $user->email }}</div>
                            <div class="mt-2">
                                <span class="badge bg-secondary">{{ $user->role?->nama_role ?? '-' }}</span>
                                <span class="badge {{ $user->status_user === 'aktif' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->status_user === 'aktif' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">Nama</div>
                                <div class="fw-semibold">{{ $user->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">Email</div>
                                <div class="fw-semibold">{{ $user->email }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">Role</div>
                                <div class="fw-semibold">{{ $user->role?->nama_role ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">Nomor Telepon</div>
                                <div class="fw-semibold">{{ $user->nomor_telepon ?: '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">Status User</div>
                                <div class="fw-semibold">
                                    <span class="badge {{ $user->status_user === 'aktif' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $user->status_user === 'aktif' ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">Tanggal Akun Dibuat</div>
                                <div class="fw-semibold">{{ optional($user->created_at)->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end">
                        <a href="{{ route($dashboardRoute) }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i>
                            Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
