<?php
session_start();
include 'db.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

// === AGREGAR DOCENTE ===
if ($_POST && isset($_POST['nuevo_docente'])) {
    $nombre = trim($_POST['nuevo_docente']);
    if ($nombre !== '') {
        $stmt = $db->prepare("INSERT INTO docentes (nombre) VALUES (?)");
        $stmt->execute([$nombre]);
        $_SESSION['exito'] = "Docente agregado correctamente.";
    } else {
        $_SESSION['error'] = "El nombre no puede estar vacío.";
    }
    header("Location: docentes.php");
    exit();
}

// === ELIMINAR DOCENTE ===
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $stmt = $db->prepare("DELETE FROM docentes WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['exito'] = "Docente eliminado correctamente.";
    header("Location: docentes.php");
    exit();
}

// Obtener todos los docentes
$stmt = $db->query("SELECT * FROM docentes ORDER BY nombre");
$docentes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Docentes - S.A.B.E.</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background: #f8f9fa; font-size: 90%; }
        .card { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-secondary mb-4">
    <div class="container-fluid">
        <a href="dashboard.php" class="navbar-brand">S.A.B.E.</a>
        <span class="text-white">Admin: <?= $_SESSION['usuario']['nombre'] ?></span>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
    </div>
</nav>

<div class="container">
    <h2 class="mb-4 text-center">Gestión de Docentes</h2>

    <?php if(isset($_SESSION['exito'])): ?>
        <div class="alert alert-success alert-dismissible">
            <?= $_SESSION['exito']; unset($_SESSION['exito']); ?>
            <button type="button" class="close" data-dismiss="alert">×</button>
        </div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="close" data-dismiss="alert">×</button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Lista de Docentes</h5>
        </div>
        <div class="card-body">
            <!-- Formulario para agregar -->
            <form method="POST" class="mb-4">
                <div class="input-group">
                    <input type="text" name="nuevo_docente" class="form-control" placeholder="Nombre completo del docente" required>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-success">Agregar Docente</button>
                    </div>
                </div>
            </form>

            <!-- Lista de docentes -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Nombre del Docente</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 1; foreach ($docentes as $d): ?>
                        <tr>
                            <td><?= $n++ ?></td>
                            <td><?= htmlspecialchars($d['nombre']) ?></td>
                            <td>
                                <a href="docentes.php?eliminar=<?= $d['id'] ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('¿Eliminar a <?= htmlspecialchars($d['nombre']) ?>?')">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($docentes)): ?>
                        <tr><td colspan="3" class="text-center text-muted">No hay docentes registrados aún.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-secondary btn-lg">Volver al Dashboard</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>