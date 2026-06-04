{{-- Modul 4/5/6 - Teknisi Ticket, Lampiran, dan Eskalasi --}}
{{-- Ringkas: detail ticket teknisi, progress, upload lampiran, dan ajukan eskalasi. --}}
@extends('layouts.app')

@section('title', 'Detail Ticket - Teknisi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Detail Ticket</h1>
        <p class="text-muted mb-0">Informasi ticket yang Anda tangani.</p>
    </div>
    <a href="{{ route('teknisi.tickets.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 justify-content-end">
            @if ($ticket->status_ticket === 'ditugaskan')
                <form method="POST" action="{{ route('teknisi.tickets.mulai', $ticket) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-play-circle"></i> Mulai Kerjakan
                    </button>
                </form>
            @endif

            @if (in_array($ticket->status_ticket, ['sedang_dikerjakan', 'menunggu_alat'], true))
                <form method="POST" action="{{ route('teknisi.tickets.selesai', $ticket) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Tandai Selesai
                    </button>
                </form>
            @endif
        </div>
        <div class="row g-3 mt-1">
            <div class="col-md-4">
                <div class="text-muted small">Kode Ticket</div>
                <div class="fw-semibold">{{ $ticket->kode_ticket }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Status Ticket</div>
                <div class="fw-semibold">{{ str_replace('_', ' ', $ticket->status_ticket) }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Prioritas</div>
                <div class="fw-semibold">{{ ucfirst($ticket->prioritas) }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Kategori</div>
                <div class="fw-semibold">{{ $ticket->kategori?->nama_kategori ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Tanggal Ditugaskan</div>
                <div class="fw-semibold">
                    {{ $ticket->tanggal_ditugaskan ? \Illuminate\Support\Carbon::parse($ticket->tanggal_ditugaskan)->format('d-m-Y H:i') : '-' }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Tanggal Mulai</div>
                <div class="fw-semibold">
                    {{ $ticket->tanggal_mulai ? \Illuminate\Support\Carbon::parse($ticket->tanggal_mulai)->format('d-m-Y H:i') : '-' }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Tanggal Selesai</div>
                <div class="fw-semibold">
                    {{ $ticket->tanggal_selesai ? \Illuminate\Support\Carbon::parse($ticket->tanggal_selesai)->format('d-m-Y H:i') : '-' }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Data Pelapor</h5>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="text-muted small">Nama Pelapor</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->nama_pelapor ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Jenis Pelapor</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->jenis_pelapor ? ucfirst($ticket->keluhan->jenis_pelapor) : '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Kontak Pelapor</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->kontak_pelapor ?? '-' }}</div>
            </div>
        </div>
        <hr>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="text-muted small">Lokasi Keluhan</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->lokasi_keluhan ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Detail Lokasi</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->detail_lokasi ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Kategori</div>
                <div class="fw-semibold">{{ $ticket->kategori?->nama_kategori ?? '-' }}</div>
            </div>
            <div class="col-12">
                <div class="text-muted small">Deskripsi Keluhan</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->deskripsi_keluhan ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>

@if (in_array($ticket->status_ticket, ['sedang_dikerjakan', 'menunggu_alat'], true))
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Tambah Progress</h5>
            <form method="POST" action="{{ route('teknisi.tickets.progress', $ticket) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea name="catatan" id="catatan" class="form-control" rows="3" required>{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="status_baru" class="form-label">Status Baru</label>
                        <select name="status_baru" id="status_baru" class="form-select" required>
                            <option value="">Pilih status</option>
                            @foreach (['sedang_dikerjakan', 'menunggu_alat'] as $status)
                                <option value="{{ $status }}" @selected(old('status_baru', $ticket->status_ticket) === $status)>
                                    {{ str_replace('_', ' ', $status) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status_baru')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Simpan Progress</button>
                </div>
            </form>
        </div>
    </div>
@endif

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Lampiran Bukti Pengerjaan</h5>

        @if (in_array($ticket->status_ticket, ['sedang_dikerjakan', 'menunggu_alat', 'menunggu_verifikasi'], true))
            <form method="POST" action="{{ route('teknisi.tickets.lampiran', $ticket) }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="lampiran" class="form-label">File Lampiran</label>
                        <input type="file" name="lampiran" id="lampiran" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                        @error('lampiran')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Format: JPG, JPEG, PNG, PDF. Maks 2MB.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" id="keterangan" class="form-control" value="{{ old('keterangan') }}">
                        @error('keterangan')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Upload Lampiran</button>
                </div>
            </form>
            <hr>
        @endif

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Preview</th>
                        <th>Nama File</th>
                        <th>Keterangan</th>
                        <th>Uploader</th>
                        <th>Waktu Upload</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lampiranList as $lampiran)
                        @php
                            $extension = strtolower(pathinfo($lampiran->nama_file, PATHINFO_EXTENSION));
                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png'], true);
                            $fileUrl = \Illuminate\Support\Facades\Storage::url($lampiran->path_file);
                        @endphp
                        <tr>
                            <td>
                                @if ($isImage)
                                    <img src="{{ $fileUrl }}" alt="{{ $lampiran->nama_file }}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <span class="badge bg-secondary">PDF</span>
                                @endif
                            </td>
                            <td>{{ $lampiran->nama_file }}</td>
                            <td>{{ $lampiran->keterangan ?? '-' }}</td>
                            <td>{{ $lampiran->uploader?->name ?? '-' }}</td>
                            <td>{{ $lampiran->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ $fileUrl }}" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">Belum ada lampiran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Ajukan Eskalasi</h5>

        @if (in_array($ticket->status_ticket, ['sedang_dikerjakan', 'menunggu_alat'], true))
            <form method="POST" action="{{ route('teknisi.tickets.eskalasi', $ticket) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="jenis_eskalasi" class="form-label">Jenis Eskalasi</label>
                        <select name="jenis_eskalasi" id="jenis_eskalasi" class="form-select" required>
                            <option value="">Pilih jenis eskalasi</option>
                            @foreach (['butuh_bantuan' => 'Butuh Bantuan Teknisi Lain', 'butuh_alat' => 'Butuh Alat / Sparepart', 'dialihkan' => 'Minta Dialihkan ke Teknisi Lain', 'keputusan_spv' => 'Butuh Keputusan SPV'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('jenis_eskalasi') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('jenis_eskalasi')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-8">
                        <label for="alasan" class="form-label">Alasan</label>
                        <textarea name="alasan" id="alasan" class="form-control" rows="3" required>{{ old('alasan') }}</textarea>
                        @error('alasan')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-warning text-dark">Ajukan Eskalasi</button>
                </div>
            </form>
        @else
            <div class="text-muted small">Eskalasi hanya dapat diajukan saat ticket sedang dikerjakan atau menunggu alat.</div>
        @endif
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Riwayat Eskalasi</h5>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Jenis</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th>Diajukan Oleh</th>
                        <th>Diputuskan Oleh</th>
                        <th>Keputusan</th>
                        <th>Waktu Pengajuan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($eskalasiList as $eskalasi)
                        @php
                            $statusBadge = $eskalasi->status_eskalasi === 'menunggu' ? 'warning' : 'secondary';
                            $jenisLabel = [
                                'butuh_bantuan' => 'Butuh Bantuan Teknisi Lain',
                                'butuh_alat' => 'Butuh Alat / Sparepart',
                                'dialihkan' => 'Minta Dialihkan ke Teknisi Lain',
                                'keputusan_spv' => 'Butuh Keputusan SPV',
                            ];
                        @endphp
                        <tr>
                            <td>{{ $jenisLabel[$eskalasi->jenis_eskalasi] ?? $eskalasi->jenis_eskalasi }}</td>
                            <td>{{ $eskalasi->alasan }}</td>
                            <td>
                                <span class="badge bg-{{ $statusBadge }} text-dark">
                                    {{ str_replace('_', ' ', $eskalasi->status_eskalasi) }}
                                </span>
                            </td>
                            <td>{{ $eskalasi->pengaju?->name ?? '-' }}</td>
                            <td>{{ $eskalasi->pemutus?->name ?? '-' }}</td>
                            <td>{{ $eskalasi->keputusan ?? '-' }}</td>
                            <td>{{ $eskalasi->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">Belum ada eskalasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Riwayat Progress Pengerjaan</h5>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Teknisi</th>
                        <th>Status Sebelumnya</th>
                        <th>Status Baru</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($progressList as $progress)
                        <tr>
                            <td>{{ $progress->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                            <td>{{ $progress->user?->name ?? '-' }}</td>
                            <td>{{ str_replace('_', ' ', $progress->status_sebelumnya) }}</td>
                            <td>{{ str_replace('_', ' ', $progress->status_baru) }}</td>
                            <td>{{ $progress->catatan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Belum ada progress.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
