<?php

header('Content-Type: text/html; charset=utf-8');


chdir(__DIR__ . '/..');

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptName = ltrim($requestUri, '/');


if ($scriptName === '' || $scriptName === 'index.php') {
    $scriptName = 'index.php';
}


if (strpos($scriptName, '..') !== false || $scriptName === 'api/index.php') {
    http_response_code(403);
    exit('Acceso denegado');
}

$fullPath = __DIR__ . '/../' . $scriptName;

if (file_exists($fullPath) && pathinfo($fullPath, PATHINFO_EXTENSION) === 'php') {
    {
    
    require $fullPath;
    exit; 
}

http_response_code(404);
echo '<h1>404 - Página no encontrada</h1>';
exit;
