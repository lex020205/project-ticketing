{{-- Modul 12 - SPV User Management --}}
{{-- Ringkas: daftar user dan filter role/status. --}}
@extends('layouts.app')

@section('title', 'User Management - SPV')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">User Management</h1>
        <p class="text-muted mb-0">Kelola user Admin, SPV, dan Teknisi.</p>
    </div>
    <a href="{{ route('spv.users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah User
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('spv.users.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="role_id" class="form-label">Role</label>
                    <select name="role_id" id="role_id" class="form-select">
                        <option value="">Semua role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" @selected((string) request('role_id') === (string) $role->id)>{{ $role->nama_role }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="status_user" class="form-label">Status</label>
                    <select name="status_user" id="status_user" class="form-select">
                        <option value="">Semua status</option>
                        @foreach (['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif'] as $value => $label)
                            <option value="{{ $value }}" @selected(request('status_user') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="keyword" class="form-label">Pencarian</label>
                    <input type="text" name="keyword" id="keyword" class="form-control" value="{{ request('keyword') }}" placeholder="Nama, email, nomor telepon">
                </div>
                <div class="col-md-12 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    <a href="{{ route('spv.users.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

@php
    $roleBadge = [
        'Admin' => 'primary',
        'SPV' => 'warning',
        'Teknisi' => 'info',
    ];
    $statusBadge = [
        'aktif' => 'success',
        'nonaktif' => 'secondary',
    ];
@endphp

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Nomor Telepon</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        @php
                            $roleName = $user->role?->nama_role ?? '-';
                            $roleClass = $roleBadge[$roleName] ?? 'secondary';
                            $statusClass = $statusBadge[$user->status_user] ?? 'secondary';
                            $isSelf = (int) auth()->id() === (int) $user->id;
                        @endphp
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge bg-{{ $roleClass }}">{{ $roleName }}</span></td>
                            <td>{{ $user->nomor_telepon ?? '-' }}</td>
                            <td><span class="badge bg-{{ $statusClass }}">{{ ucfirst($user->status_user) }}</span></td>
                            <td>{{ $user->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('spv.users.show', $user) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                    <a href="{{ route('spv.users.edit', $user) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#toggleStatus{{ $user->id }}" @disabled($isSelf)>
                                        {{ $user->status_user === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#resetPassword{{ $user->id }}" @disabled($isSelf)>
                                        Reset Password
                                    </button>
                                </div>
                                @if ($isSelf)
                                    <div class="small text-muted mt-1">Aksi status dan reset dinonaktifkan untuk akun Anda.</div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@foreach ($users as $user)
    <div class="modal fade" id="toggleStatus{{ $user->id }}" tabindex="-1" aria-labelledby="toggleStatusLabel{{ $user->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="toggleStatusLabel{{ $user->id }}">Konfirmasi Ubah Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin {{ $user->status_user === 'aktif' ? 'menonaktifkan' : 'mengaktifkan' }} user <strong>{{ $user->name }}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <form method="POST" action="{{ route('spv.users.toggle_status', $user) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-warning">Ya, Ubah Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="resetPassword{{ $user->id }}" tabindex="-1" aria-labelledby="resetPasswordLabel{{ $user->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetPasswordLabel{{ $user->id }}">Konfirmasi Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Password user <strong>{{ $user->name }}</strong> akan direset menjadi <strong>password</strong>. Lanjutkan?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <form method="POST" action="{{ route('spv.users.reset_password', $user) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger">Ya, Reset Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
