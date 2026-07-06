@extends('layouts.app')

@section('extra_css')
    @include('partials.ui.shadcn-dashboard-styles')
@endsection

@section('content')
<div class="dashboard-shell container">
    <div class="page-hero">
        <span class="page-kicker"><i class="bi bi-ticket-detailed"></i> Monitoring Ticket</span>
        <h1 class="page-title">Monitoring Ticket (Super Admin)</h1>
        <p class="page-subtitle">Daftar ticket ditampilkan dengan gaya yang sama seperti dashboard agar navigasi visual tetap konsisten.</p>
    </div>

    <div class="card card-modern table-shell">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Pelapor</th>
                            <th>Kategori</th>
                            <th>Teknisi</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                        <tr>
                            <td class="fw-semibold">{{ $ticket->kode_ticket }}</td>
                            <td>{{ $ticket->keluhan?->nama_pelapor }}</td>
                            <td>{{ $ticket->kategori?->nama_kategori }}</td>
                            <td>{{ $ticket->teknisi?->name }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $ticket->status_ticket }}</span></td>
                            <td>{{ $ticket->created_at?->format('Y-m-d') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-3 py-3 border-top">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
