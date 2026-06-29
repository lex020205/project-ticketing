@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Role Management</h3>
    <ul>
        @foreach($roles as $role)
            <li>{{ $role->nama_role }} - {{ $role->deskripsi }}</li>
        @endforeach
    </ul>
</div>
@endsection
