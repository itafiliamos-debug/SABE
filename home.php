<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jardín Infantil Bandura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            overflow: hidden;
            background: linear-gradient(135deg, #a8e6cf, #dcedc1, #ffd3b6, #ffaaa5);
            font-family: 'Comic Neue', 'Arial', sans-serif;
            color: #333;
        }
        .container-full {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .title {
            text-align: center;
            font-size: 5.5rem;
            font-weight: 900;
            color: #2c6fbb;
            text-shadow: 4px 4px 10px rgba(0,0,0,0.2);
            letter-spacing: 3px;
        }
        .year {
            font-size: 8rem;
            font-weight: 900;
            color: #ff6b6b;
            margin-top: -20px;
            position: relative;
        }
        .crane {
            position: absolute;
            top: 20%;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            opacity: 0;
            animation: craneDown 4s ease-in-out 2s forwards;
        }
        .number5, .number6 {
            position: absolute;
            font-size: 8rem;
            font-weight: 900;
            color: #ff6b6b;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .number5 { z-index: 2; }
        .number6 { opacity: 0; z-index: 1; }

        .login-icon {
            position: absolute;
            top: 30px;
            right: 40px;
            font-size: 2.5rem;
            color: #2c6fbb;
            cursor: pointer;
            transition: all 0.4s;
            z-index: 1000;
        }
        .login-icon:hover { transform: scale(1.2); color: #1a4971; }

        .fade-out {
            animation: fadeOut 1.5s forwards;
        }

        @keyframes craneDown {
            0% { top: -100px; opacity: 0; }
            50% { top: 35%; opacity: 1; }
            70% { top: 40%; opacity: 1; }
            100% { top: -100px; opacity: 0; }
        }
        @keyframes lift5 {
            0% { transform: translate(-50%, -50%) rotate(0); }
            100% { transform: translate(-50%, -300px) rotate(360deg); opacity: 0; }
        }
        @keyframes drop6 {
            0% { transform: translate(-50%, -300px) scale(0); opacity: 0; }
            100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
        }
        @keyframes fadeOut {
            to { opacity: 0; transform: scale(0.95); }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@700;900&display=swap" rel="stylesheet">
</head>
<body>

<div class="container-full" id="homeScreen">
    <!-- Icono login (esquina superior derecha) -->
    <div class="login-icon" id="loginBtn">
        <i class="fas fa-user-lock"></i>
    </div>

    <!-- Contenido principal -->
    <div class="text-center">
        <h1 class="title">JARDÍN INFANTIL<br>BANDURA</h1>

        <div class="year position-relative d-inline-block">
            <span class="number5" id="num5">5</span>
            <span class="number6" id="num6">6</span>
            <div class="crane" id="crane">
                <img src="https://i.imgur.com/9f0t3jM.png" alt="Grúa" width="200">
            </div>
        </div>
        <h2 style="font-size: 4rem; color: #2c6fbb; margin-top: -30px;">202</h2>
    </div>
</div>

<script>
    // Animación de la grúa al cargar la página
    setTimeout(() => {
        document.getElementById('crane').style.opacity = 1;
        document.getElementById('num5').style.animation = 'lift5 3s ease-in-out 1s forwards';
        document.getElementById('num6').style.animation = 'drop6 2s ease-in-out 3s forwards';
    }, 1500);

    // Al hacer clic en el icono → disolver y redirigir al login
    document.getElementById('loginBtn').addEventListener('click', function() {
        document.getElementById('homeScreen').classList.add('fade-out');
        setTimeout(() => {
            window.location.href = 'login.php'; // ← Cambia por tu página de login
        }, 1500);
    });
</script>

</body>
</html>