@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Monitoring Ticket (Super Admin)</h3>

    <div class="table-responsive mt-3">
        <table class="table table-striped">
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
                    <td>{{ $ticket->kode_ticket }}</td>
                    <td>{{ $ticket->keluhan?->nama_pelapor }}</td>
                    <td>{{ $ticket->kategori?->nama_kategori }}</td>
                    <td>{{ $ticket->teknisi?->name }}</td>
                    <td>{{ $ticket->status_ticket }}</td>
                    <td>{{ $ticket->created_at?->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $tickets->links() }}
    </div>
</div>
@endsection
