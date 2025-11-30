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
        header('Location: index.php');
        exit();
    } else {
        $_SESSION['error'] = "Error al guardar: " . $db->error;
        header('Location: formulario.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.A.B.E. - Sistema de Administración</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <style>
        /* Fuente del modal más grande y legible */
        .modal-body { font-size: 90% !important; }
        .form-control, .custom-select { font-size: 90%; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="jumbotron text-center bg-primary text-white">
            <h1>S.A.B.E. - Sistema de Administración de Bienestar Estudiantil</h1>
        </div>

        <h2 class="mt-4">Lista de estudiantes</h2>
        <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#myModal">
            + Ingresar nuevo estudiante
        </button>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ingresar nuevo estudiante</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>

                <div class="modal-body">
                    <?php
                    if (isset($_SESSION['exito'])) {
                        echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['exito']) . '</div>';
                        unset($_SESSION['exito']);
                    }
                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
                        unset($_SESSION['error']);
                    }
                    ?>

                    <form method="post" action="add.php">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Tipo de documento *</label>
                                <select class="form-control custom-select" name="tipo" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="TI">TI</option>
                                    <option value="RC">RC</option>
                                    <option value="Otro</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Documento *</label>
                                <input type="number" class="form-control" name="documento" maxlength="12" required placeholder="Máx. 12 dígitos">
                            </div>
                            <div class="col-md-3">
                                <label>Nombres *</label>
                                <input type="text" class="form-control" name="nombres" required>
                            </div>
                            <div class="col-md-3">
                                <label>Apellidos *</label>
                                <input type="text" class="form-control" name="apellidos" required>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label>Fecha nacimiento *</label>
                                <input type="date" class="form-control" name="fecha_nac" required>
                            </div>
                            <div class="col-md-3">
                                <label>Edad *</label>
                                <input type="number" class="form-control" name="edad" min="1" max="99" maxlength="2" required>
                            </div>
                            <div class="col-md-3">
                                <label>EPS *</label>
                                <input type="text" class="form-control" name="eps" required>
                            </div>
                            <div class="col-md-3">
                                <label>RH *</label>
                                <input type="text" class="form-control" name="rh" maxlength="2" style="text-transform:uppercase" required placeholder="Ej: A+">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label>Acudiente *</label>
                                <input type="text" class="form-control" name="acudiente" required>
                            </div>
                            <div class="col-md-4">
                                <label>Teléfono 1 *</label>
                                <input type="number" class="form-control" name="Telefono1" maxlength="10" required>
                            </div>
                            <div class="col-md-4">
                                <label>Teléfono 2</label>
                                <input type="number" class="form-control" name="Telefono2" maxlength="10">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label>Almuerzo *</label>
                                <select class="form-control custom-select" name="almuerzo" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Si">Sí</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Jornada *</label>
                                <select class="form-control custom-select" name="jornada" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Mañana">Mañana</option>
                                    <option value="Tarde">Tarde</option>
                                    <option value="Completa">Completa</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Grado *</label>
                                <select class="form-control custom-select" name="grado" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Sala cuna">Sala cuna</option>
                                    <option value="Exploradores">Exploradores</option>
                                    <option value="Párvulos">Párvulos</option>
                                    <option value="Pre-kínder">Pre-kínder</option>
                                    <option value="Kínder">Kínder</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Docente *</label>
                                <select class="form-control custom-select" name="docente" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Jizeth">Jizeth</option>
                                    <option value="Sandra">Sandra</option>
                                    <option value="Marisol">Marisol</option>
                                    <option value="Laura">Laura</option>
                                    <option value="Estefanía">Estefanía</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label>Matrícula *</label>
                                <select class="form-control custom-select" name="matricula" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Si">Sí</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Estado pensión *</label>
                                <select class="form-control custom-select" name="est_pension" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Al día">Al día</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Moroso">Moroso</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Valor pensión *</label>
                                <input type="number" class="form-control" name="val_pension" required min="0">
                            </div>
                            <div class="col-md-3">
                                <label>Fecha registro *</label>
                                <input type="date" class="form-control" name="fecha_reg" required value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Guardar Estudiante</button>
                            <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Aquí puedes poner tu tabla de estudiantes -->
    </div>
</body>
</html>