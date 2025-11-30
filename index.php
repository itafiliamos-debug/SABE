<?php
session_start();
include 'db.php';

// === PROTECCIÓN DE ACCESO ===
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
if (!in_array($_SESSION['usuario']['rol'], ['administrador', 'docente'])) {
    die("Acceso denegado.");
}

// === PAGINACIÓN ===
$perPage = isset($_GET['per-page']) && in_array($_GET['per-page'], [10, 25, 50, 100]) ? (int)$_GET['per-page'] : 25;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;
$hoy = date('Y-m-d');

// Total estudiantes
$total_stmt = $db->query("SELECT COUNT(*) FROM estudiantes");
$total = $total_stmt->fetchColumn();
$pages = ceil($total / $perPage);

// Estudiantes paginados
$stmt = $db->prepare("SELECT * FROM estudiantes ORDER BY apellidos, nombres LIMIT ? OFFSET ?");
$stmt->bindValue(1, $perPage, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$estudiantes = $stmt->fetchAll();
$contador = $offset + 1;

// Cargar docentes dinámicamente
$docentes_stmt = $db->query("SELECT nombre FROM docentes ORDER BY nombre");
$docentes = $docentes_stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.A.B.E. - Lista de Estudiantes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-size: 90%; background-color: #f8f9fa; }
        .modal-body { font-size: 90% !important; }
        .form-control, .custom-select { font-size: 90%; }
        .btn-icon { width: 34px; height: 34px; padding: 0; line-height: 34px; }
        .table th { cursor: pointer; }
        .table th:hover { background-color: #e9ecef; }
        .asistencia-check { width: 30x; height: 30px; cursor: pointer; }
        .buscador { border-radius: 30px; }
        .jumbotron {
            background: url("./img/logo.jpg") no-repeat center center;
            background-size: 45%;
            padding: 3rem 1rem;
            color: white;
            text-shadow: 2px 2px 6px #000;
            margin-bottom: 1.5rem;
        }
        .navbar-fixed { position: sticky; top: 0; z-index: 1000; }
    </style>
</head>
<body class="bg-light">

<!-- Barra superior fija -->
<nav class="navbar navbar-dark bg-secondary shadow navbar-fixed">
    <div class="container-fluid">
        <a href="dashboard.php" class="navbar-brand mb-0 h5">S.A.B.E.</a>
        <div class="text-white">
            <i class="fas fa-user-circle"></i>
            <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>
            (<?= ucfirst($_SESSION['usuario']['rol']) ?>)
            &nbsp; | &nbsp;
            <a href="logout.php" class="text-white"><u>Cerrar sesión</u></a>
        </div>
    </div>
</nav>

<div class="container mt-3">

    <!-- Jumbotron con logo -->
    <div class="jumbotron text-center">
        <h1 class="display-5">S.A.B.E.</h1>
        <h5 class="lead">Sistema de Administración de Bienestar Estudiantil</h5>
        <h4><strong>Hoy es:</strong> <?= date('d/m/Y') ?></h4>
    </div>

    <!-- Buscador -->
    <div class="row mb-3">
        <div class="col-md-6 offset-md-3">
            <input type="text" id="buscador" class="form-control buscador shadow-sm" 
                   placeholder="Buscar por nombre, documento, acudiente...">
        </div>
    </div>

    <!-- Botones principales -->
    <div class="text-right mb-3">
        <button type="button" class="btn btn-success btn-md" data-toggle="modal" data-target="#modalNuevo">
            Nuevo Estudiante
        </button>
        <button class="btn btn-info btn-md ml-2" onclick="window.print()">
            Imprimir
        </button>
    </div>

    <!-- Tabla -->
    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0" id="tablaEstudiantes">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Documento</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Edad</th>
                            <th>Grado</th>
                            <th>Docente</th>
                            <th>Almuerzo</th>
                            <th>Matrícula</th>
                            <th>Pensión</th>
                            <th>Jornada</th>
                            <th>Act.</th>
                            <th>Asistió Hoy</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoTabla">
                        <?php foreach ($estudiantes as $e):
                            $asistioHoy = ($e['asistencia_hoy'] == $hoy) ? 'checked' : '';
                        ?>
                        <tr>
                            <td><?= $contador++ ?></td>
                            <td><?= htmlspecialchars($e['documento']) ?></td>
                            <td><?= htmlspecialchars($e['nombres']) ?></td>
                            <td><?= htmlspecialchars($e['apellidos']) ?></td>
                            <td><?= $e['edad'] ?></td>
                            <td><?= htmlspecialchars($e['grado']) ?></td>
                            <td><?= htmlspecialchars($e['docente']) ?></td>
                            <td><?= $e['almuerzo'] ?></td>
                            <td><?= $e['matricula'] ?></td>
                            <td>$<?= number_format($e['val_pension']) ?></td>
                            <td><?= htmlspecialchars($e['jornada']) ?></td>
                            <td><?= date('d/m/Y', strtotime($e['fecha_act'])) ?></td>
                            <td class="text-center">
                                <input type="checkbox" class="asistencia-check" <?= $asistioHoy ?>
                                       onchange="marcarAsistencia(<?= $e['id'] ?>, this.checked)">
                            </td>
                            <td class="text-center">
                                <a href="update.php?id=<?= $e['id'] ?>" class="btn btn-warning btn-icon btn-sm" title="Editar">
                                    Editar
                                </a>
                                <a href="delete.php?id=<?= $e['id'] ?>" class="btn btn-danger btn-icon btn-sm ml-1" title="Eliminar"
                                   onclick="return confirm('¿Eliminar estudiante?')">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación bonita -->
            <?php if ($pages > 1): ?>
            <nav class="p-3 bg-light border-top">
                <ul class="pagination justify-content-center mb-0 mb-0">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page-1 ?>&per-page=<?= $perPage ?>">Anterior</a>
                    </li>
                    <?php for($i = max(1, $page-3); $i <= min($page+3, $pages); $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&per-page=<?= $perPage ?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page >= $pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page+1 ?>&per-page=<?= $perPage ?>">Siguiente</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ==================== MODAL NUEVO ESTUDIANTE ==================== -->
<div class="modal fade" id="modalNuevo" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Ingresar Nuevo Estudiante</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <?php if(isset($_SESSION['exito'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['exito']; unset($_SESSION['exito']); ?></div>
                <?php endif; ?>
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form action="add.php" method="POST">
                    <div class="row">
                        <!-- Fila 1 -->
                        <div class="col-md-3 mb-3"><label>Tipo Documento *</label>
                            <select name="tipo" class="form-control custom-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="TI">TI</option><option value="RC">RC</option><option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3"><label>Documento *</label><input type="number" name="documento" class="form-control" required></div>
                        <div class="col-md-3 mb-3"><label>Nombres *</label><input type="text" name="nombres" class="form-control" required></div>
                        <div class="col-md-3 mb-3"><label>Apellidos *</label><input type="text" name="apellidos" class="form-control" required></div>

                        <!-- Fila 2 -->
                        <div class="col-md-3 mb-3"><label>Fecha Nacimiento *</label><input type="date" name="fecha_nac" class="form-control" required></div>
                        <div class="col-md-3 mb-3"><label>Edad *</label><input type="number" name="edad" class="form-control" min="1" max="99" required></div>
                        <div class="col-md-3 mb-3"><label>EPS *</label><input type="text" name="eps" class="form-control" required></div>
                        <div class="col-md-3 mb-3"><label>RH *</label>
                            <select name="rh" class="form-control custom-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="O+">O+</option><option value="A+">A+</option><option value="B+">B+</option>
                                <option value="AB+">AB+</option><option value="O-">O-</option><option value="A-">A-</option>
                                <option value="B-">B-</option><option value="AB-">AB-</option>
                            </select>
                        </div>

                        <!-- Fila 3 -->
                        <div class="col-md-4 mb-3"><label>Acudiente *</label><input type="text" name="acudiente" class="form-control" required></div>
                        <div class="col-md-4 mb-3"><label>Teléfono 1 *</label><input type="number" name="Telefono1" class="form-control" maxlength="10" required></div>
                        <div class="col-md-4 mb-3"><label>Teléfono 2</label><input type="number" name="Telefono2" class="form-control" maxlength="10"></div>

                        <!-- Fila 4 - Selects -->
                        <div class="col-md-3 mb-3"><label>Almuerzo *</label>
                            <select name="almuerzo" class="form-control custom-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="Sí">Sí</option><option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3"><label>Jornada *</label>
                            <select name="jornada" class="form-control custom-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="Mañana">Mañana</option><option value="Tarde">Tarde</option><option value="Completa">Completa</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3"><label>Grado *</label>
                            <select name="grado" class="form-control custom-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="Sala cuna">Sala cuna</option>
                                <option value="Exploradores">Exploradores</option>
                                <option value="Párvulos">Párvulos</option>
                                <option value="Pre-kínder">Pre-kínder</option>
                                <option value="Kínder">Kínder</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3"><label>Docente *</label>
                            <select name="docente" class="form-control custom-select" required>
                                <option value="">Seleccionar docente...</option>
                                <?php foreach ($docentes as $docente): ?>
                                    <option value="<?= htmlspecialchars($docente) ?>"><?= htmlspecialchars($docente) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Fila 5 -->
                        <div class="col-md-3 mb-3"><label>Matrícula *</label>
                            <select name="matricula" class="form-control custom-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="Sí">Sí</option><option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3"><label>Estado Pensión *</label>
                            <select name="est_pension" class="form-control custom-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="Al día">Al día</option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="Moroso">Moroso</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3"><label>Valor Pensión *</label><input type="number" name="val_pension" class="form-control" min="0" required></div>
                        <div class="col-md-3 mb-3"><label>Fecha Registro *</label><input type="date" name="fecha_reg" class="form-control" value="<?= date('Y-m-d') ?>" required></div>
                    </div>

                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary btn-lg">Guardar Estudiante</button>
                        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

<script>
// Buscador en tiempo real
document.getElementById('buscador').addEventListener('keyup', function() {
    let filtro = this.value.toLowerCase();
    document.querySelectorAll('#cuerpoTabla tr').forEach(tr => {
        tr.style.display = tr.textContent.toLowerCase().includes(filtro) ? '' : 'none';
    });
});

// Ordenar columnas
function ordenarTabla(n) {
    let tabla = document.getElementById("tablaEstudiantes");
    let filas = Array.from(tabla.rows).slice(1);
    let asc = tabla.getAttribute('data-orden') !== 'asc';
    filas.sort((a, b) => (a.cells[n].textContent > b.cells[n].textContent ? 1 : -1) * (asc ? 1 : -1));
    filas.forEach(fila => tabla.tBodies[0].appendChild(fila));
    tabla.setAttribute('data-orden', asc ? 'asc' : 'desc');
}

// Marcar asistencia AJAX
function marcarAsistencia(id, checked) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "assistence.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.responseText.trim() !== "ok") {
            alert("Error al registrar asistencia");
            location.reload();
        }
    };
    xhr.send("id=" + id);
}
</script>
</body>
</html>