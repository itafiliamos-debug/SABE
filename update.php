<?php
session_start();
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID no válido.";
    header("Location: estudiantes.php");
    exit();
}

$id = (int)$_GET['id'];

// === OBTENER ESTUDIANTE CON PDO (CORRECTO) ===
$stmt = $db->prepare("SELECT * FROM estudiantes WHERE id = ? LIMIT 1");
$stmt->execute([$id]);
$est = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$est) {
    $_SESSION['error'] = "Estudiante no encontrado.";
    header("Location: estudiantes.php");
    exit();
}

// === CARGAR DOCENTES DINÁMICAMENTE ===
$docentes = $db->query("SELECT DISTINCT nombre FROM docentes ORDER BY nombre")->fetchAll(PDO::FETCH_COLUMN);

// === PROCESAR FORMULARIO ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo        = $_POST['tipo'];
    $documento   = $_POST['documento'];
    $nombres     = trim($_POST['nombres']);
    $apellidos   = trim($_POST['apellidos']);
    $fecha_nac   = $_POST['fecha_nac'];
    $edad        = (int)$_POST['edad'];
    $eps         = trim($_POST['eps']);
    $rh          = strtoupper(trim($_POST['rh']));
    $grado       = $_POST['grado'];
    $docente     = $_POST['docente'];
    $acudiente   = trim($_POST['acudiente']);
    $telefono1   = $_POST['Telefono1'];
    $telefono2   = $_POST['Telefono2'] ?? '';
    $almuerzo    = $_POST['almuerzo'];
    $matricula   = $_POST['matricula'];
    $val_pension = (int)$_POST['val_pension'];
    $est_pension = $_POST['est_pension'];
    $jornada     = $_POST['jornada'];
    $fecha_reg   = $_POST['fecha_reg'];

    try {
        $update = $db->prepare("UPDATE estudiantes SET
            tipo = ?, documento = ?, nombres = ?, apellidos = ?, fecha_nac = ?,
            edad = ?, eps = ?, rh = ?, grado = ?, docente = ?, acudiente = ?,
            telefono1 = ?, telefono2 = ?, almuerzo = ?, matricula = ?,
            val_pension = ?, est_pension = ?, jornada = ?, fecha_reg = ?, fecha_act = NOW()
            WHERE id = ?");

        $update->execute([
            $tipo, $documento, $nombres, $apellidos, $fecha_nac,
            $edad, $eps, $rh, $grado, $docente, $acudiente,
            $telefono1, $telefono2, $almuerzo, $matricula,
            $val_pension, $est_pension, $jornada, $fecha_reg, $id
        ]);

        $_SESSION['exito'] = "Estudiante actualizado correctamente.";
        header("Location: estudiantes.php");
        exit();

    } catch (Exception $e) {
        $error = "Error al guardar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Estudiante - S.A.B.E.</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body { font-size: 90%; background: #f8f9fa; }
        .card { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="container mt-4 mb-5">
    <h2 class="text-center text-success mb-4">
        <i class="fas fa-user-edit"></i> Editar Estudiante
    </h2>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="">
                <div class="row">

                    <!-- Fila 1 -->
                    <div class="col-md-3 mb-3">
                        <label>Tipo Documento *</label>
                        <select name="tipo" class="form-control custom-select" required>
                            <option value="TI"    <?= $est['tipo']=='TI'?'selected':'' ?>>TI</option>
                            <option value="RC"    <?= $est['tipo']=='RC'?'selected':'' ?>>RC</option>
                            <option value="Otro"  <?= $est['tipo']=='Otro'?'selected':'' ?>>Otro</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Documento *</label>
                        <input type="number" name="documento" class="form-control" value="<?= $est['documento'] ?>" maxlength="12" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Nombres *</label>
                        <input type="text" name="nombres" class="form-control" value="<?= $est['nombres'] ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Apellidos *</label>
                        <input type="text" name="apellidos" class="form-control" value="<?= $est['apellidos'] ?>" required>
                    </div>

                    <!-- Fila 2 -->
                    <div class="col-md-3 mb-3">
                        <label>Fecha Nacimiento *</label>
                        <input type="date" name="fecha_nac" class="form-control" value="<?= $est['fecha_nac'] ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Edad *</label>
                        <input type="number" name="edad" class="form-control" value="<?= $est['edad'] ?>" min="1" max="99" maxlength="2" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>EPS *</label>
                        <input type="text" name="eps" class="form-control" value="<?= $est['eps'] ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>RH *</label>
                        <input type="text" name="rh" class="form-control" value="<?= $est['rh'] ?>" maxlength="2" style="text-transform:uppercase;" required>
                    </div>

                    <!-- Fila 3 -->
                    <div class="col-md-4 mb-3">
                        <label>Acudiente *</label>
                        <input type="text" name="acudiente" class="form-control" value="<?= $est['acudiente'] ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Teléfono 1 *</label>
                        <input type="number" name="Telefono1" class="form-control" value="<?= $est['telefono1'] ?>" maxlength="10" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Teléfono 2</label>
                        <input type="number" name="Telefono2" class="form-control" value="<?= $est['telefono2'] ?>" maxlength="10">
                    </div>

                    <!-- Fila 4 - Selects -->
                    <div class="col-md-3 mb-3">
                        <label>Almuerzo *</label>
                        <select name="almuerzo" class="form-control custom-select" required>
                            <option value="Sí" <?= $est['almuerzo']=='Sí'?'selected':'' ?>>Sí</option>
                            <option value="No" <?= $est['almuerzo']=='No'?'selected':'' ?>>No</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Jornada *</label>
                        <select name="jornada" class="form-control custom-select" required>
                            <option value="Mañana"  <?= $est['jornada']=='Mañana'?'selected':'' ?>>Mañana</option>
                            <option value="Tarde"    <?= $est['jornada']=='Tarde'?'selected':'' ?>>Tarde</option>
                            <option value="Completa" <?= $est['jornada']=='Completa'?'selected':'' ?>>Completa</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Grado *</label>
                        <select name="grado" class="form-control custom-select" required>
                            <option value="Sala cuna"     <?= $est['grado']=='Sala cuna'?'selected':'' ?>>Sala cuna</option>
                            <option value="Exploradores"  <?= $est['grado']=='Exploradores'?'selected':'' ?>>Exploradores</option>
                            <option value="Párvulos"     <?= $est['grado']=='Párvulos'?'selected':'' ?>>Párvulos</option>
                            <option value="Pre-kínder"   <?= $est['grado']=='Pre-kínder'?'selected':'' ?>>Pre-kínder</option>
                            <option value="Kínder"       <?= $est['grado']=='Kínder'?'selected':'' ?>>Kínder</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Docente *</label>
                        <select name="docente" class="form-control custom-select" required>
                            <option value="Jizeth"     <?= $est['docente']=='Jizeth'?'selected':'' ?>>Jizeth</option>
                            <option value="Sandra"     <?= $est['docente']=='Sandra'?'selected':'' ?>>Sandra</option>
                            <option value="Marisol"    <?= $est['docente']=='Marisol'?'selected':'' ?>>Marisol</option>
                            <option value="Laura"      <?= $est['docente']=='Laura'?'selected':'' ?>>Laura</option>
                            <option value="Estefanía"  <?= $est['docente']=='Estefanía'?'selected':'' ?>>Estefanía</option>
                        </select>
                    </div>

                    <!-- Fila 5 -->
                    <div class="col-md-3 mb-3">
                        <label>Matrícula *</label>
                        <select name="matricula" class="form-control custom-select" required>
                            <option value="Sí" <?= $est['matricula']=='Sí'?'selected':'' ?>>Sí</option>
                            <option value="No" <?= $est['matricula']=='No'?'selected':'' ?>>No</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Estado Pensión *</label>
                        <select name="est_pension" class="form-control custom-select" required>
                            <option value="Al día"    <?= $est['est_pension']=='Al día'?'selected':'' ?>>Al día</option>
                            <option value="Pendiente" <?= $est['est_pension']=='Pendiente'?'selected':'' ?>>Pendiente</option>
                            <option value="Moroso"    <?= $est['est_pension']=='Moroso'?'selected':'' ?>>Moroso</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Valor Pensión *</label>
                        <input type="number" name="val_pension" class="form-control" value="<?= $est['val_pension'] ?>" min="0" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Fecha Registro *</label>
                        <input type="date" name="fecha_reg" class="form-control" value="<?= $est['fecha_reg'] ?>" required>
                    </div>

                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                    <a href="estudiantes.php" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i> Volver al Listado
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
</body>
</html>