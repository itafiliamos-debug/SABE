<?php
session_start();
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];

// Eliminar
$query = "DELETE FROM estudiantes WHERE id = $id";

if ($db->query($query)) {
    $_SESSION['exito'] = "Estudiante eliminado correctamente.";
} else {
    $_SESSION['error'] = "Error al eliminar: " . $db->error;
}

header("Location: index.php");
exit();
?>