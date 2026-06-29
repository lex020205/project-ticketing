@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Pengaturan Sistem</h3>
    <ul>
        @foreach($settings as $s)
            <li>{{ $s->key }}: {{ $s->value }}</li>
        @endforeach
    </ul>
</div>
@endsection
