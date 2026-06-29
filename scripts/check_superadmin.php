<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'superadmin@laboran.test')->first();
if (!$user) {
    echo "NO_USER\n";
    exit;
}
$role = $user->role ? $user->role->nama_role : null;
if (!$role) {
    echo "NO_ROLE\n";
    exit;
}
echo $role . "\n";
