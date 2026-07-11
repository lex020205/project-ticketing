{{-- Modul 11 - Laporan Admin dan SPV --}}
{{-- Ringkas: laporan dan rekap ticket (SPV). --}}
@extends('layouts.app')

@section('title', 'Laporan SPV - Sistem Ticketing Laboran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Laporan SPV</h1>
        <p class="text-muted mb-0">Rekap detail ticket dan performa teknisi berdasarkan filter.</p>
    </div>
<<<<<<< HEAD
=======
    <button type="button" class="btn btn-success" id="generateRekapBtn">
        <i class="bi bi-file-earmark-pdf me-2"></i>Generate Laporan
    </button>
>>>>>>> 5d8238d (Initial commit)
</div>

@php
    $prioritasBadge = [
        'rendah' => 'secondary',
        'sedang' => 'primary',
        'tinggi' => 'warning',
        'darurat' => 'danger',
    ];

    $statusBadge = [
        'menunggu_penugasan' => 'secondary',
        'ditugaskan' => 'primary',
        'sedang_dikerjakan' => 'info',
        'menunggu_alat' => 'warning',
        'eskalasi' => 'danger',
        'menunggu_verifikasi' => 'warning',
        'ditutup' => 'success',
        'dibatalkan' => 'secondary',
    ];
@endphp

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('spv.laporan.index') }}">
            <div class="row g-3">
                <div class="col-12 col-md-3">
                    <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                    <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
                </div>
                <div class="col-12 col-md-3">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-12 col-md-3">
                    <label for="status_ticket" class="form-label">Status Ticket</label>
                    <select name="status_ticket" id="status_ticket" class="form-select">
                        <option value="">Semua status</option>
                        @foreach ($statusOptions as $value => $label)
                            <option value="{{ $value }}" @selected(request('status_ticket') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label for="prioritas" class="form-label">Prioritas</label>
                    <select name="prioritas" id="prioritas" class="form-select">
                        <option value="">Semua prioritas</option>
                        @foreach (['rendah', 'sedang', 'tinggi', 'darurat'] as $prioritas)
                            <option value="{{ $prioritas }}" @selected(request('prioritas') === $prioritas)>{{ ucfirst($prioritas) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label for="kategori_id" class="form-label">Kategori</label>
                    <select name="kategori_id" id="kategori_id" class="form-select">
                        <option value="">Semua kategori</option>
                        @foreach ($kategoriList as $kategori)
                            <option value="{{ $kategori->id }}" @selected((string) request('kategori_id') === (string) $kategori->id)>{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label for="teknisi_id" class="form-label">Teknisi</label>
                    <select name="teknisi_id" id="teknisi_id" class="form-select">
                        <option value="">Semua teknisi</option>
                        @foreach ($teknisiList as $teknisi)
                            <option value="{{ $teknisi->id }}" @selected((string) request('teknisi_id') === (string) $teknisi->id)>{{ $teknisi->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1 flex-md-grow-0">Terapkan Filter</button>
                    <a href="{{ route('spv.laporan.index') }}" class="btn btn-outline-secondary flex-grow-1 flex-md-grow-0">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-md-6 col-xl-3">
        <div class="card shadow-sm report-card">
            <div class="card-body">
                <div class="text-muted small">Total Ticket</div>
                <div class="fs-4 fw-bold">{{ $summary['total'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm report-card">
            <div class="card-body">
                <div class="text-muted small">Ticket Aktif</div>
                <div class="fs-4 fw-bold">{{ $summary['aktif'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm report-card">
            <div class="card-body">
                <div class="text-muted small">Ticket Darurat</div>
                <div class="fs-4 fw-bold">{{ $summary['darurat'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm report-card">
            <div class="card-body">
                <div class="text-muted small">Ticket Eskalasi</div>
                <div class="fs-4 fw-bold">{{ $summary['eskalasi'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm report-card">
            <div class="card-body">
                <div class="text-muted small">Menunggu Verifikasi</div>
                <div class="fs-4 fw-bold">{{ $summary['menunggu_verifikasi'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm report-card">
            <div class="card-body">
                <div class="text-muted small">Ticket Ditutup</div>
                <div class="fs-4 fw-bold">{{ $summary['ditutup'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm report-card">
            <div class="card-body">
                <div class="text-muted small">Total Teknisi Aktif</div>
                <div class="fs-4 fw-bold">{{ $teknisiAktif }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm report-card">
            <div class="card-body">
                <div class="text-muted small">Total Kategori Aktif</div>
                <div class="fs-4 fw-bold">{{ $kategoriAktif }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-light">
                <h6 class="mb-0 fw-bold">Rekap Ticket Berdasarkan Status</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Status Ticket</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rekapStatus as $row)
                                @php
                                    $statusLabel = $statusOptions[$row->status_ticket] ?? str_replace('_', ' ', $row->status_ticket);
                                    $statusClass = $statusBadge[$row->status_ticket] ?? 'secondary';
                                @endphp
                                <tr>
                                    <td><span class="badge bg-{{ $statusClass }}">{{ $statusLabel }}</span></td>
                                    <td>{{ $row->jumlah }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">Belum ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-light">
                <h6 class="mb-0 fw-bold">Rekap Ticket Berdasarkan Prioritas</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Prioritas</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rekapPrioritas as $row)
                                @php
                                    $prioritasLabel = $row->prioritas ? ucfirst($row->prioritas) : '-';
                                    $prioritasClass = $row->prioritas ? ($prioritasBadge[$row->prioritas] ?? 'secondary') : 'secondary';
                                @endphp
                                <tr>
                                    <td><span class="badge bg-{{ $prioritasClass }}">{{ $prioritasLabel }}</span></td>
                                    <td>{{ $row->jumlah }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">Belum ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-light">
                <h6 class="mb-0 fw-bold">Rekap Ticket Berdasarkan Kategori</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kategori</th>
                                <th>Jumlah Ticket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rekapKategori as $row)
                                <tr>
                                    <td>{{ $row->nama_kategori }}</td>
                                    <td>{{ $row->jumlah_ticket }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">Belum ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-light">
                <h6 class="mb-0 fw-bold">Rekap Ticket Berdasarkan Teknisi</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Teknisi</th>
                                <th>Total Ticket</th>
                                <th>Ticket Selesai</th>
                                <th>Ticket Aktif</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rekapTeknisi as $row)
                                <tr>
                                    <td>{{ $row->nama_teknisi }}</td>
                                    <td>{{ $row->total_ticket }}</td>
                                    <td>{{ $row->ticket_selesai }}</td>
                                    <td>{{ $row->ticket_aktif }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Belum ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h6 class="mb-0 fw-bold">Daftar Ticket Terbaru</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kode Ticket</th>
                        <th>Nama Pelapor</th>
                        <th>Kategori</th>
                        <th>Teknisi</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestTickets as $ticket)
                        @php
                            $prioritas = $ticket->prioritas;
                            $prioritasClass = $prioritas ? ($prioritasBadge[$prioritas] ?? 'secondary') : 'secondary';
                            $statusClass = $statusBadge[$ticket->status_ticket] ?? 'secondary';
                            $statusTextClass = in_array($ticket->status_ticket, ['menunggu_alat', 'menunggu_verifikasi'], true) ? 'text-dark' : '';
                            $statusLabel = $statusOptions[$ticket->status_ticket] ?? str_replace('_', ' ', $ticket->status_ticket);
                        @endphp
                        <tr>
                            <td>{{ $ticket->kode_ticket }}</td>
                            <td>{{ $ticket->keluhan?->nama_pelapor ?? '-' }}</td>
                            <td>{{ $ticket->kategori?->nama_kategori ?? '-' }}</td>
                            <td>{{ $ticket->teknisi?->name ?? '-' }}</td>
                            <td><span class="badge bg-{{ $prioritasClass }}">{{ $prioritas ? ucfirst($prioritas) : '-' }}</span></td>
                            <td><span class="badge bg-{{ $statusClass }} {{ $statusTextClass }}">{{ $statusLabel }}</span></td>
                            <td>{{ $ticket->created_at ? $ticket->created_at->format('d-m-Y H:i') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada ticket.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('extra_css')
<style>
    .report-card {
        border-left: 4px solid #2563eb;
    }
</style>
@endsection
<<<<<<< HEAD
=======

@section('extra_js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('generateRekapBtn');
        if (!button) return;

        button.addEventListener('click', function () {
            const form = document.querySelector('form[action="{{ route('spv.laporan.index') }}"]');
            const formData = form ? new FormData(form) : new FormData();
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';

            fetch('{{ route('spv.laporan.export.rekap') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async response => {
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(data.message || 'Gagal membuat laporan');
                }
                return data;
            })
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'Laporan berhasil dikirim',
                    text: data.message || 'Laporan rekap teknisi telah dikirim ke email yang dituju.',
                });
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal membuat laporan',
                    text: error.message,
                });
            })
            .finally(() => {
                button.disabled = false;
                button.innerHTML = '<i class="bi bi-file-earmark-pdf me-2"></i>Generate Laporan';
            });
        });
    });
</script>
@endsection

@section('extra_js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('generateRekapBtn');
        if (!button) return;

        button.addEventListener('click', function () {
            const form = document.querySelector('form[action="{{ route('spv.laporan.index') }}"]');
            const formData = new FormData(form);
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';

            fetch('{{ route('spv.laporan.export.rekap') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async response => {
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(data.message || 'Gagal membuat laporan');
                }
                return data;
            })
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'Laporan berhasil dikirim',
                    text: data.message || 'Laporan rekap teknisi telah dikirim ke email yang dituju.',
                });
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal membuat laporan',
                    text: error.message,
                });
            })
            .finally(() => {
                button.disabled = false;
                button.innerHTML = '<i class="bi bi-file-earmark-pdf me-2"></i>Generate Laporan';
            });
        });
    });
</script>
@endsection
>>>>>>> 5d8238d (Initial commit)
