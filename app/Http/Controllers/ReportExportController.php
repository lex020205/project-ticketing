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

        // Simpan log
        $this->reportService->createLog([
            'user_id'       => Auth::id(),
            'periode_awal'  => $filters['tanggal_awal'] ?? null,
            'periode_akhir' => $filters['tanggal_akhir'] ?? null,
            'nama_file'     => $pdf['filename'],
            'email_tujuan'  => null,
            'status'        => 'berhasil',
        ]);

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

        // Kirim email via Resend API
        $emailResult = $this->emailService->sendReportEmail(
            $pdf['content'],
            $pdf['filename'],
            $meta
        );

        $toEmail = config('services.resend.receiver_email');

        // Simpan log
        $this->reportService->createLog([
            'user_id'       => Auth::id(),
            'periode_awal'  => $filters['tanggal_awal'] ?? null,
            'periode_akhir' => $filters['tanggal_akhir'] ?? null,
            'nama_file'     => $pdf['filename'],
            'email_tujuan'  => $toEmail,
            'status'        => $emailResult['success'] ? 'berhasil' : 'gagal',
            'error_message' => $emailResult['success'] ? null : $emailResult['message'],
        ]);

        return response()->json([
            'success' => $emailResult['success'],
            'message' => $emailResult['message'],
        ], $emailResult['success'] ? 200 : 500);
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
