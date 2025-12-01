<?php

require_once __DIR__.'/../vendor/autoload.php';

use App\Services\SelcomService;

// Load Laravel environment
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $selcomService = app(SelcomService::class);

    // Test payment initiation
    $response = $selcomService->initiatePayment(
        'test_order_' . time(),
        1000, // amount in TZS
        '+255712345678', // test phone
        'Test Payment',
        null, // callback
        'test@example.com',
        'Test User'
    );

    echo "Payment initiation successful!\n";
    echo "Response: " . json_encode($response, JSON_PRETTY_PRINT) . "\n";

} catch (\Exception $e) {
    echo "Payment initiation failed: " . $e->getMessage() . "\n";
}
