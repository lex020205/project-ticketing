<?php

namespace App\Http\Controllers;

use App\Services\EmailService;
use App\Services\PdfService;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportExportController extends Controller
{
    public function __construct(
        private ReportService $reportService,
        private PdfService $pdfService,
        private EmailService $emailService,
    ) {}

    /**
     * Generate PDF dan download.
     */
    public function generatePdf(Request $request)
    {
        $filters = $this->extractFilters($request);
        $tickets = $this->reportService->getFilteredTickets($filters);

        if ($tickets->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data laporan pada periode tersebut.',
            ], 422);
        }

        $summary = $this->reportService->getReportSummary($tickets);
        $meta    = $this->buildMeta($filters, $tickets->count());

        $pdf = $this->pdfService->generateReport($tickets, $summary, $meta);

        // Simpan log bila tabel tersedia; jangan memecah alur bila belum migrasi.
        try {
            $this->reportService->createLog([
                'user_id'       => Auth::id(),
                'periode_awal'  => $filters['tanggal_awal'] ?? null,
                'periode_akhir' => $filters['tanggal_akhir'] ?? null,
                'nama_file'     => $pdf['filename'],
                'email_tujuan'  => null,
                'status'        => 'berhasil',
                'error_message' => null,
            ]);
        } catch (\Throwable $e) {
            \Log::warning('Gagal menulis log laporan PDF', ['message' => $e->getMessage()]);
        }

        return response($pdf['content'], 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $pdf['filename'] . '"',
        ]);
    }

    /**
     * Generate PDF dan kirim ke email via Resend API.
     */
    public function sendEmail(Request $request)
    {
        $filters = $this->extractFilters($request);
        $tickets = $this->reportService->getFilteredTickets($filters);

        if ($tickets->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data laporan pada periode tersebut.',
            ], 422);
        }

        $summary = $this->reportService->getReportSummary($tickets);
        $meta    = $this->buildMeta($filters, $tickets->count());

        // Generate PDF
        $pdf = $this->pdfService->generateReport($tickets, $summary, $meta);

        // Kirim email via Resend API atau fallback Laravel mailer
        $emailResult = $this->emailService->sendReportEmail(
            $pdf['content'],
            $pdf['filename'],
            $meta
        );

        $toEmail = config('services.resend.receiver_email') ?: config('mail.from.address');

        // Simpan log bila tabel tersedia; jangan memecah alur bila belum migrasi.
        try {
            $this->reportService->createLog([
                'user_id'       => Auth::id(),
                'periode_awal'  => $filters['tanggal_awal'] ?? null,
                'periode_akhir' => $filters['tanggal_akhir'] ?? null,
                'nama_file'     => $pdf['filename'],
                'email_tujuan'  => $toEmail,
                'status'        => $emailResult['success'] ? 'berhasil' : 'gagal',
                'error_message' => $emailResult['success'] ? null : $emailResult['message'],
            ]);
        } catch (\Throwable $e) {
            \Log::warning('Gagal menulis log pengiriman laporan email', ['message' => $e->getMessage()]);
        }

        return response()->json([
            'success' => $emailResult['success'],
            'message' => $emailResult['success']
                ? "Laporan berhasil dikirim ke email {$toEmail}."
                : ($emailResult['message'] ?? 'Gagal mengirim laporan.'),
            'recipient_email' => $toEmail,
            'email_sent' => $emailResult['success'],
        ], $emailResult['success'] ? 200 : 500);
    }

    /**
     * Generate PDF rekap teknisi dan kirim lewat email. */
    public function exportRekapTeknisi(Request $request)
    {
        try {
            $filters = $this->extractFilters($request);
            $rekapData = $this->reportService->getRekapTeknisiData();
            $summary = $rekapData['summary'];
            $meta = $this->buildMeta($filters, $summary['total_ticket_selesai'] ?? 0);

            $pdf = $this->pdfService->generateRekapTeknisi($rekapData['rekap'], $summary, $meta);
            $savedPath = $this->pdfService->savePdfToDisk($pdf['content'], $pdf['filename']);

            $emailResult = $this->emailService->sendRekapTeknisiEmail(
                $pdf['content'],
                $pdf['filename'],
                $meta + $summary
            );

            try {
                $this->reportService->createLog([
                    'user_id'       => Auth::id(),
                    'periode_awal'  => $filters['tanggal_awal'] ?? null,
                    'periode_akhir' => $filters['tanggal_akhir'] ?? null,
                    'nama_file'     => $pdf['filename'],
                    'email_tujuan'  => config('services.resend.receiver_email'),
                    'status'        => $emailResult['success'] ? 'berhasil' : 'gagal',
                    'error_message' => $emailResult['success'] ? null : $emailResult['message'],
                ]);
            } catch (\Throwable $e) {
                \Log::warning('Gagal menulis log rekap teknisi', ['message' => $e->getMessage()]);
            }

            $toEmail = config('services.resend.receiver_email') ?: config('mail.from.address');
            $savedToDisk = !empty($savedPath);
            $emailSent = (bool) ($emailResult['success'] ?? false);

            $message = $emailSent
                ? "Laporan rekap teknisi berhasil dibuat dan dikirim ke email {$toEmail}."
                : (
                    $savedToDisk
                        ? "Laporan berhasil dibuat dan disimpan di server. Pengiriman email gagal: " . ($emailResult['message'] ?? 'cek konfigurasi Resend/SMTP.')
                        : ($emailResult['message'] ?? 'Gagal membuat laporan.')
                );

            return response()->json([
                'success' => true,
                'message' => $message,
                'recipient_email' => $toEmail,
                'email_sent' => $emailSent,
                'saved_to_disk' => $savedToDisk,
                'storage_path' => $savedPath,
            ], 200);
        } catch (\Throwable $e) {
            \Log::error('Gagal membuat laporan rekap teknisi', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat laporan karena error server: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ekstrak filter dari request.
     */
    private function extractFilters(Request $request): array
    {
        return [
            'tanggal_awal'  => $request->input('tanggal_awal'),
            'tanggal_akhir' => $request->input('tanggal_akhir'),
            'status_ticket' => $request->input('status_ticket'),
            'prioritas'     => $request->input('prioritas'),
            'kategori_id'   => $request->input('kategori_id'),
            'teknisi_id'    => $request->input('teknisi_id'),
        ];
    }

    /**
     * Bangun metadata laporan.
     */
    private function buildMeta(array $filters, int $totalData): array
    {
        $user = Auth::user();

        return [
            'user_name'     => $user->name,
            'user_role'     => $user->role?->nama_role ?? 'Tanpa Role',
            'tanggal_generate' => now()->format('d F Y H:i'),
            'periode_awal'  => $filters['tanggal_awal'] ?? null,
            'periode_akhir' => $filters['tanggal_akhir'] ?? null,
            'total_data'    => $totalData,
        ];
    }
}
