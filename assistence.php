<?php
session_start();
include 'db.php';

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo "error";
    exit();
}

$id = (int)$_POST['id'];
$hoy = date('Y-m-d');

try {
    $sql = "UPDATE estudiantes SET asistencia_hoy = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$hoy, $id]);
    echo "ok";
} catch (Exception $e) {
    echo "error";
}
?>