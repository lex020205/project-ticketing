<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = $app->make(App\Services\EmailService::class);
$result = $service->sendReportEmail('PDF-CONTENT', 'test.pdf', [
    'total_data' => 1,
    'periode_awal' => null,
    'periode_akhir' => null,
]);

var_export($result);
echo PHP_EOL;
