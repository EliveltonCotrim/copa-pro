<?php
// Corrige o esquema HTTPS quando passa por Cloudflare Tunnel / Traefik
if (isset($_SERVER['HTTP_CF_VISITOR'])) {
    $cfVisitor = json_decode($_SERVER['HTTP_CF_VISITOR'], true);
    if (isset($cfVisitor['scheme']) && $cfVisitor['scheme'] === 'https') {
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_PORT'] = 443;
        $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
        $_SERVER['HTTP_X_FORWARDED_PORT'] = '443';
    }
}
// end Corrige o esquema HTTPS quando passa por Cloudflare Tunnel / Traefik

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__ . '/../bootstrap/app.php')
    ->handleRequest(Request::capture());
