{{-- Modul 4/5/6 - Teknisi Ticket, Lampiran, dan Eskalasi --}}
{{-- Ringkas: detail ticket teknisi, progress, upload lampiran, dan ajukan eskalasi. --}}
@extends('layouts.app')

@section('title', 'Detail Ticket - Teknisi')

@section('content')
@php
    $prioritasBadge = [
        'rendah' => 'secondary',
        'sedang' => 'primary',
        'tinggi' => 'warning',
        'darurat' => 'danger',
    ];
    $statusTicketBadge = [
        'menunggu_penugasan' => 'secondary',
        'ditugaskan' => 'info',
        'sedang_dikerjakan' => 'primary',
        'menunggu_alat' => 'warning',
        'eskalasi' => 'danger',
        'menunggu_verifikasi' => 'warning',
        'ditutup' => 'success',
        'dibatalkan' => 'dark',
    ];
@endphp
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h1 class="fw-bold mb-1 fs-2">Detail Ticket</h1>
        <p class="text-muted mb-0" style="font-size:0.9rem;">Informasi ticket yang Anda tangani.</p>
    </div>
    <a href="{{ route('teknisi.tickets.index') }}" class="btn btn-outline-secondary align-self-stretch align-self-sm-auto text-center">
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

<<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 justify-content-end mb-3">
            @if ($ticket->status_ticket === 'ditugaskan')
                <form method="POST" action="{{ route('teknisi.tickets.mulai', $ticket) }}" class="flex-fill flex-sm-grow-0">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-play-circle me-1"></i> Mulai Kerjakan
                    </button>
                </form>
            @endif

            @if (in_array($ticket->status_ticket, ['sedang_dikerjakan', 'menunggu_alat'], true))
                <form method="POST" action="{{ route('teknisi.tickets.selesai', $ticket) }}" class="flex-fill flex-sm-grow-0">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-check-circle me-1"></i> Tandai Selesai
                    </button>
                </form>
            @endif
        </div>
        <div class="row g-3 mt-1">
            <div class="col-6 col-md-4">
                <div class="text-muted small">Kode Ticket</div>
                <div class="fw-bold text-primary" style="font-size:1.1rem;">{{ $ticket->kode_ticket }}</div>
            </div>
            <div class="col-6 col-md-4">
                <div class="text-muted small">Status Ticket</div>
                <div class="fw-semibold mt-1">
                    <span class="badge bg-{{ $statusTicketBadge[$ticket->status_ticket] ?? 'secondary' }} {{ in_array($ticket->status_ticket, ['menunggu_alat', 'menunggu_verifikasi'], true) ? 'text-dark' : '' }}" style="font-size:0.85rem;">
                        {{ str_replace('_', ' ', $ticket->status_ticket) }}
                    </span>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="text-muted small">Prioritas</div>
                <div class="fw-semibold mt-1">
                    <span class="badge bg-{{ $prioritasBadge[$ticket->prioritas] ?? 'secondary' }}" style="font-size:0.85rem;">
                        {{ ucfirst($ticket->prioritas) }}
                    </span>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="text-muted small">Kategori</div>
                <div class="fw-semibold">{{ $ticket->kategori?->nama_kategori ?? '-' }}</div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="text-muted small">Tanggal Ditugaskan</div>
                <div class="fw-semibold text-secondary" style="font-size:0.95rem;">
                    {{ $ticket->tanggal_ditugaskan ? \Illuminate\Support\Carbon::parse($ticket->tanggal_ditugaskan)->format('d-m-Y H:i') : '-' }}
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="text-muted small">Tanggal Mulai</div>
                <div class="fw-semibold text-secondary" style="font-size:0.95rem;">
                    {{ $ticket->tanggal_mulai ? \Illuminate\Support\Carbon::parse($ticket->tanggal_mulai)->format('d-m-Y H:i') : '-' }}
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="text-muted small">Tanggal Selesai</div>
                <div class="fw-semibold text-secondary" style="font-size:0.95rem;">
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
            <div class="col-6 col-md-4">
                <div class="text-muted small">Nama Pelapor</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->nama_pelapor ?? '-' }}</div>
            </div>
            <div class="col-6 col-md-4">
                <div class="text-muted small">Jenis Pelapor</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->jenis_pelapor ? ucfirst($ticket->keluhan->jenis_pelapor) : '-' }}</div>
            </div>
            <div class="col-12 col-md-4">
                <div class="text-muted small">Kontak Pelapor</div>
                <div class="fw-semibold text-secondary" style="font-size:0.95rem;">{{ $ticket->keluhan?->kontak_pelapor ?? '-' }}</div>
            </div>
        </div>
        <hr class="my-3">
        <div class="row g-3">
            <div class="col-6 col-md-4">
                <div class="text-muted small">Lokasi Keluhan</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->lokasi_keluhan ?? '-' }}</div>
            </div>
            <div class="col-6 col-md-4">
                <div class="text-muted small">Detail Lokasi</div>
                <div class="fw-semibold">{{ $ticket->keluhan?->detail_lokasi ?? '-' }}</div>
            </div>
            <div class="col-12 col-md-4">
                <div class="text-muted small">Kategori</div>
                <div class="fw-semibold">{{ $ticket->kategori?->nama_kategori ?? '-' }}</div>
            </div>
            <div class="col-12">
                <div class="text-muted small">Deskripsi Keluhan</div>
                <div class="fw-semibold p-2 bg-light rounded mt-1 text-secondary" style="font-size: 0.925rem; border-left: 3px solid #cbd5e1;">
                    {{ $ticket->keluhan?->deskripsi_keluhan ?? '-' }}
                </div>
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

