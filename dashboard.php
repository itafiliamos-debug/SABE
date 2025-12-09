<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
$rol = $_SESSION['usuario']['rol'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - S.A.B.E.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        .jumbotron {
            background: url("./img/logo.jpg") no-repeat center center;
            background-size: 45%;
            padding: 9rem 0.5rem;
            color: white;
            text-shadow: 2px 2px 6px #000;
            margin-bottom: 1rem;
            border-radius: 30px;
        }
        .btn-almuerzo {
            background: #ff7300ff;
            color: black;
        }
        .btn-opinion {
            background: linear-gradient(135deg, #a8e6cf, #dcedc1, #ff9a9e);
            color: black;
        }
        .navbar-brand:hover { color: #000 !important; }
    </style>

</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php"><i class="fas fa-school"></i> S.A.B.E.</a>
        <div class="d-flex align-items-center text-white">
            <span class="me-3"><?= $_SESSION['usuario']['nombre'] ?> (<?= ucfirst($rol) ?>)</span>
            <a href="logout.php" class="btn btn-outline-light btn-sm"><i class="fas fa-sign-out-alt"></i> Salir</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2>Bienvenid@, <?= $_SESSION['usuario']['nombre'] ?></h2>
    
    <div class="row mt-4">
        <!-- ADMINISTRADOR -->
        <?php if($rol === 'administrador'): ?>
            <div class="col-md-3 mb-3"><a href="estudiantes.php" class="btn btn-success btn-lg w-100"><i class="fas fa-users"></i> Gestión de Alumnos</a></div>
            <div class="col-md-3 mb-3"><a href="docentes.php" class="btn btn-info btn-lg w-100"><i class="fas fa-chalkboard-teacher"></i> Gestión Docentes</a></div>
            <div class="col-md-3 mb-3"><a href="certificado.php" class="btn btn-warning btn-lg w-100"><i class="fas fa-file-alt"></i> Certificados</a></div>
            <div class="col-md-3 mb-3"><a href="usuarios.php" class="btn btn-primary btn-lg w-100"><i class="fas fa-user-cog"></i> Administrar Usuarios</a></div>
        <?php endif; ?>

        <!-- DOCENTE -->
        <?php if($rol === 'docente'): ?>
            <div class="col-md-6 mb-3"><a href="estudiantes.php" class="btn btn-success btn-lg w-100"><i class="fas fa-clipboard-check"></i> Tomar Asistencia</a></div>
            <div class="col-md-6 mb-3"><a href="almuerzo.php" class="btn btn-almuerzo btn-lg w-100"><i class="fas fa-utensils"></i> Registrar Almuerzo</a></div>
        <?php endif; ?>

        <!-- ALUMNO -->
        <?php if($rol === 'alumno'): ?>
            <div class="col-md-6 mb-3"><a href="certificado.php" class="btn btn-warning btn-lg w-100"><i class="fas fa-file-alt"></i> Solicitar Certificado</a></div>
            <div class="col-md-6 mb-3"><a href="opiniones.php" class="btn btn-opinion btn-lg w-100"><i class="fas fa-comment"></i> Dejar Opinión</a></div>
        <?php endif; ?>
    </div>
    <!-- Jumbotron con logo -->
    <div class="jumbotron text-center">
        <h1 class="display-4">S.A.B.E.</h1>
        <br><br>
        <h4><strong>Hoy es:</strong> <?= date('d/m/Y') ?></h4>
    </div>

</div>
</body>
</html>