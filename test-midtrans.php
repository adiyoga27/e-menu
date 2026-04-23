<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
    \Midtrans\Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
    \Midtrans\Config::$is3ds = env('MIDTRANS_IS_3DS', true);

    $params = [
        'transaction_details' => [
            'order_id' => 'TEST-' . time(),
            'gross_amount' => 10000
        ],
        'customer_details' => [
            'first_name' => 'Budi',
            'phone' => '08123456789'
        ]
    ];
    $url = \Midtrans\Snap::createTransaction($params)->redirect_url;
    echo "URL: " . $url . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
