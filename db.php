<?php
// db.php → VERSIÓN 100% COMPATIBLE CON VERCEL (2025)

$host     = $_ENV['MYSQL_HOST'] ?? $_SERVER['MYSQL_HOST'] ?? 'localhost';
$dbname   = $_ENV['MYSQL_DATABASE'] ?? $_SERVER['MYSQL_DATABASE'] ?? 'dbsabe';
$username = $_ENV['MYSQL_USER'] ?? $_SERVER['MYSQL_USER'] ?? 'root';
$password = $_ENV['MYSQL_PASSWORD'] ?? $_SERVER['MYSQL_PASSWORD'] ?? '';
$port     = $_ENV['MYSQL_PORT'] ?? $_SERVER['MYSQL_PORT'] ?? '3306';

// Para PlanetScale, Neon, Railway, etc. (SSL opcional)
$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $db = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    error_log("DB Connection failed: " . $e->getMessage());
    http_response_code(500);
    die("Error interno del servidor");
}
?>