<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Setup folder temporary /tmp untuk serverless
$tmpStorage = '/tmp/storage';
$tmpBootstrapCache = '/tmp/bootstrap/cache';

foreach ([
    $tmpStorage . '/framework/views',
    $tmpStorage . '/framework/cache/data',
    $tmpStorage . '/framework/sessions',
    $tmpStorage . '/logs',
    $tmpBootstrapCache,
] as $dir) {
    if (! is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Salin cache bootstrap ke /tmp agar dapat dibaca dan ditulis ulang tanpa error read-only
if (is_dir(__DIR__ . '/../bootstrap/cache')) {
    foreach (scandir(__DIR__ . '/../bootstrap/cache') as $file) {
        if (str_ends_with($file, '.php')) {
            @copy(__DIR__ . '/../bootstrap/cache/' . $file, $tmpBootstrapCache . '/' . $file);
        }
    }
}

putenv('LARAVEL_STORAGE_PATH=' . $tmpStorage);
putenv('APP_SERVICES_CACHE=' . $tmpBootstrapCache . '/services.php');
putenv('APP_PACKAGES_CACHE=' . $tmpBootstrapCache . '/packages.php');
putenv('APP_CONFIG_CACHE=' . $tmpBootstrapCache . '/config.php');
putenv('APP_ROUTES_CACHE=' . $tmpBootstrapCache . '/routes.php');
putenv('APP_EVENTS_CACHE=' . $tmpBootstrapCache . '/events.php');

$_ENV['LARAVEL_STORAGE_PATH'] = $tmpStorage;
$_ENV['APP_SERVICES_CACHE'] = $tmpBootstrapCache . '/services.php';
$_ENV['APP_PACKAGES_CACHE'] = $tmpBootstrapCache . '/packages.php';
$_ENV['APP_CONFIG_CACHE'] = $tmpBootstrapCache . '/config.php';
$_ENV['APP_ROUTES_CACHE'] = $tmpBootstrapCache . '/routes.php';
$_ENV['APP_EVENTS_CACHE'] = $tmpBootstrapCache . '/events.php';

$_SERVER['LARAVEL_STORAGE_PATH'] = $tmpStorage;
$_SERVER['APP_SERVICES_CACHE'] = $tmpBootstrapCache . '/services.php';
$_SERVER['APP_PACKAGES_CACHE'] = $tmpBootstrapCache . '/packages.php';
$_SERVER['APP_CONFIG_CACHE'] = $tmpBootstrapCache . '/config.php';
$_SERVER['APP_ROUTES_CACHE'] = $tmpBootstrapCache . '/routes.php';
$_SERVER['APP_EVENTS_CACHE'] = $tmpBootstrapCache . '/events.php';

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
