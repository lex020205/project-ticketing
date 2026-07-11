<?php

namespace Tests\Feature;

use App\Http\Controllers\ReportExportController;
use App\Services\EmailService;
use App\Services\PdfService;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class ReportExportRouteTest extends TestCase
{
    public function test_spv_rekap_export_route_exists(): void
    {
        $response = $this->post(route('spv.laporan.export.rekap'));

        $response->assertRedirect('/login');
    }

    public function test_super_admin_rekap_export_route_exists(): void
    {
        $response = $this->post(route('super-admin.laporan.export.rekap'));

        $response->assertRedirect('/login');
    }

    public function test_export_rekap_returns_success_when_email_fails(): void
    {
        $reportService = Mockery::mock(ReportService::class);
        $pdfService = Mockery::mock(PdfService::class);
        $emailService = Mockery::mock(EmailService::class);

        $reportService->shouldReceive('getRekapTeknisiData')->once()->andReturn([
            'rekap' => collect(),
            'summary' => [
                'total_ticket_selesai' => 0,
                'jumlah_teknisi' => 0,
                'total_ticket_aktif' => 0,
                'total_ticket_eskalasi' => 0,
                'total_ticket_ditolak' => 0,
            ],
        ]);
        $pdfService->shouldReceive('generateRekapTeknisi')->once()->andReturn([
            'content' => '%PDF-1.4',
            'filename' => 'laporan.pdf',
        ]);
        $pdfService->shouldReceive('savePdfToDisk')->once()->andReturn('reports/laporan.pdf');
        $emailService->shouldReceive('sendRekapTeknisiEmail')->once()->andReturn([
            'success' => false,
            'message' => 'SMTP unavailable',
        ]);
        $reportService->shouldReceive('createLog')->once()->andReturnNull();

        Auth::shouldReceive('id')->andReturn(1);
        Auth::shouldReceive('user')->andReturn((object) [
            'name' => 'Test User',
            'role' => (object) ['nama_role' => 'SPV'],
        ]);

        $controller = new ReportExportController($reportService, $pdfService, $emailService);
        $response = $controller->exportRekapTeknisi(new Request());

        $this->assertSame(200, $response->getStatusCode());
        $payload = $response->getData(true);
        $this->assertTrue($payload['success']);
        $this->assertFalse($payload['email_sent']);
        $this->assertTrue($payload['saved_to_disk']);
    }
}
