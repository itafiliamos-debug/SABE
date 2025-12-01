<?php
session_start();
include 'db.php';

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['administrador', 'docente'])) {
    header("Location: login.php");
    exit();
}

$hoy = date('Y-m-d');

// === MARCAR ALMUERZO (AJAX) ===
if (isset($_POST['estudiante_id']) && isset($_POST['consumio'])) {
    $estudiante_id = (int)$_POST['estudiante_id'];
    $consumio = (int)$_POST['consumio'];

    // Verificar si el estudiante tiene almuerzo incluido
    $stmt = $db->prepare("SELECT almuerzo FROM estudiantes WHERE id = ?");
    $stmt->execute([$estudiante_id]);
    $almuerzo_incluido = $stmt->fetchColumn() === 'Sí';

    $cobrado = $almuerzo_incluido ? 0 : 1;
    $valor = $almuerzo_incluido ? 0 : 6500;

    $sql = "INSERT INTO almuerzos (estudiante_id, fecha, consumido, cobrado, valor_cobrado) 
            VALUES (?, ?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE consumido = ?, cobrado = ?, valor_cobrado = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$estudiante_id, $hoy, $consumio, $cobrado, $valor, $consumio, $cobrado, $valor]);

    echo "ok";
    exit();
}

// Obtener solo estudiantes que asistieron hoy
$stmt = $db->prepare("
    SELECT e.id, e.nombres, e.apellidos, e.almuerzo,
           a.consumido 
    FROM estudiantes e 
    LEFT JOIN almuerzos a ON e.id = a.estudiante_id AND a.fecha = ? 
    WHERE e.asistencia_hoy = ?
    ORDER BY e.apellidos, e.nombres
");
$stmt->execute([$hoy, $hoy]);
$estudiantes = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Almuerzo - S.A.B.E.</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background: #f8f9fa; font-size: 95%; }
        .card { box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .check-almuerzo { width: 25px; height: 25px; cursor: pointer; }
        .badge-si { background: #28a745; }
        .badge-no { background: #dc3545; }
        .navbar-fixed { position: sticky; top: 0; z-index: 1000; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-secondary shadow navbar-fixed">
    <div class="container-fluid">
        <a href="dashboard.php" class="navbar-brand">S.A.B.E.</a>
        <span class="text-white">Almuerzo del día: <?= date('d/m/Y') ?></span>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
    </div>
</nav>

<div class="container mt-4">
    <div class="text-center mb-4">
        <h2>Registrar Almuerzo del Día</h2>
        <p class="lead">Solo se muestran los niños que asistieron hoy</p>
    </div>

    <?php if(empty($estudiantes)): ?>
        <div class="alert alert-info text-center">
            <strong>No hay niños presentes hoy</strong> o todos ya tienen almuerzo registrado.
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-header bg-success text-white text-center">
                <h4>Total presentes: <?= count($estudiantes) ?> niños</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Estudiante</th>
                                <th>Almuerzo Incluido</th>
                                <th class="text-center">¿Comió Hoy?</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n = 1; foreach ($estudiantes as $e): 
                                $checked = $e['consumido'] ? 'checked' : '';
                                $badge = $e['almuerzo'] === 'Sí' ? 'badge-si' : 'badge-no';
                                $texto = $e['almuerzo'] === 'Sí' ? 'Incluido' : 'Extra ($6.500)';
                            ?>
                            <tr>
                                <td><?= $n++ ?></td>
                                <td><strong><?= htmlspecialchars($e['apellidos'] . ' ' . $e['nombres']) ?></strong></td>
                                <td><span class="badge <?= $badge ?>"><?= $texto ?></span></td>
                                <td class="text-center">
                                    <input type="checkbox" class="check-almuerzo" <?= $checked ?>
                                           onchange="marcarAlmuerzo(<?= $e['id'] ?>, this.checked)">
                                </td>
                                <td>
                                    <?php if ($e['consumido']): ?>
                                        <span class="text-success">Registrado</span>
                                        <?php if ($e['almuerzo'] === 'No'): ?>
                                            <small class="text-danger">(Se cobrará $6.500)</small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Pendiente</span>
                                    <?php endif; ?>
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
    <?php endif; ?>
</div>

<script>
// Marcar almuerzo con AJAX
function marcarAlmuerzo(id, checked) {
    let valor = checked ? 1 : 0;
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "almuerzo.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.responseText !== "ok") {
            alert("Error al registrar almuerzo");
            location.reload();
        }
    };
    xhr.send("estudiante_id=" + id + "&consumio=" + valor);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>