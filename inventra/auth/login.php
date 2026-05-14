<?php
// =========================================
// INVENTRA — Procesar Login
// =========================================
// Este archivo solo recibe el POST del formulario,
// valida credenciales y redirige. No muestra HTML.

session_start();
include('../includes/conexion.php');

// Solo acepta peticiones POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$email    = trim(pg_escape_string($conn, $_POST['email']));
$password = $_POST['password'];

// Busca el usuario por email
$sql       = "SELECT id, nombre, password, rol FROM usuarios WHERE email = '$email' LIMIT 1";
$resultado = pg_query($conn, $sql);

if ($resultado && pg_num_rows($resultado) === 1) {
    $usuario = pg_fetch_assoc($resultado);

    // Verifica la contraseña hasheada
    if (password_verify($password, $usuario['password'])) {
        $_SESSION['usuario_id']     = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_rol']    = $usuario['rol'];

        // Redirige al dashboard (que detecta el rol automáticamente)
        header('Location: ../pages/dashboard.php');
        exit;
    }
}

// Si falla, vuelve al login con mensaje de error
header('Location: ../index.php?error=1');
exit;
?>
