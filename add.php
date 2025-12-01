<?php 
session_start(); 
include 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $campos_requeridos = ['tipo', 'documento', 'nombres', 'apellidos', 'fecha_nac', 'edad', 'eps', 'rh', 'grado', 'docente', 'acudiente', 'Telefono1', 'almuerzo', 'matricula', 'val_pension', 'est_pension', 'jornada', 'fecha_reg'];
    $faltantes = [];

    foreach ($campos_requeridos as $campo) {
        if (empty(trim($_POST[$campo]))) {
            $faltantes[] = $campo;
        }
    }

    if (!empty($faltantes)) {
        $_SESSION['error'] = "Faltan datos obligatorios.";
        $_SESSION['campos_faltantes'] = $faltantes;
        $_SESSION['datos_viejos'] = $_POST;
        header('Location: formulario.php');
        exit();
    }

    // Sanitización
    $tipo        = $db->real_escape_string($_POST['tipo']);
    $documento   = $db->real_escape_string($_POST['documento']);
    $nombres     = $db->real_escape_string($_POST['nombres']);
    $apellidos   = $db->real_escape_string($_POST['apellidos']);
    $fecha_nac   = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['fecha_nac'])));
    $edad        = (int)$_POST['edad'];
    $eps         = $db->real_escape_string($_POST['eps']);
    $rh          = $db->real_escape_string($_POST['rh']);
    $grado       = $db->real_escape_string($_POST['grado']);
    $docente     = $db->real_escape_string($_POST['docente']);
    $acudiente   = $db->real_escape_string($_POST['acudiente']);
    $telefono1   = $db->real_escape_string($_POST['Telefono1']);
    $telefono2   = $db->real_escape_string($_POST['Telefono2'] ?? '');
    $almuerzo    = $db->real_escape_string($_POST['almuerzo']);
    $matricula   = $db->real_escape_string($_POST['matricula']);
    $val_pension = $db->real_escape_string($_POST['val_pension']);
    $est_pension = $db->real_escape_string($_POST['est_pension']);
    $jornada     = $db->real_escape_string($_POST['jornada']);
    $fecha_reg   = $db->real_escape_string($_POST['fecha_reg']);
    $fecha_act   = date('Y-m-d H:i:s');

    $query = "INSERT INTO estudiantes (
        tipo, documento, nombres, apellidos, fecha_nac, edad, eps, rh, grado,
        docente, acudiente, telefono1, telefono2, almuerzo, matricula,
        val_pension, est_pension, jornada, fecha_reg, fecha_act
    ) VALUES (
        '$tipo', '$documento', '$nombres', '$apellidos', '$fecha_nac', $edad,
        '$eps', '$rh', '$grado', '$docente', '$acudiente', '$telefono1',
        '$telefono2', '$almuerzo', '$matricula', '$val_pension', '$est_pension',
        '$jornada', '$fecha_reg', '$fecha_act'
    )";

    if ($db->query($query) === TRUE) {
        $_SESSION['exito'] = "Estudiante registrado correctamente.";
        header('Location: estudiantes.php');
        exit();
    } else {
        $_SESSION['error'] = "Error al guardar: " . $db->error;
        header('Location: formulario.php');
        exit();
    }
}
?>