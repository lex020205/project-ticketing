<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Ticket</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #1e293b;
            line-height: 1.5;
        }

        .page-wrapper {
            padding: 20px 30px;
        }

        /* ===== HEADER ===== */
        .header {
            text-align: center;
            border-bottom: 3px solid #1e3a5f;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header-logo {
            margin-bottom: 8px;
        }

        .header-logo img {
            height: 60px;
        }

        .header h1 {
            font-size: 14px;
            font-weight: bold;
            color: #1e3a5f;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .header h2 {
            font-size: 12px;
            font-weight: bold;
            color: #1e3a5f;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .header h3 {
            font-size: 11px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 2px;
        }

        .header .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #2563eb;
            margin-top: 8px;
            letter-spacing: 1px;
        }

        .header-line {
            height: 1px;
            background: #94a3b8;
            margin-top: 4px;
        }

        /* ===== INFO BOX ===== */
        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 20px;
        }

        .info-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-box td {
            padding: 3px 8px;
            font-size: 10px;
            vertical-align: top;
        }

        .info-box .label {
            color: #64748b;
            font-weight: 600;
            width: 140px;
        }

        .info-box .value {
            color: #0f172a;
            font-weight: 700;
        }

        /* ===== STATISTIK ===== */
        .stats-section {
            margin-bottom: 20px;
        }

        .stats-title {
            font-size: 12px;
            font-weight: bold;
            color: #1e3a5f;
            margin-bottom: 10px;
            padding-bottom: 4px;
            border-bottom: 2px solid #2563eb;
            display: inline-block;
        }

        .stats-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .stats-grid td {
            width: 25%;
            padding: 4px;
            vertical-align: top;
        }

        .stat-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 12px;
            text-align: center;
        }

        .stat-card.highlight-blue {
            background: #eff6ff;
            border-color: #93c5fd;
        }

        .stat-card.highlight-red {
            background: #fef2f2;
            border-color: #fca5a5;
        }

        .stat-card.highlight-green {
            background: #f0fdf4;
            border-color: #86efac;
        }

        .stat-card.highlight-yellow {
            background: #fffbeb;
            border-color: #fcd34d;
        }

        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
        }

        .stat-label {
            font-size: 8px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        /* ===== TABEL DATA ===== */
        .data-section {
            margin-bottom: 20px;
        }

        .data-title {
            font-size: 12px;
            font-weight: bold;
            color: #1e3a5f;
            margin-bottom: 10px;
            padding-bottom: 4px;
            border-bottom: 2px solid #2563eb;
            display: inline-block;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }

        .data-table thead th {
            background: #1e3a5f;
            color: #ffffff;
            padding: 8px 6px;
            text-align: left;
            font-weight: 700;
            font-size: 7.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .data-table thead th:first-child {
            border-radius: 4px 0 0 0;
        }

        .data-table thead th:last-child {
            border-radius: 0 4px 0 0;
        }

        .data-table tbody td {
            padding: 6px 6px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
            font-size: 8px;
        }

        .data-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .data-table tbody tr:last-child td {
            border-bottom: 2px solid #1e3a5f;
        }

        /* Badge styles for PDF */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 7px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fca5a5; }
        .badge-warning { background: #fffbeb; color: #92400e; border: 1px solid #fcd34d; }
        .badge-primary { background: #eff6ff; color: #1e40af; border: 1px solid #93c5fd; }
        .badge-success { background: #f0fdf4; color: #166534; border: 1px solid #86efac; }
        .badge-secondary { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
        .badge-info { background: #ecfeff; color: #155e75; border: 1px solid #67e8f9; }

        /* ===== FOOTER ===== */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding: 8px 30px;
            background: #ffffff;
        }

        .footer .footer-text {
            margin-bottom: 2px;
        }

        .page-number:after {
            content: counter(page);
        }
    </style>
</head>
<body>
    <div class="footer">
        <div class="footer-text">Generated by <strong>Sistem Ticketing Laboran</strong></div>
        <div class="footer-text">Universitas Kristen Satya Wacana</div>
        <div class="footer-text">{{ $meta['tanggal_generate'] }} &mdash; Halaman <span class="page-number"></span></div>
    </div>

    <div class="page-wrapper">
        <!-- HEADER -->
        <div class="header">
            @if(file_exists(public_path('images/logo-uksw.png')))
                <div class="header-logo">
                    <img src="{{ public_path('images/logo-uksw.png') }}" alt="Logo UKSW">
                </div>
            @endif
            <h1>Universitas Kristen Satya Wacana</h1>
            <h2>Fakultas Teknologi Informasi</h2>
            <h3>Sistem Ticketing Laboran</h3>
            <div class="header-line"></div>
            <div class="report-title">LAPORAN DATA TICKET</div>
        </div>

        <!-- INFO BOX -->
        <div class="info-box">
            <table>
                <tr>
                    <td class="label">Nama User</td>
                    <td class="value">: {{ $meta['user_name'] }}</td>
                    <td class="label">Tanggal Generate</td>
                    <td class="value">: {{ $meta['tanggal_generate'] }}</td>
                </tr>
                <tr>
                    <td class="label">Role</td>
                    <td class="value">: {{ $meta['user_role'] }}</td>
                    <td class="label">Periode Laporan</td>
                    <td class="value">:
                        @if($meta['periode_awal'] && $meta['periode_akhir'])
                            {{ \Carbon\Carbon::parse($meta['periode_awal'])->format('d/m/Y') }}
                            s/d
                            {{ \Carbon\Carbon::parse($meta['periode_akhir'])->format('d/m/Y') }}
                        @else
                            Semua Periode
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Total Data</td>
                    <td class="value">: {{ $meta['total_data'] }} Ticket</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>

        <!-- RINGKASAN STATISTIK -->
        <div class="stats-section">
            <div class="stats-title">Ringkasan Statistik</div>

            <table class="stats-grid">
                <tr>
                    <td>
                        <div class="stat-card highlight-blue">
                            <div class="stat-value">{{ $summary['total'] }}</div>
                            <div class="stat-label">Total Ticket</div>
                        </div>
                    </td>
                    <td>
                        <div class="stat-card">
                            <div class="stat-value">{{ $summary['ditugaskan'] }}</div>
                            <div class="stat-label">Ditugaskan</div>
                        </div>
                    </td>
                    <td>
                        <div class="stat-card">
                            <div class="stat-value">{{ $summary['sedang_dikerjakan'] }}</div>
                            <div class="stat-label">Sedang Dikerjakan</div>
                        </div>
                    </td>
                    <td>
                        <div class="stat-card highlight-yellow">
                            <div class="stat-value">{{ $summary['menunggu_alat'] }}</div>
                            <div class="stat-label">Menunggu Alat</div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="stat-card highlight-yellow">
                            <div class="stat-value">{{ $summary['menunggu_verifikasi'] }}</div>
                            <div class="stat-label">Menunggu Verifikasi</div>
                        </div>
                    </td>
                    <td>
                        <div class="stat-card highlight-green">
                            <div class="stat-value">{{ $summary['ditutup'] }}</div>
                            <div class="stat-label">Ditutup</div>
                        </div>
                    </td>
                    <td>
                        <div class="stat-card highlight-red">
                            <div class="stat-value">{{ $summary['eskalasi'] }}</div>
                            <div class="stat-label">Eskalasi</div>
                        </div>
                    </td>
                    <td>
                        <div class="stat-card highlight-red">
                            <div class="stat-value">{{ $summary['darurat'] }}</div>
                            <div class="stat-label">Darurat</div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="stat-card">
                            <div class="stat-value">{{ $summary['prioritas_tinggi'] }}</div>
                            <div class="stat-label">Prioritas Tinggi</div>
                        </div>
                    </td>
                    <td>
                        <div class="stat-card">
                            <div class="stat-value">{{ $summary['prioritas_sedang'] }}</div>
                            <div class="stat-label">Prioritas Sedang</div>
                        </div>
                    </td>
                    <td>
                        <div class="stat-card">
                            <div class="stat-value">{{ $summary['prioritas_rendah'] }}</div>
                            <div class="stat-label">Prioritas Rendah</div>
                        </div>
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>

        <!-- TABEL DATA TICKET -->
        <div class="data-section">
            <div class="data-title">Detail Data Ticket</div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 25px;">No</th>
                        <th>Kode Ticket</th>
                        <th>Judul Keluhan</th>
                        <th>Pelapor</th>
                        <th>Kategori</th>
                        <th>Teknisi</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Lokasi</th>
                        <th>Tgl Dibuat</th>
                        <th>Tgl Ditugaskan</th>
                        <th>Tgl Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $index => $ticket)
                        @php
                            $prioritasBadge = match($ticket->prioritas) {
                                'darurat' => 'badge-danger',
                                'tinggi'  => 'badge-warning',
                                'sedang'  => 'badge-primary',
                                'rendah'  => 'badge-secondary',
                                default   => 'badge-secondary',
                            };

                            $statusBadge = match($ticket->status_ticket) {
                                'eskalasi'              => 'badge-danger',
                                'menunggu_alat', 'menunggu_verifikasi' => 'badge-warning',
                                'sedang_dikerjakan'     => 'badge-info',
                                'ditugaskan'            => 'badge-primary',
                                'ditutup'               => 'badge-success',
                                default                 => 'badge-secondary',
                            };

                            $statusLabel = match($ticket->status_ticket) {
                                'menunggu_penugasan'    => 'Menunggu Penugasan',
                                'ditugaskan'            => 'Ditugaskan',
                                'sedang_dikerjakan'     => 'Sedang Dikerjakan',
                                'menunggu_alat'         => 'Menunggu Alat',
                                'eskalasi'              => 'Eskalasi',
                                'menunggu_verifikasi'   => 'Menunggu Verifikasi',
                                'ditutup'               => 'Ditutup',
                                'dibatalkan'            => 'Dibatalkan',
                                default                 => str_replace('_', ' ', ucfirst($ticket->status_ticket)),
                            };
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td style="white-space: nowrap; font-weight: 700;">{{ $ticket->kode_ticket }}</td>
                            <td>{{ $ticket->keluhan?->deskripsi_keluhan ?? '-' }}</td>
                            <td>{{ $ticket->keluhan?->nama_pelapor ?? '-' }}</td>
                            <td>{{ $ticket->kategori?->nama_kategori ?? '-' }}</td>
                            <td>{{ $ticket->teknisi?->name ?? '-' }}</td>
                            <td><span class="badge {{ $prioritasBadge }}">{{ $ticket->prioritas ? ucfirst($ticket->prioritas) : '-' }}</span></td>
                            <td><span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span></td>
                            <td>{{ $ticket->keluhan?->lokasi_keluhan ?? '-' }}</td>
                            <td style="white-space: nowrap;">{{ $ticket->created_at?->format('d/m/Y') ?? '-' }}</td>
                            <td style="white-space: nowrap;">{{ $ticket->tanggal_ditugaskan ? \Carbon\Carbon::parse($ticket->tanggal_ditugaskan)->format('d/m/Y') : '-' }}</td>
                            <td style="white-space: nowrap;">{{ $ticket->tanggal_selesai ? \Carbon\Carbon::parse($ticket->tanggal_selesai)->format('d/m/Y') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
