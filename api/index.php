<?php
header('Content-Type: text/html; charset=utf-8');

chdir(__DIR__ . '/..');

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptName = trim($requestUri, '/');

if (empty($scriptName)) {
    $scriptName = 'index.php';
}

if (file_exists($scriptName) && pathinfo($scriptName, PATHINFO_EXTENSION) === 'php') {
    try {
        include $scriptName;
    } catch (Exception $e) {
        echo 'Error en página: ' . $e->getMessage();
    }
    exit;
}
header('Content-Type: text/html; charset=utf-8');
http_response_code(404);
echo '<html><body><h1>Página no encontrada</h1></body></html>';
?>
