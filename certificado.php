<?php
session_start();
include 'db.php';

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['administrador', 'docente'])) {
    header("Location: login.php");
    exit();
}

$estudiante = null;
$mensaje = '';

if ($_POST && !empty($_POST['documento'])) {
    $doc = trim($_POST['documento']);
    $stmt = $db->prepare("SELECT * FROM estudiantes WHERE documento = ? LIMIT 1");
    $stmt->execute([$doc]);
    $estudiante = $stmt->fetch();

    if (!$estudiante) {
        $mensaje = "No se encontró estudiante con documento $doc";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Certificado - S.A.B.E.</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background: #f8f9fa; font-size: 95%; }
        .card { box-shadow: 0 6px 20px rgba(0,0,0,0.15); }
        .form-control:focus { border-color: #007bff; box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25); }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-secondary shadow mb-4">
    <div class="container-fluid">
        <a href="dashboard.php" class="navbar-brand">S.A.B.E.</a>
        <span class="text-white">Generar Certificado de Estudio</span>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
    </div>
</nav>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-white text-center">
                    <h4>Generar Certificado de Estudio</h4>
                </div>
                <div class="card-body">
                    <form method="POST" class="mb-4">
                        <div class="input-group input-group-lg">
                            <input type="text" name="documento" class="form-control" placeholder="Ingrese número de documento" required autofocus>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Buscar Estudiante</button>
                            </div>
                        </div>
                    </form>

                    <?php if ($mensaje): ?>
                        <div class="alert alert-warning text-center"><?= $mensaje ?></div>
                    <?php endif; ?>

                    <?php if ($estudiante): ?>
                        <div class="alert alert-success text-center">
                            <strong>Estudiante encontrado:</strong><br>
                            <?= htmlspecialchars($estudiante['nombres'] . ' ' . $estudiante['apellidos']) ?>
                        </div>

                        <div class="text-center">
                            <form action="generarPDF.php" method="POST" target="_blank">
                                <input type="hidden" name="id" value="<?= $estudiante['id'] ?>">
                                <button type="submit" class="btn btn-success btn-lg">
                                    Generar y Descargar PDF
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="dashboard.php" class="btn btn-secondary btn-lg">Volver al Dashboard</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>