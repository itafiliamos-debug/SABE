<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

chdir(__DIR__ . '/..');

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptName = ltrim($requestUri, '/');

if ($scriptName === '' || $scriptName === 'index.php') {
    $scriptName = 'index.php';
}

if (strpos($scriptName, '..') !== false || strpos($scriptName, 'api/') === 0) {
    http_response_code(403);
    echo '<h1>Acceso denegado</h1>';
    exit;
}

$fullPath = __DIR__ . '/../' . $scriptName;

if (file_exists($fullPath) && pathinfo($fullPath, PATHINFO_EXTENSION) === 'php') {
    
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method === 'GET' || $method === 'POST') {
        // Ejecuta el archivo
        require $fullPath;
    } else {
        // Otros métodos → 405
        header('Allow: GET, POST');
        http_response_code(405);
        echo '<h1>405 Método no permitido</h1>';
    }
    exit;  
}

http_response_code(404);
echo '<html><body><h1>404 - Página no encontrada en SABE</h1></body></html>';
exit;
?>