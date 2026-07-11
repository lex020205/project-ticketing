<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    /**
     * Generate laporan PDF dari data ticket.
     *
     * @return array{content: string, filename: string}
     */
    public function generateReport(Collection $tickets, array $summary, array $meta): array
    {
        $filename = 'Laporan_Ticket_' . date('Y-m-d_His') . '.pdf';

        $pdf = Pdf::loadView('reports.pdf-template', [
            'tickets'  => $tickets,
            'summary'  => $summary,
            'meta'     => $meta,
        ]);

        $pdf->setPaper('a4', 'landscape');

        // Opsi DomPDF untuk rendering yang lebih baik
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'     => true,
            'defaultFont'         => 'sans-serif',
        ]);

        return [
            'content'  => $pdf->output(),
            'filename' => $filename,
        ];
    }

    /**
     * Generate laporan PDF rekap teknisi.
     *
     * @param  \Illuminate\Support\Collection  $rekapData  Data rekap per teknisi
     * @param  array  $summary  Ringkasan statistik
     * @param  array  $meta     Metadata laporan
     * @return array{content: string, filename: string}
     */
    public function generateRekapTeknisi($rekapData, array $summary, array $meta): array
    {
        $filename = 'Laporan_Rekap_Teknisi_' . date('Y-m-d_His') . '.pdf';

        $pdf = Pdf::loadView('reports.rekap-teknisi-pdf', [
            'rekapData' => $rekapData,
            'summary'   => $summary,
            'meta'      => $meta,
        ]);

        $pdf->setPaper('a4', 'portrait');

        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'     => true,
            'defaultFont'         => 'sans-serif',
        ]);

        return [
            'content'  => $pdf->output(),
            'filename' => $filename,
        ];
    }

    /**
     * Simpan PDF yang sudah dibuat ke storage lokal agar tetap tersedia meski email gagal.
     */
    public function savePdfToDisk(string $content, string $filename): string
    {
        $relativePath = 'reports/' . date('Y-m-d') . '/' . $filename;

        Storage::disk('local')->put($relativePath, $content);

        return $relativePath;
    }
}
