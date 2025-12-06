<?php

$host     = $_ENV['MYSQL_HOST']     ?? 'localhost';
$database = $_ENV['MYSQL_DATABASE'] ?? 'sabe';
$username = $_ENV['MYSQL_USER']     ?? 'root';
$password = $_ENV['MYSQL_PASSWORD'] ?? '';
$port     = $_ENV['MYSQL_PORT']     ?? '3306';
$ssl      = isset($_ENV['MYSQL_SSL']) ? true : false;

$dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

if ($ssl) {
    $options[PDO::MYSQL_ATTR_SSL_CA] = true; 
    $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
}

try {
    $db = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    
    error_log("DB Error: " . $e->getMessage());
    die("Error de conexión a la base de datos");
}