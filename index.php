<?php
if (isset($_COOKIE[session_name()])) {
    session_start();
    if (isset($_SESSION['usuario_id'])) {
        header('Location: pages/dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <!-- RESPONSIVE -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INVENTRA — Iniciar sesión</title>

    <!-- CAMBIÉ v=3 por v=10 -->
    <link rel="stylesheet" href="css/styles.css?v=10">
</head>
<body>

<!-- NUEVO CONTENEDOR -->
<div class="container-login">

    <!-- PANEL IZQUIERDO -->
    <div class="left">

        <div class="blob blob1"></div>
        <div class="blob blob2"></div>

        <!-- Logo -->
        <div>
            <div class="logo-circle">
                <img src="img/logo.jpeg" alt="INVENTRA">
            </div>

            <div class="brand-sub">
                SISTEMA DE CONTROL DE INVENTARIO EMPRESARIAL
            </div>
        </div>

        <!-- Texto -->
        <div class="left-mid">

            <div class="big-title">
                Gestiona tu inventario<br>
                <span>con inteligencia</span>
            </div>

            <div class="desc">
                Una plataforma completa para el control empresarial de productos, proveedores y equipos de trabajo.
            </div>

            <div class="feat">
                <div class="feat-icon">📦</div>
                <span class="feat-text">
                    Control de stock en tiempo real
                </span>
            </div>

            <div class="feat">
                <div class="feat-icon">👥</div>
                <span class="feat-text">
                    Múltiples roles y permisos
                </span>
            </div>

            <div class="feat">
                <div class="feat-icon">📊</div>
                <span class="feat-text">
                    Reportes y estadísticas
                </span>
            </div>

        </div>

        <!-- Footer -->
        <div class="left-bot">
            <span class="pill">
                © 2026 Inventra — Todos los derechos reservados
            </span>
        </div>

    </div>

    <!-- PANEL DERECHO -->
    <div class="right">

        <div class="card">

            <div class="card-title">
                Bienvenidos
            </div>

            <div class="card-sub">
                Ingresa tus credenciales para continuar
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert-error">
                    Correo o contraseña incorrectos.
                </div>
            <?php endif; ?>

            <form action="auth/login.php" method="POST">

                <div class="inp-wrap">
                    <span class="inp-label">
                        CORREO ELECTRÓNICO
                    </span>

                    <input
                        type="email"
                        name="email"
                        placeholder="tu@empresa.com"
                        required
                    >
                </div>

                <div class="inp-wrap">
                    <span class="inp-label">
                        CONTRASEÑA
                    </span>

                    <input
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <button type="submit" class="btn-in">
                    INICIAR SESIÓN
                </button>

            </form>

            <div class="links">
                <a href="auth/recuperar.php">
                    ¿Olvidó su contraseña? Recupérela aquí
                </a>
            </div>

        </div>

    </div>

</div>

</body>
</html>
