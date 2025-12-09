<?php
session_start();
include 'db.php';

// Solo el administrador puede entrar
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

// === CAMBIAR ROL ===
if (isset($_POST['cambiar_rol'])) {
    $user_id = (int)$_POST['user_id'];
    $nuevo_rol = $_POST['nuevo_rol'];
    if (in_array($nuevo_rol, ['administrador', 'docente', 'alumno'])) {
        $stmt = $db->prepare("UPDATE usuarios SET rol = ? WHERE id = ?");
        $stmt->execute([$nuevo_rol, $user_id]);
        $_SESSION['exito'] = "Rol actualizado correctamente.";
    }
}

// === CAMBIAR CONTRASEÑA ===
if (isset($_POST['cambiar_pass'])) {
    $user_id = (int)$_POST['user_id'];
    $nueva_pass = $_POST['nueva_pass'];
    if (strlen($nueva_pass) >= 6) {
        $hash = password_hash($nueva_pass, PASSWORD_BCRYPT);
        $stmt = $db->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
        $stmt->execute([$hash, $user_id]);
        $_SESSION['exito'] = "Contraseña actualizada correctamente.";
    } else {
        $_SESSION['error'] = "La contraseña debe tener mínimo 6 caracteres.";
    }
    header("Location: usuarios.php");
    exit();
}

// Listar todos los usuarios
$stmt = $db->query("SELECT id, nombre, email, rol FROM usuarios ORDER BY nombre");
$usuarios = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Usuarios - S.A.B.E.</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background: #f8f9fa; font-size: 90%; }
        .card { box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .table th { background-color: #e9ecef; }
        .navbar-brand:hover { color: #000 !important; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark bg-secondary shadow mb-4">
    <div class="container-fluid">
        <a href="dashboard.php" class="navbar-brand">S.A.B.E.    Menú principal</a>
        <span class="text-white">Administrador: <?= $_SESSION['usuario']['nombre'] ?></span>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
    </div>
</nav>

<div class="container">
    <h2 class="text-center mb-4">Administración de Usuarios</h2>

    <?php if(isset($_SESSION['exito'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['exito']; unset($_SESSION['exito']); ?>
            <button type="button" class="close" data-dismiss="alert">×</button>
        </div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="close" data-dismiss="alert">×</button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Lista de Usuarios del Sistema</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol Actual</th>
                            <th>Cambiar Rol</th>
                            <th>Nueva Contraseña</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 1; foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?= $n++ ?></td>
                            <td><strong><?= htmlspecialchars($u['nombre']) ?></strong></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td>
                                <span class="badge badge-<?= $u['rol']=='administrador'?'danger':($u['rol']=='docente'?'info':'success') ?>">
                                    <?= ucfirst($u['rol']) ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                    <select name="nuevo_rol" class="form-control form-control-sm d-inline" style="width:120px;">
                                        <option value="administrador" <?= $u['rol']=='administrador'?'selected':'' ?>>Administrador</option>
                                        <option value="docente" <?= $u['rol']=='docente'?'selected':'' ?>>Docente</option>
                                        <option value="alumno" <?= $u['rol']=='alumno'?'selected':'' ?>>Alumno</option>
                                    </select>
                                    <button type="submit" name="cambiar_rol" class="btn btn-primary btn-sm ml-1">Cambiar</button>
                                </form>
                            </td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                    <input type="password" name="nueva_pass" class="form-control form-control-sm d-inline" 
                                           placeholder="Nueva contraseña" style="width:140px;" minlength="6">
                            </td>
                            <td>
                                    <button type="submit" name="cambiar_pass" class="btn btn-success btn-sm">Actualizar</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
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