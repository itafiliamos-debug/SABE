<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jardín Infantil Bandura | Bienvenidos 2026</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Lobster&display=swap" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fredoka+One&family=Comic+Neue:wght@700;900&display=swap');

        body, html {
            height: 100%;
            margin: 0;
            overflow: hidden;
            background: linear-gradient(-45deg, #ff9a9e, #fad0c4, #a8e6cf, #dcedc1);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            font-family: 'Comic Neue', cursive;
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .home-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            text-align: center;
            padding: 20px;
        }

        .image-logo {
            background: url("./img/globo.png") no-repeat center center;
            background-size: 75%;
            padding: 3rem 1rem;
            color: white;
            text-shadow: 2px 2px 6px #000;
            margin-bottom: 1.5rem;
        }

        .title {
            font-family: 'Pacifico', 'Lobster', 'Segoe Script', 'Comic Sans MS', cursive;
            font-weight: normal;
            font-size: 4rem;    
            color: #3F94CA;
            text-shadow: 3px 3px 0px #2775a5ff, 10px 10px 0px rgba(0,0,0,0.1);
            margin-top: 3rem;
            animation: bounceIn 2s;
        }

        .year-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }

        .year-prefix {
            font-size: 10rem;
            font-weight: 900;
            color: #b206ddff;
            text-shadow: 8px 8px 0px #370344ff;
            letter-spacing: 8px;
            font-family: 'Fredoka One', cursive;
        }

        .number-block {
            width: 20%;
            position: relative;
            text-align: left;
            margin-left: 10px;
        }

        .number-5, .number-6 {
            font-size: 10rem;
            font-weight: 900;
            font-family: 'Fredoka One', cursive;
            text-shadow: 8px 8px 0px #370344ff;
            display: inline-block;
        }

        .number-5 {
            color: #b206ddff;
            animation: explode5 3s forwards;
            animation-delay: 2s;
        }

        .number-6 {
            position: absolute;
            font-size: 11rem;
            top: 50%;
            left: 1px;
            transform: translateY(-50%) scale(0);
            color: #27ae60;
            text-shadow: 8px 8px 0px #1e8449;
            opacity: 0;
            animation: appear6 2s forwards;
            animation-delay: 4s;
        }

        #welcomeText {
            font-size: 2.6rem;
            color: #2c3e50;
            margin-top: 10px;
            opacity: 0;
            transition: opacity 2s ease-in-out;
            font-weight: 700;
        }

        .login-btn {
            position: absolute;
            top: 10px;
            right: 40px;
            font-size: 2.8rem;
            color: #2c3e50;
            background: rgba(255,255,255,0.95);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 12px 30px rgba(0,0,0,0.25);
            cursor: pointer;
            transition: all 0.4s;
            z-index: 1000;
        }
        .login-btn:hover {
            transform: scale(1.25) rotate(12deg);
            background: #3498db;
            color: white;
        }

        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #f1c40f;
            animation: confetti-fall 3.5s linear infinite;
        }

        .fade-out {
            animation: fadeOut 1.8s forwards;
        }

        @keyframes bounceIn {
            0%,20%,40%,60%,80%,100%{transition-timing-function:cubic-bezier(.215,.61,.355,1)}
            0%{opacity:0;transform:scale3d(.3,.3,.3)}
            20%{transform:scale3d(1.1,1.1,1.1)}
            40%{transform:scale3d(.9,.9,.9)}
            60%{opacity:1;transform:scale3d(1.03,1.03,1.03)}
            80%{transform:scale3d(.97,.97,.97)}
            100%{opacity:1;transform:scale3d(1,1,1)}
        }

        @keyframes explode5 {
            0% { transform: scale(1) rotate(0); opacity: 1; }
            70% { transform: scale(1.5) rotate(20deg); opacity: 0.8; }
            100% { transform: scale(0) rotate(360deg); opacity: 0; }
        }

        @keyframes appear6 {
            0% { transform: translateY(-48%) scale(0) rotate(-360deg); opacity: 0; }
            70% { transform: translateY(-48%) scale(1.2); }
            100% { transform: translateY(-48%) scale(1); opacity: 1; }
        }

        @keyframes confetti-fall {
            0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
        }

        @keyframes fadeOut {
            to { opacity: 0; transform: scale(0.9); filter: blur(12px); }
        }
    </style>
</head>
<body>

<div class="home-container" id="homeScreen">
    <!-- Candado login -->
    <div class="login-btn" id="loginBtn">
        <i class="fas fa-user-lock"></i>
    </div>
    <!-- Logo e título -->
    <div class="image-logo">
    <h1 class="title">Jardín Infantil<br>Bandura</h1>
    </div>

    <!-- Año dividido 70% / 30% -->
    <div class="year-container">
        <div style="width: 70%; text-align:left; padding-right: 20px;">
            <span class="year-prefix">202</span>
        </div>
        <div class="number-block">
            <span class="number-5" id="num5">5</span>
            <span class="number-6" id="num6">6</span>
        </div>
    </div>

    <!-- Texto que aparece al final -->
    <p id="welcomeText">¡Bienvenidos al año 2026!</p>
</div>

<!-- Contenedor confeti -->
<div id="confetti-container"></div>

<script>
    // Animación completa + confeti + texto final
    setTimeout(() => {
        // Confeti
        const container = document.getElementById('confetti-container');
        for(let i = 0; i < 180; i++) {
            const c = document.createElement('div');
            c.className = 'confetti';
            c.style.left = Math.random() * 100 + 'vw';
            c.style.background = ['#f1c40f','#e74c3c','#3498db','#2ecc71','#9b59b6','#ff6b6b'][Math.floor(Math.random()*6)];
            c.style.animationDelay = Math.random() * 2.5 + 's';
            c.style.width = c.style.height = Math.random() * 12 + 6 + 'px';
            container.appendChild(c);
        }

        // Mostrar texto de bienvenida
        setTimeout(() => {
            document.getElementById('welcomeText').style.opacity = '0.85';
        }, 2000);

    }, 4000);

    // Ir al login
    document.getElementById('loginBtn').addEventListener('click', () => {
        document.getElementById('homeScreen').classList.add('fade-out');
        setTimeout(() => {
            window.location.href = 'login.php';
        }, 1800);
    });
</script>

</body>
</html>