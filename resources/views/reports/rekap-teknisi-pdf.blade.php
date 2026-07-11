<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekap Teknisi</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #0f172a;
            line-height: 1.45;
        }
        .page-wrapper { padding: 24px 28px; }
        .header { text-align: center; border-bottom: 2px solid #2563eb; padding-bottom: 10px; margin-bottom: 14px; }
        .header h1 { font-size: 15px; color: #1e3a5f; text-transform: uppercase; margin-bottom: 2px; }
        .header h2 { font-size: 12px; color: #334155; margin-bottom: 2px; }
        .meta-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; margin-bottom: 12px; }
        .meta-box table { width: 100%; border-collapse: collapse; }
        .meta-box td { padding: 3px 6px; font-size: 9px; }
        .label { color: #64748b; font-weight: 700; width: 120px; }
        .value { color: #0f172a; font-weight: 600; }
        .stats-grid { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .stats-grid td { width: 25%; padding: 4px; }
        .stat-card { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; padding: 8px 10px; text-align: center; }
        .stat-value { font-size: 16px; font-weight: bold; color: #1d4ed8; }
        .stat-label { font-size: 8px; text-transform: uppercase; color: #64748b; margin-top: 2px; }
        table.data-table { width: 100%; border-collapse: collapse; font-size: 8px; }
        .data-table th { background: #1e3a5f; color: white; padding: 6px; text-align: left; }
        .data-table td { padding: 6px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        .data-table tr:nth-child(even) { background: #f8fafc; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 10px; font-weight: 700; }
        .badge-success { background: #f0fdf4; color: #166534; border: 1px solid #86efac; }
        .badge-secondary { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
        .footer { margin-top: 16px; font-size: 8px; color: #64748b; text-align: center; }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="header">
            <h1>Rekap Pekerjaan Teknisi</h1>
            <h2>Sistem Ticketing Laboran - Universitas Kristen Satya Wacana</h2>
        </div>

        <div class="meta-box">
            <table>
                <tr>
                    <td class="label">Di-generate oleh</td>
                    <td class="value">{{ $meta['user_name'] ?? '-' }}</td>
                    <td class="label">Tanggal</td>
                    <td class="value">{{ $meta['tanggal_generate'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Periode</td>
                    <td class="value">
                        @if(!empty($meta['periode_awal']) && !empty($meta['periode_akhir']))
                            {{ \Carbon\Carbon::parse($meta['periode_awal'])->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($meta['periode_akhir'])->format('d/m/Y') }}
                        @else
                            Semua Periode
                        @endif
                    </td>
                    <td class="label">Total Selesai</td>
                    <td class="value">{{ $summary['total_ticket_selesai'] ?? 0 }}</td>
                </tr>
            </table>
        </div>

        <table class="stats-grid">
            <tr>
                <td>
                    <div class="stat-card">
                        <div class="stat-value">{{ $summary['jumlah_teknisi'] ?? 0 }}</div>
                        <div class="stat-label">Jumlah Teknisi</div>
                    </div>
                </td>
                <td>
                    <div class="stat-card">
                        <div class="stat-value">{{ $summary['total_ticket_selesai'] ?? 0 }}</div>
                        <div class="stat-label">Ticket Selesai</div>
                    </div>
                </td>
                <td>
                    <div class="stat-card">
                        <div class="stat-value">{{ $summary['total_ticket_aktif'] ?? 0 }}</div>
                        <div class="stat-label">Ticket Aktif</div>
                    </div>
                </td>
                <td>
                    <div class="stat-card">
                        <div class="stat-value">{{ $summary['total_ticket_eskalasi'] ?? 0 }}</div>
                        <div class="stat-label">Eskalasi</div>
                    </div>
                </td>
            </tr>
        </table>

        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Teknisi</th>
                    <th>Jumlah Selesai</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rekapData as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row['nama_teknisi'] ?? '-' }}</td>
                        <td>{{ $row['jumlah_selesai'] ?? 0 }}</td>
                        <td><span class="badge badge-success">Aktif</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Belum ada data rekap teknisi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            Dibuat secara otomatis oleh Sistem Ticketing Laboran.
        </div>
    </div>
</body>
</html>
