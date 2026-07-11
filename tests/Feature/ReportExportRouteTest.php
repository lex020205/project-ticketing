<?php

namespace Tests\Feature;

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
}
