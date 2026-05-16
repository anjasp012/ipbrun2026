<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Imported Orders: " . \App\Models\Order::where('payment_method', 'import')->count() . "\n";
echo "Total Participants: " . \App\Models\Participant::count() . "\n";
