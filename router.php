<?php
/**
 * Routing script for PHP built-in web server
 */
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $uri;

if (is_file($file)) {
    return false; // serve the requested file as-is
}

$_SERVER['SCRIPT_NAME'] = '/index.php';
http_response_code(200);
include_once __DIR__ . '/index.php';
