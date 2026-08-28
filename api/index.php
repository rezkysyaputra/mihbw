<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Pastikan direktori sementara /tmp siap sebelum Laravel dijalankan
$storageDirs = [
    '/tmp/views',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($storageDirs as $dir) {
    if (! is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Muat autoloader Composer
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel Application
/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->handleRequest(Request::capture());
