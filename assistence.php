<?php
session_start();
include 'db.php';

if (!isset($_POST['id']) || !is_numeric($_POST['id])) {
    echo "error";
    exit();
}

$id = (int)$_POST['id'];
$hoy = date('Y-m-d');

$sql = "UPDATE estudiantes SET asistencia_hoy = '$hoy' WHERE id = $id";

if ($db->query($sql)) {
    echo "ok";
} else {
    echo "error";
}
?>