<?php
chdir(__DIR__ . '/..');

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptName = trim($requestUri, '/');

if (empty($scriptName)) {
    $scriptName = 'index.php';
}

if (file_exists($scriptName) && pathinfo($scriptName, PATHINFO_EXTENSION) === 'php') {
    include $scriptName;
    exit;
}

http_response_code(404);
echo 'Not Found';
?>