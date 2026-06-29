@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Audit Log</h3>
    <table class="table table-sm">
        <thead><tr><th>User</th><th>Action</th><th>Module</th><th>IP</th><th>Waktu</th></tr></thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td>{{ $log->user?->name }}</td>
                <td>{{ $log->action }}</td>
                <td>{{ $log->module }}</td>
                <td>{{ $log->ip_address }}</td>
                <td>{{ $log->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $logs->links() }}
</div>
@endsection
