<?php
// =========================================
// INVENTRA — Gestión de Usuarios
// =========================================

session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

include('../includes/conexion.php');

$nombre        = htmlspecialchars($_SESSION['usuario_nombre']);
$iniciales     = strtoupper(substr($nombre, 0, 2));
$rol           = $_SESSION['usuario_rol'];
$pagina_activa = 'usuarios';

// Eliminar usuario (no puede eliminarse a sí mismo)
if (isset($_GET['eliminar'])) {
    $id = (int) $_GET['eliminar'];
    if ($id !== (int) $_SESSION['usuario_id']) {
        pg_query($conn, "DELETE FROM usuarios WHERE id = $id");
    }
    header('Location: usuarios.php?ok=eliminado');
    exit;
}

// Agregar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_u = trim(pg_escape_string($conn, $_POST['nombre']));
    $email    = trim(pg_escape_string($conn, $_POST['email']));
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $rol_u    = pg_escape_string($conn, $_POST['rol']);

    pg_query($conn, "INSERT INTO usuarios (nombre, email, password, rol) 
                     VALUES ('$nombre_u', '$email', '$password', '$rol_u')");
    header('Location: usuarios.php?ok=creado');
    exit;
}

$usuarios = pg_query($conn, "SELECT id, nombre, email, rol, created_at FROM usuarios ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios — INVENTRA</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="layout">

    <?php include('../includes/sidebar.php'); ?>

    <div class="main">
        <div class="topbar">
            <div>
                <div class="topbar-title">Usuarios</div>
                <div class="topbar-sub">Gestión de usuarios del sistema</div>
            </div>
            <a href="../auth/logout.php" class="btn-logout">Cerrar sesión</a>
        </div>

        <div class="main-content">

            <?php if (isset($_GET['ok'])): ?>
                <div class="alert alert-success">Operación realizada correctamente ✔</div>
            <?php endif; ?>

            <div class="card">
                <h3>Agregar usuario</h3>
                <form action="usuarios.php" method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nombre:</label>
                            <input type="text" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Contraseña:</label>
                            <input type="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label>Rol:</label>
                            <select name="rol">
                                <option value="vendedor">Vendedor</option>
                                <option value="almacenista">Almacenista</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Agregar usuario</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <h3>Lista de usuarios</h3>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Registrado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($u = pg_fetch_assoc($usuarios)): ?>
                            <tr>
                                <td><?= htmlspecialchars($u['nombre']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><span class="badge badge-<?= $u['rol'] ?>"><?= ucfirst($u['rol']) ?></span></td>
                                <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                                <td>
                                    <?php if ($u['id'] != $_SESSION['usuario_id']): ?>
                                        <a href="usuarios.php?eliminar=<?= $u['id'] ?>" class="btn btn-danger" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
                                    <?php else: ?>
                                        <span style="color:#a78bfa; font-size:12px;">Tu cuenta</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <?php include('../includes/footer.php'); ?>
    </div>

</div>
</body>
</html>
