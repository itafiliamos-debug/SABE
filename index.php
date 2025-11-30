<?php 
session_start(); 
include 'db.php'; 
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.fas fa-edit6.5.1/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <style>
        body { font-size: 90%; background-color: #f8f9fa; }
        .modal-body { font-size: 90% !important; }
        .form-control, .custom-select { font-size: 90%; }
        .btn-icon { width: 38px; height: 38px; padding: 0; }
        .table td { vertical-align: middle; }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="jumbotron bg-primary text-white text-center rounded">
        <h1 class="display-5">S.A.B.E.</h1>
        <p class="lead">Sistema de Administración de Bienestar Estudiantil</p>
    </div>

    <div class="text-right mb-3">
        <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#modalNuevo">
            <i class="fas fa-user-plus"></i> Nuevo Estudiante
        </button>
        <button class="btn btn-info btn-lg ml-2" onclick="window.print()">
            <i class="fas fa-print"></i> Imprimir
        </button>
    </div>

    <!-- Tabla -->
    <div class="card shadow">
        <div class="card-body">
            <h4 class="card-title"><i class="fas fa-users"></i> Lista de Estudiantes</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm">
                    <thead class="thead-dark">
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
                            <th>Última Act.</th>
                            <th width="100" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM estudiantes ORDER BY id DESC";
                        $result = $db->query($sql);
                        $n = 1;
                        while ($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= $n++ ?></td>
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
                                <!-- Botón Editar con ícono -->
                                <a href="editar.php?id=<?= $row['id'] ?>" 
                                   class="btn btn-warning btn-icon btn-sm" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- Botón Eliminar con ícono -->
                                <a href="eliminar.php?id=<?= $row['id'] ?>" 
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
            </div>
        </div>
    </div>
</div>

<!-- ====================== MODAL NUEVO ESTUDIANTE (igual que antes) ====================== -->
<div class="modal fade" id="modalNuevo" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user-plus"></i> Ingresar Nuevo Estudiante</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Mensajes de éxito/error -->
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

                <form action="add.php" method="POST">
                    <!-- Todo el formulario igual que en el mensaje anterior -->
                    <!-- (lo mantengo igual para que no se haga eterno el código) -->
                    <!-- ... aquí va todo el formulario con los select y maxlength que ya te pasé ... -->
                    
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>