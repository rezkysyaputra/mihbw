<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Setup folder temporary /tmp untuk serverless
$tmpStorage = '/tmp/storage';
foreach ([
    $tmpStorage . '/framework/views',
    $tmpStorage . '/framework/cache/data',
    $tmpStorage . '/framework/sessions',
    $tmpStorage . '/logs',
] as $dir) {
    if (! is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 1. Muat composer autoload
require __DIR__ . '/../vendor/autoload.php';

// 2. Buat instance application Laravel
/** @var \Illuminate\Foundation\Application $app */
$app = require __DIR__ . '/../bootstrap/app.php';

// 3. Arahkan storage path ke /tmp
$app->useStoragePath($tmpStorage);

// 4. Jalankan request melalui HTTP Kernel
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
