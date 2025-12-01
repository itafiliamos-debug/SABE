<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: dashboard.php");
    exit();
}

$mensaje = '';
if ($_POST) {
    include 'db.php';
    
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario && password_verify($password, $usuario['password'])) {
        $_SESSION['usuario'] = [
            'id'    => $usuario['id'],
            'nombre'=> $usuario['nombre'],
            'email' => $usuario['email'],
            'rol'   => $usuario['rol']
        ];
        header("Location: dashboard.php");
        exit();
    } else {
        $mensaje = "Email o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - S.A.B.E.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #a8e6cf, #764ba2, #ff9a9e); height: 100vh; }
        .login-box { max-width: 400px; margin: auto; margin-top: 5%; }
        .card { border-radius: 35px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
    </style>
</head>
<body>
<div class="login-box">
    <div class="card"  >
        <div class="card-body p-5">
            <h3 class="text-center mb-4"><i class="fas fa-school text-primary"></i> S.A.B.E.</h3>
            <h5 class="text-center mb-4">Iniciar Sesión</h5>
            
            <?php if($mensaje): ?>
                <div class="alert alert-danger"><?= $mensaje ?></div>
            <?php endif; ?>
            
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-sign-in-alt"></i> Entrar
                </button>
            </form>
            
            <div class="text-center mt-3">
                <small>Prueba:<br>
                admin@sabe.com | docente@sabe.com | alumnos@sabe.com<br>
                Contraseña: <strong>123456</strong></small>
            </div>
        </div>
    </div>
</div>
</body>
</html>