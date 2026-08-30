<?php

header('Content-Type: text/html; charset=utf-8');

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

define('LARAVEL_START', microtime(true));

// 1. Setup direktori writable di /tmp
$tmpStorage = '/tmp/storage';
foreach ([
    $tmpStorage . '/framework/views',
    $tmpStorage . '/framework/cache/data',
    $tmpStorage . '/framework/sessions',
    $tmpStorage . '/logs',
    '/tmp/bootstrap/cache',
] as $dir) {
    if (! is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

putenv('LARAVEL_STORAGE_PATH=' . $tmpStorage);
$_ENV['LARAVEL_STORAGE_PATH'] = $tmpStorage;
$_SERVER['LARAVEL_STORAGE_PATH'] = $tmpStorage;

// 2. Load composer autoloader
require __DIR__ . '/../vendor/autoload.php';

try {
    // 3. Load Bootstrap Application
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // 4. Pastikan ViewServiceProvider terdaftar di container jika belum
    if (! $app->bound('view')) {
        $app->register(\Illuminate\View\ViewServiceProvider::class);
    }

    // 5. Eksekusi request melalui HTTP Kernel
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

    $request = \Illuminate\Http\Request::capture();
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);

} catch (\Throwable $e) {
    http_response_code(500);
    echo '<div style="font-family: sans-serif; padding: 30px; background: #fff; color: #111; max-width: 900px; margin: 20px auto; border: 1px solid #e2e8f0; border-radius: 8px;">';
    echo '<h2 style="color: #dc2626; margin-top: 0;">Error Terdeteksi di Vercel:</h2>';
    echo '<p style="font-size: 16px; font-weight: bold; background: #fef2f2; color: #991b1b; padding: 12px; border-radius: 6px; border: 1px solid #fecaca;">' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile()) . ' (' . $e->getLine() . ')</p>';
    echo '<h3 style="margin-top: 20px;">Stack Trace:</h3>';
    echo '<pre style="background: #f8fafc; padding: 15px; overflow-x: auto; font-size: 12px; border: 1px solid #cbd5e1; border-radius: 6px;">' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    echo '</div>';
}
