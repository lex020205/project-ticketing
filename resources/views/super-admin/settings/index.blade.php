@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="mb-4">
        <span class="page-kicker"><i class="bi bi-gear"></i> Pengaturan Sistem</span>
        <h1 class="page-title mb-2">Pengaturan Program</h1>
        <p class="page-subtitle mb-0">Tempat menyimpan konfigurasi global seperti nama aplikasi, notifikasi, dan batas operasional sistem.</p>
    </div>

    @if (! $tableReady)
        <div class="alert alert-warning">
            Tabel <strong>system_settings</strong> belum tersedia di database aktif. Jalankan migrasi supaya menu ini bisa dipakai penuh.
        </div>
    @endif

    <div class="surface-card p-4">
        @forelse($settings as $setting)
            <div class="d-flex justify-content-between align-items-start border-bottom py-3">
                <div>
                    <div class="fw-semibold">{{ $setting->key }}</div>
                    <div class="text-muted small">{{ $setting->description ?? 'Tidak ada deskripsi.' }}</div>
                </div>
                <div class="text-end">
                    <div class="fw-semibold">{{ $setting->value ?? '-' }}</div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-4">
                Belum ada pengaturan yang tersimpan.
            </div>
        @endforelse
    </div>
</div>
@endsection
