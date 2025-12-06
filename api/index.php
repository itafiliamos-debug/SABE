<?php

header('Content-Type: text/html; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

require_once __DIR__ . '/../session_handler.php';

chdir(__DIR__ . '/..'); 

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file    = ltrim($request, '/');
if ($file === '' || $file === 'index.php') $file = 'index.php';

$path = __DIR__ . '/../' . $file;

if (file_exists($path) && pathinfo($path, PATHINFO_EXTENSION) === 'php') {
    require $path;
    exit;
}

http_response_code(404);
echo '<h1>404 Not Found</h1>';