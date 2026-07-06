@extends('layouts.app')

@section('title', 'Dashboard Super Admin - Sistem Ticketing Laboran')

@section('extra_css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.3.0/chart.min.css">
<style>
    .superadmin-dashboard {
        --bg: #f8fafc;
        --surface: #ffffff;
        --surface-soft: #f8fafc;
        --surface-muted: #f1f5f9;
        --border: #e2e8f0;
        --border-strong: #cbd5e1;
        --text: #0f172a;
        --muted: #64748b;
        --primary: #0f172a;
        --accent: #2563eb;
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        color: var(--text);
    }

    .superadmin-dashboard .container-fluid {
        padding-top: 0.25rem;
        padding-bottom: 0.5rem;
    }

    .superadmin-hero,
    .dashboard-panel,
    .metric-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    }

    .superadmin-hero {
        position: relative;
        overflow: hidden;
        padding: 1.25rem 1.35rem;
        background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);
    }

    .hero-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.32rem 0.7rem;
        border-radius: 999px;
        background: var(--surface-muted);
        color: var(--text);
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }

    .hero-title {
        margin: 0.85rem 0 0.5rem;
        font-size: clamp(1.5rem, 2.4vw, 2.4rem);
        font-weight: 800;
        letter-spacing: -0.04em;
        line-height: 1.15;
    }

    .hero-subtitle {
        margin: 0;
        max-width: 58rem;
        color: var(--muted);
        font-size: 0.96rem;
        line-height: 1.6;
    }

    .hero-meta {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        margin-top: 1rem;
        padding: 0.55rem 0.8rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 999px;
        color: #334155;
        font-size: 0.9rem;
    }

    .hero-meta-badge {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #111827;
        color: #fff;
        display: grid;
        place-items: center;
        font-weight: 700;
    }

    .section-label {
        margin: 0 0 0.35rem;
        color: var(--muted);
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .metric-card {
        padding: 1rem 1.05rem;
        display: flex;
        align-items: center;
        gap: 0.9rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .metric-card:hover {
        border-color: var(--border-strong);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
    }

    .metric-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        color: #fff;
        font-size: 1.1rem;
        flex: 0 0 auto;
    }

    .metric-icon-blue { background: #2563eb; }
    .metric-icon-green { background: #16a34a; }
    .metric-icon-amber { background: #d97706; }
    .metric-icon-slate { background: #475569; }
    .metric-icon-red { background: #dc2626; }

    .metric-value {
        margin: 0;
        font-size: 1.6rem;
        line-height: 1;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .metric-label {
        margin: 0.15rem 0 0;
        color: var(--muted);
        font-size: 0.85rem;
        font-weight: 500;
    }

    .dashboard-panel {
        overflow: hidden;
    }

    .panel-head {
        padding: 1rem 1.1rem;
        border-bottom: 1px solid var(--border);
        background: #ffffff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .panel-head h5 {
        margin: 0;
        font-size: 0.98rem;
        font-weight: 700;
        letter-spacing: -0.03em;
    }

    .panel-head p {
        margin: 0.25rem 0 0;
        color: var(--muted);
        font-size: 0.88rem;
    }

    .panel-body {
        padding: 0;
    }

    .dashboard-table {
        margin: 0;
    }

    .dashboard-table thead th {
        padding: 0.85rem 1rem;
        background: var(--surface-soft);
        color: #334155;
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    .dashboard-table tbody td {
        padding: 0.95rem 1rem;
        vertical-align: middle;
        color: var(--text);
        border-color: #edf2f7;
    }

    .dashboard-table tbody tr:hover {
        background: #fafafa;
    }

    .badge-soft {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.65rem;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 600;
        line-height: 1;
    }

    .badge-soft i {
        font-size: 0.6rem;
    }

    .badge-priority-rendah,
    .badge-status-ditutup,
    .badge-status-valid,
    .badge-status-selesai {
        background: #f0fdf4;
        color: #15803d;
    }

    .badge-priority-sedang,
    .badge-status-ditugaskan,
    .badge-status-menunggu_verifikasi,
    .badge-status-menunggu_alat {
        background: #fffbeb;
        color: #b45309;
    }

    .badge-priority-tinggi,
    .badge-status-sedang_dikerjakan,
    .badge-status-validasi {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .badge-priority-darurat,
    .badge-status-eskalasi,
    .badge-status-dibatalkan {
        background: #fef2f2;
        color: #b91c1c;
    }

    .badge-neutral {
        background: #f8fafc;
        color: #475569;
    }

    .btn-soft {
        border-radius: 999px;
        padding: 0.38rem 0.75rem;
        font-weight: 600;
        border: 1px solid var(--border);
        transition: all 0.2s ease;
    }

    .btn-soft:hover {
        background: var(--surface-soft);
    }

    .btn-soft-primary {
        background: #ffffff;
        color: #0f172a;
    }

    .btn-soft-primary:hover {
        color: #0f172a;
    }

    .btn-soft-warning {
        background: #ffffff;
        color: #0f172a;
    }

    .btn-soft-warning:hover {
        color: #0f172a;
    }

    .btn-soft-danger {
        background: #ffffff;
        color: #b91c1c;
    }

    .btn-soft-danger:hover {
        background: #fef2f2;
        color: #991b1b;
    }

    .chart-card {
        padding: 0.95rem 1rem 1rem;
    }

    .chart-wrap {
        height: 230px;
    }

    .empty-state {
        padding: 2rem 1rem;
        text-align: center;
        color: var(--muted);
    }

    @media (max-width: 768px) {
        .superadmin-hero,
        .panel-head,
        .chart-card {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .hero-title {
            font-size: 1.45rem;
        }

        .metric-value {
            font-size: 1.45rem;
        }

        .panel-head {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endsection

@section('content')
@php
    $priorityLabels = [
        'rendah' => 'Rendah',
        'sedang' => 'Sedang',
        'tinggi' => 'Tinggi',
        'darurat' => 'Darurat',
    ];

    $priorityClass = [
        'rendah' => 'badge-priority-rendah',
        'sedang' => 'badge-priority-sedang',
        'tinggi' => 'badge-priority-tinggi',
        'darurat' => 'badge-priority-darurat',
    ];

    $statusLabels = [
        'menunggu_penugasan' => 'Menunggu Penugasan',
        'ditugaskan' => 'Ditugaskan',
        'sedang_dikerjakan' => 'Sedang Dikerjakan',
        'menunggu_alat' => 'Menunggu Alat',
        'menunggu_verifikasi' => 'Menunggu Verifikasi',
        'ditutup' => 'Ditutup',
        'eskalasi' => 'Eskalasi',
        'dibatalkan' => 'Dibatalkan',
    ];

    $statusClass = [
        'menunggu_penugasan' => 'badge-neutral',
        'ditugaskan' => 'badge-status-ditugaskan',
        'sedang_dikerjakan' => 'badge-status-sedang_dikerjakan',
        'menunggu_alat' => 'badge-status-menunggu_alat',
        'menunggu_verifikasi' => 'badge-status-menunggu_verifikasi',
        'ditutup' => 'badge-status-ditutup',
        'eskalasi' => 'badge-status-eskalasi',
        'dibatalkan' => 'badge-status-dibatalkan',
    ];

    $metricCards = [
        ['label' => 'Total User', 'value' => $totalUser, 'icon' => 'bi-people', 'class' => 'metric-icon-blue'],
        ['label' => 'Total Admin', 'value' => $totalAdmin, 'icon' => 'bi-person-badge', 'class' => 'metric-icon-slate'],
        ['label' => 'Total SPV', 'value' => $totalSpv, 'icon' => 'bi-person-check', 'class' => 'metric-icon-green'],
        ['label' => 'Total Teknisi', 'value' => $totalTeknisi, 'icon' => 'bi-tools', 'class' => 'metric-icon-amber'],
        ['label' => 'Total Ticket', 'value' => $totalTicket, 'icon' => 'bi-ticket-detailed', 'class' => 'metric-icon-blue'],
        ['label' => 'Ticket Aktif', 'value' => $ticketAktif, 'icon' => 'bi-lightning-charge', 'class' => 'metric-icon-green'],
        ['label' => 'Ticket Ditugaskan', 'value' => $ticketDitugaskan, 'icon' => 'bi-arrow-right-circle', 'class' => 'metric-icon-slate'],
        ['label' => 'Sedang Dikerjakan', 'value' => $ticketSedang, 'icon' => 'bi-kanban', 'class' => 'metric-icon-amber'],
        ['label' => 'Menunggu Alat', 'value' => $ticketMenungguAlat, 'icon' => 'bi-box-seam', 'class' => 'metric-icon-amber'],
        ['label' => 'Menunggu Verifikasi', 'value' => $ticketMenungguVerifikasi, 'icon' => 'bi-patch-check', 'class' => 'metric-icon-blue'],
        ['label' => 'Ticket Ditutup', 'value' => $ticketDitutup, 'icon' => 'bi-check2-circle', 'class' => 'metric-icon-green'],
        ['label' => 'Ticket Eskalasi', 'value' => $ticketEskalasi, 'icon' => 'bi-exclamation-triangle', 'class' => 'metric-icon-red'],
    ];
@endphp

<div class="superadmin-dashboard container-fluid">
    <div class="superadmin-hero mb-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3 position-relative">
            <div>
                <span class="hero-kicker"><i class="bi bi-speedometer2"></i> Dashboard Super Admin</span>
                <h1 class="hero-title">Ringkasan operasional ticketing yang lebih jelas, rapi, dan mudah dipantau.</h1>
                <p class="hero-subtitle">Halaman ini menampilkan statistik pengguna, distribusi ticket, status pekerjaan, dan monitoring ticket terbaru dalam satu tampilan yang konsisten.</p>
                <div class="hero-meta">
                    <span class="hero-meta-badge">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
                    <span>Selamat datang, <strong>{{ auth()->user()->name }}</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        @foreach ($metricCards as $card)
            <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                <div class="metric-card h-100">
                    <div class="metric-icon {{ $card['class'] }}">
                        <i class="bi {{ $card['icon'] }}"></i>
                    </div>
                    <div>
                        <div class="section-label">{{ $card['label'] }}</div>
                        <p class="metric-value">{{ $card['value'] }}</p>
                        <p class="metric-label">Statistik terbaru</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-8">
            <div class="dashboard-panel h-100">
                <div class="panel-head">
                    <div>
                        <div class="section-label mb-1">Monitoring</div>
                        <h5>Ticket Terbaru</h5>
                        <p>Daftar ticket terbaru dengan aksi yang seragam dan mudah dipindai.</p>
                    </div>
                    <span class="badge-soft badge-neutral"><i class="bi bi-record-fill"></i> Live data</span>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table dashboard-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Kode Ticket</th>
                                    <th>Pelapor</th>
                                    <th>Kategori</th>
                                    <th>Teknisi</th>
                                    <th>Prioritas</th>
                                    <th>Status</th>
                                    <th>Lokasi</th>
                                    <th>Tanggal Dibuat</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tickets as $ticket)
                                    @php
                                        $ticketPriority = $ticket->prioritas ? strtolower($ticket->prioritas) : null;
                                        $ticketStatus = $ticket->status_ticket ? strtolower($ticket->status_ticket) : null;
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold">{{ $ticket->kode_ticket }}</td>
                                        <td>{{ $ticket->keluhan?->nama_pelapor ?? $ticket->keluhan?->pembuat?->name ?? '-' }}</td>
                                        <td>{{ $ticket->kategori?->nama_kategori ?? '-' }}</td>
                                        <td>{{ $ticket->teknisi?->name ?? '-' }}</td>
                                        <td>
                                            <span class="badge-soft {{ $priorityClass[$ticketPriority] ?? 'badge-neutral' }}">
                                                <i class="bi bi-dot"></i>
                                                {{ $priorityLabels[$ticketPriority] ?? ($ticket->prioritas ? ucfirst($ticket->prioritas) : '-') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge-soft {{ $statusClass[$ticketStatus] ?? 'badge-neutral' }}">
                                                <i class="bi bi-dot"></i>
                                                {{ $statusLabels[$ticketStatus] ?? ($ticket->status_ticket ? str_replace('_', ' ', ucfirst($ticket->status_ticket)) : '-') }}
                                            </span>
                                        </td>
                                        <td>{{ $ticket->keluhan?->lokasi_keluhan ?? '-' }}</td>
                                        <td>{{ $ticket->created_at?->format('d M Y') ?? '-' }}</td>
                                        <td class="text-end">
                                            <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                                <a href="{{ url('/admin/tickets/'.$ticket->id) }}" class="btn-soft btn-soft-primary btn btn-sm">Detail</a>
                                                <a href="#" class="btn-soft btn-soft-warning btn btn-sm">Edit</a>
                                                <a href="#" class="btn-soft btn-soft-danger btn btn-sm">Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9">
                                            <div class="empty-state">
                                                <div class="fw-semibold mb-1">Belum ada ticket untuk ditampilkan.</div>
                                                <div>Data terbaru akan muncul di sini setelah ticket masuk ke sistem.</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="dashboard-panel mb-4">
                <div class="panel-head">
                    <div>
                        <div class="section-label mb-1">Visualisasi</div>
                        <h5>Ticket per Status</h5>
                        <p>Distribusi ticket aktif berdasarkan status saat ini.</p>
                    </div>
                </div>
                <div class="chart-card">
                    <div class="chart-wrap">
                        <canvas id="chartStatus"></canvas>
                    </div>
                </div>
            </div>

            <div class="dashboard-panel mb-4">
                <div class="panel-head">
                    <div>
                        <div class="section-label mb-1">Visualisasi</div>
                        <h5>Ticket per Kategori</h5>
                        <p>Melihat kategori dengan volume ticket terbanyak.</p>
                    </div>
                </div>
                <div class="chart-card">
                    <div class="chart-wrap">
                        <canvas id="chartCategory"></canvas>
                    </div>
                </div>
            </div>

            <div class="dashboard-panel">
                <div class="panel-head">
                    <div>
                        <div class="section-label mb-1">Visualisasi</div>
                        <h5>Ticket per Bulan</h5>
                        <p>Tren ticket masuk dari waktu ke waktu.</p>
                    </div>
                </div>
                <div class="chart-card">
                    <div class="chart-wrap">
                        <canvas id="chartMonthly"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const statusData = @json($ticketsPerStatus);
        const categoryData = @json($ticketsPerCategory);
        const monthlyData = @json($ticketsPerMonth);

        const chartPalette = ['#2563eb', '#16a34a', '#f59e0b', '#ef4444', '#64748b', '#0ea5e9', '#8b5cf6'];

        Chart.defaults.font.family = 'Inter, system-ui, -apple-system, BlinkMacSystemFont, Segoe UI, sans-serif';
        Chart.defaults.color = '#475569';

        new Chart(document.getElementById('chartStatus'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(statusData),
                datasets: [{ data: Object.values(statusData), backgroundColor: chartPalette, borderWidth: 0, cutout: '66%' }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 16,
                            boxWidth: 10,
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('chartCategory'), {
            type: 'bar',
            data: {
                labels: Object.keys(categoryData),
                datasets: [{
                    label: 'Tickets',
                    data: Object.values(categoryData),
                    backgroundColor: '#2563eb',
                    borderRadius: 10,
                    maxBarThickness: 32,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });

        new Chart(document.getElementById('chartMonthly'), {
            type: 'line',
            data: {
                labels: Object.keys(monthlyData),
                datasets: [{
                    label: 'Tickets',
                    data: Object.values(monthlyData),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.12)',
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    </script>
@endsection
