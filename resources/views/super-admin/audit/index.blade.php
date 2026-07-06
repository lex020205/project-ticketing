@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="mb-4">
        <span class="page-kicker"><i class="bi bi-journal-text"></i> Audit Log</span>
        <h1 class="page-title mb-2">Audit Log</h1>
        <p class="page-subtitle mb-0">Catatan aktivitas untuk jejak siapa melakukan apa, di modul mana, dan kapan itu terjadi.</p>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="surface-card p-4 h-100">
                <div class="text-muted small text-uppercase fw-semibold mb-2">Total Log</div>
                <div class="fs-2 fw-bold">{{ $totalLogs }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="surface-card p-4 h-100">
                <div class="text-muted small text-uppercase fw-semibold mb-2">Hari Ini</div>
                <div class="fs-2 fw-bold">{{ $todayLogs }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="surface-card p-4 h-100">
                <div class="text-muted small text-uppercase fw-semibold mb-2">Modul Teraktif</div>
                <div class="fw-bold">
                    {{ $moduleBreakdown->first()?->module ?? '-' }}
                </div>
                <div class="text-muted small">
                    {{ $moduleBreakdown->first()?->total ?? 0 }} aktivitas
                </div>
            </div>
        </div>
    </div>

    <div class="surface-card p-4 mb-3">
        <h5 class="fw-bold mb-3">Top Modul</h5>
        <div class="d-flex flex-wrap gap-2">
            @forelse ($moduleBreakdown as $module)
                <span class="badge text-bg-light border">
                    {{ $module->module }}: {{ $module->total }}
                </span>
            @empty
                <span class="text-muted">Belum ada data modul.</span>
            @endforelse
        </div>
    </div>

    <div class="surface-card p-4 mb-3">
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Aksi</th>
                        <th>Modul</th>
                        <th>Deskripsi</th>
                        <th>IP</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->user?->name ?? 'System' }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->module }}</td>
                        <td>{{ $log->description }}</td>
                        <td>{{ $log->ip_address ?? '-' }}</td>
                        <td>{{ $log->created_at?->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Audit log masih kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-end">
        {{ $logs->links() }}
    </div>
</div>
@endsection