<<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Lampiran Bukti Pengerjaan</h5>

        @if (in_array($ticket->status_ticket, ['sedang_dikerjakan', 'menunggu_alat', 'menunggu_verifikasi'], true))
            <form method="POST" action="{{ route('teknisi.tickets.lampiran', $ticket) }}" enctype="multipart/form-data">
                @csrf
                <div class="upload-container mb-3">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-medium">Pilih / Ambil Foto Bukti</label>
                            
                            <!-- Dashed Dropzone -->
                            <div class="upload-zone" id="uploadZone" onclick="document.getElementById('lampiran').click()">
                                <div class="upload-zone-content">
                                    <i class="bi bi-cloud-arrow-up fs-2 mb-1 d-block"></i>
                                    <span class="fw-semibold text-primary d-block" style="font-size:0.9rem;">Pilih foto dari galeri / seret file</span>
                                    <span class="text-muted small" style="font-size:0.75rem;">Format: JPG, JPEG, PNG, PDF (Maks 2MB)</span>
                                </div>
                                <input type="file" name="lampiran" id="lampiran" class="d-none" accept=".jpg,.jpeg,.png,.pdf" required>
                            </div>
                            
                            @error('lampiran')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            
                            <!-- Shutter activation button -->
                            <div class="mt-2">
                                <button type="button" class="btn btn-primary w-100" id="btnStartCamera">
                                    <i class="bi bi-camera me-1"></i> Ambil Foto Kamera Langsung
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <label for="keterangan" class="form-label fw-medium">Keterangan Bukti</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" rows="4" placeholder="Tulis deskripsi atau keterangan bukti pekerjaan..." style="height: calc(100% - 32px); min-height: 100px;">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- WebRTC Camera Widget -->
                    <div class="camera-panel mt-3 p-3 border rounded bg-light d-none" id="cameraPanel">
                        <h6 class="fw-bold mb-3 d-flex justify-content-between align-items-center" style="font-size:0.9rem;">
                            <span><i class="bi bi-camera me-1 text-primary"></i> Kamera Langsung</span>
                            <span class="badge bg-warning text-dark" id="cameraStatus">Inisialisasi...</span>
                        </h6>
                        
                        <div class="camera-viewport-wrapper position-relative mx-auto bg-dark rounded overflow-hidden" style="max-width: 400px; aspect-ratio: 4/3;">
                            <video id="cameraVideo" autoplay playsinline class="w-100 h-100" style="object-fit: cover;"></video>
                            <canvas id="cameraCanvas" class="d-none"></canvas>
                            
                            <div id="capturedImagePreview" class="position-absolute top-0 start-0 w-100 h-100 bg-dark d-none">
                                <img id="previewImg" src="" class="w-100 h-100" style="object-fit: contain;">
                            </div>
                        </div>
                        
                        <!-- Shutter and controls -->
                        <div class="d-flex justify-content-center align-items-center gap-3 mt-3">
                            <button type="button" class="btn btn-sm btn-secondary px-3" id="btnCancelCamera">Batal</button>
                            
                            <button type="button" class="btn btn-danger rounded-circle d-flex align-items-center justify-content-center" id="btnCapturePhoto" style="width: 54px; height: 54px; padding: 0;">
                                <span class="bg-white rounded-circle" style="width: 36px; height: 36px; display: inline-block;"></span>
                            </button>
                            
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" id="btnSwitchCamera" style="width: 40px; height: 40px; padding: 0;">
                                <i class="bi bi-arrow-repeat fs-5"></i>
                            </button>
                        </div>
                        
                        <!-- Post capture actions -->
                        <div class="d-flex justify-content-center gap-3 mt-3 d-none" id="postCaptureControls">
                            <button type="button" class="btn btn-sm btn-warning text-dark px-3" id="btnRetakePhoto">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Foto Ulang
                            </button>
                            <button type="button" class="btn btn-sm btn-success px-4" id="btnUsePhoto">
                                <i class="bi bi-check-lg me-1"></i>Gunakan Foto
                            </button>
                        </div>
                    </div>

                    <!-- Selected/Captured File Preview Card -->
                    <div class="file-preview-card mt-3 p-3 border rounded bg-white d-none" id="filePreviewCard">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3 min-width-0">
                                <div class="preview-thumbnail-wrapper border rounded overflow-hidden bg-light" style="width: 50px; height: 50px; flex-shrink: 0;">
                                    <img id="filePreviewThumbnail" src="" class="w-100 h-100 d-none" style="object-fit: cover;">
                                    <div id="filePreviewPdfIcon" class="w-100 h-100 d-flex align-items-center justify-content-center bg-danger text-white fw-bold d-none" style="font-size: 0.7rem;">PDF</div>
                                </div>
                                <div class="min-width-0">
                                    <div class="fw-semibold text-truncate" id="previewFileName" style="max-width: 180px; font-size:0.9rem;">filename.jpg</div>
                                    <div class="text-muted small" id="previewFileSize" style="font-size:0.75rem;">0 KB</div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm rounded-circle d-flex align-items-center justify-content-center" id="btnRemovePreview" style="width: 32px; height: 32px; padding: 0;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary w-100 w-sm-auto px-4">Upload Lampiran</button>
                </div>
            </form>
            <hr>
        @endif

        <!-- Desktop View Table -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Preview</th>
                        <th>Nama File</th>
                        <th>Keterangan</th>
                        <th>Uploader</th>
                        <th>Waktu Upload</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lampiranList as $lampiran)
                        @php
                            $extension = strtolower(pathinfo($lampiran->nama_file, PATHINFO_EXTENSION));
                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png'], true);
                            $fileUrl = route('ticket-lampiran.show', $lampiran);
                        @endphp
                        <tr>
                            <td class="ps-4">
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
                            <td class="text-end pe-4">
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

        <!-- Mobile View Card List (iPhone 13 friendly) -->
        <div class="d-block d-md-none">
            <div class="list-group list-group-flush">
                @forelse ($lampiranList as $lampiran)
                    @php
                        $extension = strtolower(pathinfo($lampiran->nama_file, PATHINFO_EXTENSION));
                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png'], true);
                        $fileUrl = route('ticket-lampiran.show', $lampiran);
                    @endphp
                    <div class="list-group-item px-0 py-3 border-bottom">
                        <div class="d-flex gap-3 align-items-center">
                            <div class="border rounded overflow-hidden bg-light" style="width: 64px; height: 64px; flex-shrink: 0;">
                                @if ($isImage)
                                    <img src="{{ $fileUrl }}" alt="{{ $lampiran->nama_file }}" class="w-100 h-100" style="object-fit: cover;">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-secondary text-white fw-bold" style="font-size:0.75rem;">PDF</div>
                                @endif
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="fw-semibold text-truncate text-dark" style="font-size:0.9rem;">{{ $lampiran->nama_file }}</div>
                                <div class="text-secondary text-truncate small">{{ $lampiran->keterangan ?? 'Tanpa keterangan' }}</div>
                                <div class="text-muted small mt-1" style="font-size:0.7rem;">
                                    Oleh: {{ $lampiran->uploader?->name ?? '-' }} | {{ $lampiran->created_at?->format('d-m H:i') ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <a href="{{ $fileUrl }}" class="btn btn-sm btn-outline-primary px-3 rounded-pill" style="font-size:0.75rem;" target="_blank" rel="noopener">Lihat</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">Belum ada lampiran bukti.</div>
                @endforelse
            </div>
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

<<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Riwayat Eskalasi</h5>
        
        <!-- Desktop view table -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Jenis</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th>Diajukan Oleh</th>
                        <th>Diputuskan Oleh</th>
                        <th>Keputusan</th>
                        <th class="pe-4">Waktu Pengajuan</th>
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
                            <td class="ps-4 fw-medium">{{ $jenisLabel[$eskalasi->jenis_eskalasi] ?? $eskalasi->jenis_eskalasi }}</td>
                            <td>{{ $eskalasi->alasan }}</td>
                            <td>
                                <span class="badge bg-{{ $statusBadge }} text-dark">
                                    {{ str_replace('_', ' ', $eskalasi->status_eskalasi) }}
                                </span>
                            </td>
                            <td>{{ $eskalasi->pengaju?->name ?? '-' }}</td>
                            <td>{{ $eskalasi->pemutus?->name ?? '-' }}</td>
                            <td>{{ $eskalasi->keputusan ?? '-' }}</td>
                            <td class="pe-4">{{ $eskalasi->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">Belum ada eskalasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile view card list -->
        <div class="d-block d-md-none">
            <div class="list-group list-group-flush">
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
                    <div class="list-group-item px-0 py-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-dark" style="font-size:0.9rem;">{{ $jenisLabel[$eskalasi->jenis_eskalasi] ?? $eskalasi->jenis_eskalasi }}</span>
                            <span class="badge bg-{{ $statusBadge }} text-dark" style="font-size:0.75rem;">
                                {{ str_replace('_', ' ', $eskalasi->status_eskalasi) }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <div class="text-muted small">Alasan</div>
                            <div class="text-secondary" style="font-size:0.9rem;">{{ $eskalasi->alasan }}</div>
                        </div>
                        @if ($eskalasi->keputusan)
                            <div class="mb-2 p-2 bg-light rounded border-start border-3 border-primary">
                                <div class="text-muted small" style="font-size:0.75rem;">Keputusan SPV:</div>
                                <div class="fw-medium text-dark" style="font-size:0.85rem;">{{ $eskalasi->keputusan }}</div>
                                <div class="text-muted small" style="font-size:0.7rem;">Oleh: {{ $eskalasi->pemutus?->name ?? '-' }}</div>
                            </div>
                        @endif
                        <div class="text-muted small" style="font-size:0.7rem; margin-top:0.5rem;">
                            Diajukan oleh: {{ $eskalasi->pengaju?->name ?? '-' }} | {{ $eskalasi->created_at?->format('d-m-Y H:i') ?? '-' }}
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">Belum ada data eskalasi.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Riwayat Progress Pengerjaan</h5>
        
        <!-- Desktop view table -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Tanggal</th>
                        <th>Teknisi</th>
                        <th>Status Sebelumnya</th>
                        <th>Status Baru</th>
                        <th class="pe-4">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($progressList as $progress)
                        <tr>
                            <td class="ps-4">{{ $progress->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                            <td>{{ $progress->user?->name ?? '-' }}</td>
                            <td>{{ str_replace('_', ' ', $progress->status_sebelumnya) }}</td>
                            <td>{{ str_replace('_', ' ', $progress->status_baru) }}</td>
                            <td class="pe-4">{{ $progress->catatan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Belum ada progress.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile view card list -->
        <div class="d-block d-md-none">
            <div class="list-group list-group-flush">
                @forelse ($progressList as $progress)
                    <div class="list-group-item px-0 py-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-dark" style="font-size:0.9rem;">{{ $progress->user?->name ?? '-' }}</span>
                            <span class="text-muted small" style="font-size:0.75rem;">
                                <i class="bi bi-clock me-1"></i>{{ $progress->created_at?->format('d-m-Y H:i') ?? '-' }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <div class="text-muted small">Catatan Progress</div>
                            <div class="text-secondary" style="font-size:0.9rem;">{{ $progress->catatan }}</div>
                        </div>
                        <div class="d-flex align-items-center gap-2" style="font-size:0.8rem;">
                            <span class="badge bg-secondary opacity-75">{{ str_replace('_', ' ', $progress->status_sebelumnya) }}</span>
                            <i class="bi bi-arrow-right text-muted"></i>
                            <span class="badge bg-primary">{{ str_replace('_', ' ', $progress->status_baru) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">Belum ada data progress.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@section('extra_css')
<style>
    /* Custom Upload Zone */
    .upload-zone {
        border: 2px dashed #cbd5e1;
        background-color: #f8fafc;
        border-radius: 0.75rem;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 140px;
    }
    .upload-zone:hover {
        border-color: #2563eb;
        background-color: #eff6ff;
    }
    .upload-zone-content i {
        color: #2563eb;
        transition: transform 0.2s ease;
    }
    .upload-zone:hover .upload-zone-content i {
        transform: translateY(-4px);
    }
    
    /* Camera Viewport */
    .camera-viewport-wrapper {
        box-shadow: inset 0 0 20px rgba(0,0,0,0.6);
        border: 3px solid #cbd5e1;
    }
    
    /* Responsive adjustment for table-to-cards */
    @media (max-width: 576px) {
        .card-body {
            padding: 1rem;
        }
        .btn-lg {
            padding: 0.5rem 1rem;
            font-size: 1rem;
        }
    }
</style>
@endsection

@section('extra_js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('lampiran');
        const uploadZone = document.getElementById('uploadZone');
        const filePreviewCard = document.getElementById('filePreviewCard');
        const filePreviewThumbnail = document.getElementById('filePreviewThumbnail');
        const filePreviewPdfIcon = document.getElementById('filePreviewPdfIcon');
        const previewFileName = document.getElementById('previewFileName');
        const previewFileSize = document.getElementById('previewFileSize');
        const btnRemovePreview = document.getElementById('btnRemovePreview');

        const btnStartCamera = document.getElementById('btnStartCamera');
        const cameraPanel = document.getElementById('cameraPanel');
        const cameraVideo = document.getElementById('cameraVideo');
        const cameraCanvas = document.getElementById('cameraCanvas');
        const cameraStatus = document.getElementById('cameraStatus');
        const btnCancelCamera = document.getElementById('btnCancelCamera');
        const btnCapturePhoto = document.getElementById('btnCapturePhoto');
        const btnSwitchCamera = document.getElementById('btnSwitchCamera');
        const capturedImagePreview = document.getElementById('capturedImagePreview');
        const previewImg = document.getElementById('previewImg');
        const postCaptureControls = document.getElementById('postCaptureControls');
        const btnRetakePhoto = document.getElementById('btnRetakePhoto');
        const btnUsePhoto = document.getElementById('btnUsePhoto');

        let cameraStream = null;
        let currentFacingMode = 'environment'; // Rear camera default for taking work pictures
        let capturedDataUrl = null;

        // Function to convert Base64 Data URL to File object
        function dataURLtoFile(dataurl, filename) {
            const arr = dataurl.split(',');
            const mime = arr[0].match(/:(.*?);/)[1];
            const bstr = atob(arr[1]);
            let n = bstr.length;
            const u8arr = new Uint8Array(n);
            while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new File([u8arr], filename, { type: mime });
        }

        // Format File Size
        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        // Display selected file preview
        function handleFileSelection(file) {
            if (!file) return;

            previewFileName.textContent = file.name;
            previewFileSize.textContent = formatBytes(file.size);
            
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    filePreviewThumbnail.src = e.target.result;
                    filePreviewThumbnail.classList.remove('d-none');
                    filePreviewPdfIcon.classList.add('d-none');
                }
                reader.readAsDataURL(file);
            } else if (file.type === 'application/pdf') {
                filePreviewThumbnail.classList.add('d-none');
                filePreviewPdfIcon.classList.remove('d-none');
            } else {
                filePreviewThumbnail.classList.add('d-none');
                filePreviewPdfIcon.classList.remove('d-none');
                filePreviewPdfIcon.textContent = 'FILE';
            }

            filePreviewCard.classList.remove('d-none');
        }

        // Standard File Input Change Listener
        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                handleFileSelection(e.target.files[0]);
            }
        });

        // Remove Preview Button Clicked
        btnRemovePreview.addEventListener('click', function() {
            fileInput.value = '';
            filePreviewCard.classList.add('d-none');
            filePreviewThumbnail.src = '';
        });

        // Drag and Drop Zone styling
        uploadZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadZone.classList.add('border-primary', 'bg-primary-subtle');
        });

        uploadZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadZone.classList.remove('border-primary', 'bg-primary-subtle');
        });

        uploadZone.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadZone.classList.remove('border-primary', 'bg-primary-subtle');
            
            if (e.dataTransfer.files.length > 0) {
                const files = e.dataTransfer.files;
                fileInput.files = files; // Assign files to input
                handleFileSelection(files[0]);
            }
        });

        // Start WebRTC Camera
        async function startCamera() {
            cameraPanel.classList.remove('d-none');
            capturedImagePreview.classList.add('d-none');
            postCaptureControls.classList.add('d-none');
            btnCapturePhoto.classList.remove('d-none');
            btnSwitchCamera.classList.remove('d-none');
            cameraStatus.textContent = 'Menghubungkan...';
            cameraStatus.className = 'badge bg-warning text-dark';

            if (cameraStream) {
                stopCameraStream();
            }

            const constraints = {
                video: {
                    facingMode: currentFacingMode,
                    width: { ideal: 1280 },
                    height: { ideal: 960 }
                },
                audio: false
            };

            try {
                cameraStream = await navigator.mediaDevices.getUserMedia(constraints);
                cameraVideo.srcObject = cameraStream;
                cameraStatus.textContent = 'Kamera Aktif';
                cameraStatus.className = 'badge bg-success';
                
                // Wait for video metadata to play
                cameraVideo.onloadedmetadata = () => {
                    cameraVideo.play();
                };
            } catch (err) {
                console.error("Error accessing camera: ", err);
                cameraStatus.textContent = 'Gagal Akses';
                cameraStatus.className = 'badge bg-danger';
                alert("Tidak dapat mengakses kamera secara langsung. Izin kamera mungkin diblokir, atau koneksi tidak aman (non-HTTPS). Gunakan file uploader biasa untuk memfoto lewat kamera sistem.");
                stopCameraStream();
                cameraPanel.classList.add('d-none');
            }
        }

        // Stop Camera Stream
        function stopCameraStream() {
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
            }
            cameraVideo.srcObject = null;
        }

        // Start Camera Button Clicked
        btnStartCamera.addEventListener('click', function() {
            startCamera();
            setTimeout(() => {
                cameraPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 100);
        });

        // Cancel Camera Button
        btnCancelCamera.addEventListener('click', function() {
            stopCameraStream();
            cameraPanel.classList.add('d-none');
        });

        // Capture Snapshot
        btnCapturePhoto.addEventListener('click', function() {
            if (!cameraStream) return;

            const width = cameraVideo.videoWidth || cameraVideo.clientWidth;
            const height = cameraVideo.videoHeight || cameraVideo.clientHeight;
            
            cameraCanvas.width = width;
            cameraCanvas.height = height;
            
            const context = cameraCanvas.getContext('2d');
            context.drawImage(cameraVideo, 0, 0, width, height);
            
            capturedDataUrl = cameraCanvas.toDataURL('image/jpeg', 0.85);
            previewImg.src = capturedDataUrl;
            
            capturedImagePreview.classList.remove('d-none');
            postCaptureControls.classList.remove('d-none');
            btnCapturePhoto.classList.add('d-none');
            btnSwitchCamera.classList.add('d-none');
            
            cameraStatus.textContent = 'Foto Terambil';
            cameraStatus.className = 'badge bg-info';
        });

        // Retake Photo Button
        btnRetakePhoto.addEventListener('click', function() {
            capturedImagePreview.classList.add('d-none');
            postCaptureControls.classList.add('d-none');
            btnCapturePhoto.classList.remove('d-none');
            btnSwitchCamera.classList.remove('d-none');
            cameraStatus.textContent = 'Kamera Aktif';
            cameraStatus.className = 'badge bg-success';
            capturedDataUrl = null;
        });

        // Use Photo Button
        btnUsePhoto.addEventListener('click', function() {
            if (!capturedDataUrl) return;

            // Convert Base64 dataUrl to File object
            const fileName = `bukti_pengerjaan_${Date.now()}.jpg`;
            const file = dataURLtoFile(capturedDataUrl, fileName);

            // Dynamically assign file to the hidden file input
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;

            // Trigger file preview rendering
            handleFileSelection(file);

            // Clean up and hide camera panel
            stopCameraStream();
            cameraPanel.classList.add('d-none');
            capturedDataUrl = null;
        });

        // Switch Camera (Front/Back)
        btnSwitchCamera.addEventListener('click', function() {
            currentFacingMode = (currentFacingMode === 'environment') ? 'user' : 'environment';
            startCamera();
        });
    });
</script>
@endsection
@endsection
