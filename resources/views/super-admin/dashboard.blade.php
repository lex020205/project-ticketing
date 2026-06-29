@extends('layouts.app')

@section('extra_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.3.0/chart.min.css">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3>Dashboard Super Admin</h3>
            <small class="text-muted">Selamat datang, {{ auth()->user()->name }}</small>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card p-3">
                <h6>Total User</h6>
                <h3>{{ $totalUser }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <h6>Total Admin</h6>
                <h3>{{ $totalAdmin }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <h6>Total SPV</h6>
                <h3>{{ $totalSpv }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <h6>Total Teknisi</h6>
                <h3>{{ $totalTeknisi }}</h3>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3"><div class="card p-3"><h6>Total Ticket</h6><h3>{{ $totalTicket }}</h3></div></div>
        <div class="col-md-3"><div class="card p-3"><h6>Ticket Aktif</h6><h3>{{ $ticketAktif }}</h3></div></div>
        <div class="col-md-3"><div class="card p-3"><h6>Ticket Ditugaskan</h6><h3>{{ $ticketDitugaskan }}</h3></div></div>
        <div class="col-md-3"><div class="card p-3"><h6>Sedang Dikerjakan</h6><h3>{{ $ticketSedang }}</h3></div></div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3"><div class="card p-3"><h6>Menunggu Alat</h6><h3>{{ $ticketMenungguAlat }}</h3></div></div>
        <div class="col-md-3"><div class="card p-3"><h6>Menunggu Verifikasi</h6><h3>{{ $ticketMenungguVerifikasi }}</h3></div></div>
        <div class="col-md-3"><div class="card p-3"><h6>Ticket Ditutup</h6><h3>{{ $ticketDitutup }}</h3></div></div>
        <div class="col-md-3"><div class="card p-3"><h6>Ticket Eskalasi</h6><h3>{{ $ticketEskalasi }}</h3></div></div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card p-3">
                <h6>Monitoring Terbaru</h6>
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-sm">
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
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->kode_ticket }}</td>
                                    <td>{{ $ticket->keluhan?->nama_pelapor ?? $ticket->keluhan?->pembuat?->name ?? '-' }}</td>
                                    <td>{{ $ticket->kategori?->nama_kategori ?? '-' }}</td>
                                    <td>{{ $ticket->teknisi?->name ?? '-' }}</td>
                                    <td>{{ $ticket->prioritas }}</td>
                                    <td>{{ $ticket->status_ticket }}</td>
                                    <td>{{ $ticket->keluhan?->lokasi_keluhan ?? '-' }}</td>
                                    <td>{{ $ticket->created_at?->format('Y-m-d') ?? '-' }}</td>
                                    <td>
                                        <a href="{{ url('/admin/tickets/'.$ticket->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                        <a href="#" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="#" class="btn btn-sm btn-danger">Hapus</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 mb-3">
                <h6>Ticket per Status</h6>
                <canvas id="chartStatus" height="200"></canvas>
            </div>
            <div class="card p-3 mb-3">
                <h6>Ticket per Kategori</h6>
                <canvas id="chartCategory" height="200"></canvas>
            </div>
            <div class="card p-3">
                <h6>Ticket per Bulan</h6>
                <canvas id="chartMonthly" height="200"></canvas>
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

        // Status Chart
        new Chart(document.getElementById('chartStatus'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(statusData),
                datasets: [{ data: Object.values(statusData), backgroundColor: ['#2563eb','#16a34a','#f59e0b','#ef4444','#64748b'] }]
            }
        });

        // Category Chart
        new Chart(document.getElementById('chartCategory'), {
            type: 'bar',
            data: {
                labels: Object.keys(categoryData),
                datasets: [{ label: 'Tickets', data: Object.values(categoryData), backgroundColor: '#2563eb' }]
            },
            options: { responsive: true }
        });

        // Monthly Chart
        new Chart(document.getElementById('chartMonthly'), {
            type: 'line',
            data: {
                labels: Object.keys(monthlyData),
                datasets: [{ label: 'Tickets', data: Object.values(monthlyData), borderColor: '#2563eb', fill:false }]
            },
            options: { responsive: true }
        });
    </script>
@endsection
