<?php
session_start();
include 'db.php';

// Protección
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['administrador', 'docente'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recibir y limpiar datos (NO uses real_escape_string con PDO)
    $tipo        = $_POST['tipo'] ?? '';
    $documento   = $_POST['documento'] ?? '';
    $nombres     = trim($_POST['nombres'] ?? '');
    $apellidos   = trim($_POST['apellidos'] ?? '');
    $fecha_nac   = $_POST['fecha_nac'] ?? '';
    $edad        = (int)($_POST['edad'] ?? 0);
    $eps         = trim($_POST['eps'] ?? '');
    $rh          = strtoupper(trim($_POST['rh'] ?? ''));
    $acudiente   = trim($_POST['acudiente'] ?? '');
    $telefono1   = $_POST['Telefono1'] ?? '';
    $telefono2   = $_POST['Telefono2'] ?? '';
    $almuerzo    = $_POST['almuerzo'] ?? '';
    $jornada     = $_POST['jornada'] ?? '';
    $grado       = $_POST['grado'] ?? '';
    $docente     = $_POST['docente'] ?? '';
    $matricula   = $_POST['matricula'] ?? '';
    $est_pension = $_POST['est_pension'] ?? '';
    $val_pension = (int)($_POST['val_pension'] ?? 0);
    $fecha_reg   = $_POST['fecha_reg'] ?? date('Y-m-d');

    // Validación básica
    if (empty($documento) || empty($nombres) || empty($apellidos) || empty($acudiente)) {
        $_SESSION['error'] = "Los campos obligatorios no pueden estar vacíos.";
        header("Location: estudiantes.php");
        exit();
    }

    try {
        $sql = "INSERT INTO estudiantes (
                    tipo, documento, nombres, apellidos, fecha_nac, edad, eps, rh,
                    acudiente, telefono1, telefono2, almuerzo, jornada, grado, docente,
                    matricula, est_pension, val_pension, fecha_reg, fecha_act
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()
                )";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            $tipo, $documento, $nombres, $apellidos, $fecha_nac, $edad, $eps, $rh,
            $acudiente, $telefono1, $telefono2, $almuerzo, $jornada, $grado, $docente,
            $matricula, $est_pension, $val_pension, $fecha_reg
        ]);

        $_SESSION['exito'] = "Estudiante agregado correctamente.";
        header("Location: estudiantes.php");
        exit();

    } catch (PDOException $e) {
        // Si el documento ya existe (clave duplicada)
        if ($e->getCode() == 23000) {
            $_SESSION['error'] = "Ya existe un estudiante con ese número de documento.";
        } else {
            $_SESSION['error'] = "Error al guardar: " . $e->getMessage();
        }
        header("Location: estudiantes.php");
        exit();
    }
}
?>