<?php
session_start();
$mensaje = '';

// Configuración del correo destino
$correo_destino = "atencionbandura@sabe.com";
$asunto_base    = "Nueva Opinión / Sugerencia - Jardín Infantil Bandura";

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nombre    = trim($_POST['nombre'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $telefono  = trim($_POST['telefono'] ?? '');
    $opinion   = trim($_POST['opinion'] ?? '');

    if (empty($nombre) || empty($email) || empty($opinion)) {
        $mensaje = '<div class="alert alert-danger">Por favor completa todos los campos obligatorios.</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = '<div class="alert alert-danger">Ingresa un correo electrónico válido.</div>';
    } else {
        // Construir el mensaje
        $mensaje_correo = "
            <html>
            <body style='font-family: Arial, sans-serif; line-height: 1.6;'>
                <h2>Nueva Opinión Recibida</h2>
                <p><strong>Fecha:</strong> " . date('d/m/Y H:i') . "</p>
                <hr>
                <p><strong>Nombre:</strong> $nombre</p>
                <p><strong>Correo:</strong> $email</p>
                <p><strong>Teléfono:</strong> $telefono</p>
                <hr>
                <p><strong>Opinión / Sugerencia:</strong></p>
                <blockquote style='background:#f9f9f9; padding:15px; border-left:5px solid #007bff;'>
                    " . nl2br(htmlspecialchars($opinion)) . "
                </blockquote>
                <br>
                <small>Enviado desde el formulario de Opiniones - S.A.B.E.</small>
            </body>
            </html>
        ";

        // Cabeceras para enviar como HTML
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: no-reply@sabe.com\r\n";
        $headers .= "Reply-To: $email\r\n";

        // Enviar correo
        if (mail($correo_destino, $asunto_base, $mensaje_correo, $headers)) {
            $mensaje = '<div class="alert alert-success text-center">
                <i class="fas fa-check-circle fa-2x"></i><br>
                ¡Gracias por tu opinión! Hemos recibido tu mensaje correctamente.
            </div>';
            // Limpiar campos
            $nombre = $email = $telefono = $opinion = '';
        } else {
            $mensaje = '<div class="alert alert-danger">
                Hubo un error al enviar tu mensaje. Inténtalo más tarde.
            </div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opiniones y Sugerencias | Jardín Infantil Bandura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #a8e6cf, #dcedc1, #ff9a9e);
            min-height: 100vh;
            font-family: 'Comic Neue', cursive;
        }
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            background: rgba(255,255,255,0.95);
        }
        .btn-enviar {
            background: #27ae60;
            color: white;
            font-weight: bold;
            padding: 12px 40px;
            border-radius: 50px;
            transition: all 0.3s;
        }
        .btn-enviar:hover {
            background: #219653;
            transform: scale(1.05);
        }
        .footer-contact {
            background: rgba(0,0,0,0.1);
            border-radius: 15px;
            padding: 20px;
            margin-top: 30px;
        }
        textarea {
            resize: vertical;
            min-height: 150px;
        }
    </style>
</head>
<body class="d-flex align-items-center py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            <div class="text-center mb-4">
                <h1 class="display-4 text-primary fw-bold">
                    Opiniones y Sugerencias
                </h1>
                <p class="lead text-secondary">
                    Tu opinión nos ayuda a mejorar cada día
                </p>
            </div>

            <div class="card p-4 p-md-5">
                <div class="card-body">

                    <?= $mensaje ?>

                    <form method="POST" action="">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nombre completo *</label>
                                <input type="text" name="nombre" class="form-control form-control-lg" 
                                       value="<?= htmlspecialchars($nombre ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Correo electrónico *</label>
                                <input type="email" name="email" class="form-control form-control-lg" 
                                       value="<?= htmlspecialchars($email ?? '') ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Teléfono (opcional)</label>
                                <input type="text" name="telefono" class="form-control form-control-lg" 
                                       value="<?= htmlspecialchars($telefono ?? '') ?>" placeholder="Ej: 3001234567">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Tu opinión o sugerencia *</label>
                                <textarea name="opinion" class="form-control form-control-lg" 
                                          placeholder="Escribe aquí todo lo que quieras decirnos..." required><?= htmlspecialchars($opinion ?? '') ?></textarea>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-enviar btn-lg px-5">
                                Enviar Opinión
                            </button>
                        </div>
                    </form>

                    <div class="footer-contact text-center mt-5">
                        <p class="mb-2">
                            También puedes escribirnos directamente a:
                        </p>
                        <h4 class="text-primary fw-bold">
                            atencionbandura@sabe.com
                        </h4>
                        <small class="text-muted">
                            Responderemos lo más pronto posible
                        </small>
                    </div>

                </div>
            </div>

            <div class="text-center mt-4">
                <a href="dashboard.php" class="btn btn-outline-light btn-lg">
                    Volver
                </a>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>