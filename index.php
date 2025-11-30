<?php 
session_start(); 
include 'db.php';
$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
$perPage = (isset($_GET['per-page']) && (int)$_GET['per-page'] <= 50) ? (int)$_GET['per-page'] : 5;
$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;
$sql = "select * from estudiantes limit " . $start . "," . $perPage. " ";
$total = $db->query("select * from estudiantes")->num_rows;
$pages = ceil($total / $perPage);
$row = $db->query($sql);
$hoy = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.A.B.E. - Lista de Estudiantes</title>
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <style>
        body { font-size: 90%; background-color: #f8f9fa;}
        .modal-body { font-size: 90% !important; }
        .form-control, .custom-select { font-size: 90%; }
        .btn-icon { width: 30px; height: 30px; padding: 1; }
        .table td { vertical-align: middle; }
        .table th { cursor: pointer; }
        .table th:hover { background-color: #e9ecef; }
        .asistencia-check { width: 30px; height: 30px; }
        .buscador { border-radius: 30px; }
        .jumbotron { background: url("./img/logo.jpg") no-repeat center center; padding: 2rem 1rem; background-size: 50%; color: white; text-shadow: 2px 2px 4px #000000; }
    </style>
</head>
<body class="bg-light">

<div class="container mt-2 mb-2">
    <div class="jumbotron text-center">
        <h1 class="display-5">S.A.B.E.</h1>
        <h5 class="lead">Sistema de Administración de Bienestar Estudiantil <i class="fas fa-heart fa-1x text-danger"></i></h5>
        <br>
        <p><strong>Hoy:</strong> <?= date('d/m/Y') ?></p>
    </div>
    
    <!-- BUSCADOR -->
    <div class="row mb-4">
        <div class="col-md-6 offset-md-3">
            <input type="text" id="buscador" class="form-control buscador shadow-sm" 
                   placeholder="Buscar por nombre, documento o acudiente...">
        </div>
    </div>

    <!-- BOTÓN NUEVO ESTUDIANTE E IMPRIMIR -->
    <div class="text-right mb-3">
        <button type="button" class="btn btn-success btn-md" data-toggle="modal" data-target="#modalNuevo">
            <i class="fas fa-user-plus"></i> Nuevo Estudiante
        </button>
        <button class="btn btn-info btn-md ml-2" onclick="window.print()">
            <i class="fas fa-print"></i> Imprimir
        </button>
    </div>

    <!-- Tabla de estudiantes -->
    <div class="card shadow">
        <div class="card-body p-4">
            <h4 class="card-title"><i class="fas fa-users"></i> Lista de Estudiantes</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm" id="tablaEstudiantes">
                    <thead class="thead-light text-center">
                        <tr>
                            <th onclick="ordenarTabla(0)">#</th>
                            <th onclick="ordenarTabla(1)">Documento</th>
                            <th onclick="ordenarTabla(2)">Nombres</th>
                            <th onclick="ordenarTabla(3)">Apellidos</th>
                            <th onclick="ordenarTabla(4)">Edad</th>
                            <th onclick="ordenarTabla(5)">Grado</th>
                            <th onclick="ordenarTabla(6)">Docente</th>
                            <th onclick="ordenarTabla(7)">Almuerzo</th>
                            <th onclick="ordenarTabla(8)">Matrícula</th>
                            <th onclick="ordenarTabla(9)">Pensión</th>
                            <th onclick="ordenarTabla(10)">Jornada</th>
                            <th onclick="ordenarTabla(11)">Act.</th>
                            <th class="text-center">Asistió Hoy</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoTabla">
                        <?php
                        $sql = "SELECT * FROM estudiantes ORDER BY apellidos, nombres";
                        $result = $db->query($sql);
                        $contador = 1;
                        while ($row = $result->fetch_assoc()):
                            $asistioHoy = ($row['asistencia_hoy'] == $hoy) ? 'checked' : '';
                        ?>
                        <tr>
                            <td><?= $contador++ ?></td>
                            <td><?= htmlspecialchars($row['documento']) ?></td>
                            <td><?= htmlspecialchars($row['nombres']) ?></td>
                            <td><?= htmlspecialchars($row['apellidos']) ?></td>
                            <td><?= $row['edad'] ?></td>
                            <td><?= htmlspecialchars($row['grado']) ?></td>
                            <td><?= htmlspecialchars($row['docente']) ?></td>
                            <td><?= $row['almuerzo'] ?></td>
                            <td><?= $row['matricula'] ?></td>
                            <td>$<?= number_format($row['val_pension']) ?></td>
                            <td><?= htmlspecialchars($row['jornada']) ?></td>
                            <td><?= date('d/m/Y', strtotime($row['fecha_act'])) ?></td>

                            <td class="text-center">
                                <input type="checkbox" class="asistencia-check" <?= $asistioHoy ?>
                                       onchange="marcarAsistencia(<?= $row['id'] ?>, this.checked)">
                            </td>
                            <td class="text-center">
                                <!-- Botón Editar con ícono -->
                                <a href="update.php?id=<?= $row['id'] ?>" 
                                   class="btn btn-warning btn-icon btn-sm" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- Botón Eliminar con ícono -->
                                <a href="delete.php?id=<?= $row['id'] ?>" 
                                   class="btn btn-danger btn-icon btn-sm ml-1" 
                                   title="Eliminar"
                                   onclick="return confirm('¿Seguro que deseas eliminar este estudiante?')">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <!-- Paginación -->
                <nav>
                    <ul class="pagination">
                        <?php for($x = 1; $x <= $pages; $x++): ?>
                            <li class="page-item <?= ($page === $x) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $x ?>&per-page=<?= $perPage ?>"><?= $x ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- ==================== MODAL NUEVO ESTUDIANTE ==================== -->
<div class="modal fade" id="modalNuevo" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user-plus"></i> Ingresar Nuevo Estudiante</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
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
                        <div class="col-md-3 mb-3">
                            <label>Tipo Documento *</label>
                            <select name="tipo" class="form-control custom-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="TI">TI</option>
                                <option value="RC">RC</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Documento *</label>
                            <input type="number" name="documento" class="form-control" maxlength="12" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Nombres *</label>
                            <input type="text" name="nombres" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Apellidos *</label>
                            <input type="text" name="apellidos" class="form-control" required>
                        </div>

                        <!-- Fila 2 -->
                        <div class="col-md-3 mb-3">
                            <label>Fecha Nacimiento *</label>
                            <input type="date" name="fecha_nac" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Edad *</label>
                            <input type="number" name="edad" class="form-control" min="1" max="99" maxlength="2" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>EPS *</label>
                            <input type="text" name="eps" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>RH *</label>
                            <select name="rh" class="form-control custom-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="O+">O+</option>
                                <option value="A+">A+</option>
                                <option value="B+">B+</option>
                                <option value="AB+">AB+</option>
                                <option value="O-">O-</option>
                                <option value="A-">A-</option>
                                <option value="B-">B-</option>
                                <option value="AB-">AB-</option>
                            </select>
                        </div>

                        <!-- Fila 3 -->
                        <div class="col-md-4 mb-3">
                            <label>Acudiente *</label>
                            <input type="text" name="acudiente" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Teléfono 1 *</label>
                            <input type="number" name="Telefono1" class="form-control" maxlength="10" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Teléfono 2</label>
                            <input type="number" name="Telefono2" class="form-control" maxlength="10">
                        </div>

                        <!-- Fila 4 - Selects -->
                        <div class="col-md-3 mb-3">
                            <label>Almuerzo *</label>
                            <select name="almuerzo" class="form-control custom-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="Sí">Sí</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Jornada *</label>
                            <select name="jornada" class="form-control custom-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="Mañana">Mañana</option>
                                <option value="Tarde">Tarde</option>
                                <option value="Completa">Completa</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Grado *</label>
                            <select name="grado" class="form-control custom-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="Sala cuna">Sala cuna</option>
                                <option value="Exploradores">Exploradores</option>
                                <option value="Párvulos">Párvulos</option>
                                <option value="Pre-kínder">Pre-kínder</option>
                                <option value="Kínder">Kínder</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Docente *</label>
                            <select name="docente" class="form-control custom-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="Jizeth">Jizeth</option>
                                <option value="Sandra">Sandra</option>
                                <option value="Marisol">Marisol</option>
                                <option value="Laura">Laura</option>
                                <option value="Estefanía">Estefanía</option>
                            </select>
                        </div>

                        <!-- Fila 5 -->
                        <div class="col-md-3 mb-3">
                            <label>Matrícula *</label>
                            <select name="matricula" class="form-control custom-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="Sí">Sí</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Estado Pensión *</label>
                            <select name="est_pension" class="form-control custom-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="Al día">Al día</option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="Moroso">Moroso</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Valor Pensión *</label>
                            <input type="number" name="val_pension" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Fecha Registro *</label>
                            <input type="date" name="fecha_reg" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>

                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">Guardar Estudiante</button>
                        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// BUSCADOR EN TIEMPO REAL
document.getElementById('buscador').addEventListener('keyup', function() {
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll('#cuerpoTabla tr');
    filas.forEach(fila => {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(filtro) ? '' : 'none';
    });
});

// ORDENAR TABLA AL HACER CLIC EN ENCABEZADO
function ordenarTabla(n) {
    let tabla = document.getElementById("tablaEstudiantes");
    let filas = Array.from(tabla.rows).slice(1);
    let asc = tabla.getAttribute('data-orden') !== 'asc';
    
    filas.sort((a, b) => {
        let x = a.cells[n].textContent;
        let y = b.cells[n].textContent;
        return asc ? (x > y ? 1 : -1) : (x < y ? 1 : -1);
    });
    
    filas.forEach(fila => tabla.tBodies[0].appendChild(fila));
    tabla.setAttribute('data-orden', asc ? 'asc' : 'desc');
}

// MARCAR ASISTENCIA CON AJAX (sin recargar página)
function marcarAsistencia(id, checked) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "actualizar_asistencia.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.responseText !== "ok") {
            alert("Error al marcar asistencia");
            location.reload();
        }
    };
    xhr.send("id=" + id);
}
</script>

</body>
</html>