<?php
// =========================================
// INVENTRA — Gestión de Proveedores
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
$pagina_activa = 'proveedores';

// Eliminar proveedor
if (isset($_GET['eliminar'])) {
    $id = (int) $_GET['eliminar'];
    pg_query($conn, "DELETE FROM proveedores WHERE id = $id");
    header('Location: proveedores.php?ok=eliminado');
    exit;
}

// Agregar proveedor
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_p = trim(pg_escape_string($conn, $_POST['nombre']));
    $contacto = trim(pg_escape_string($conn, $_POST['contacto']));
    $email    = trim(pg_escape_string($conn, $_POST['email']));
    $telefono = trim(pg_escape_string($conn, $_POST['telefono']));

    pg_query($conn, "INSERT INTO proveedores (nombre, contacto, email, telefono) 
                     VALUES ('$nombre_p', '$contacto', '$email', '$telefono')");
    header('Location: proveedores.php?ok=creado');
    exit;
}

$proveedores = pg_query($conn, "SELECT * FROM proveedores ORDER BY nombre ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores — INVENTRA</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="layout">

    <?php include('../includes/sidebar.php'); ?>

    <div class="main">
        <div class="topbar">
            <div>
                <div class="topbar-title">Proveedores</div>
                <div class="topbar-sub">Gestión de proveedores</div>
            </div>
            <a href="../auth/logout.php" class="btn-logout">Cerrar sesión</a>
        </div>

        <div class="main-content">

            <?php if (isset($_GET['ok'])): ?>
                <div class="alert alert-success">Operación realizada correctamente ✔</div>
            <?php endif; ?>

            <div class="card">
                <h3>Agregar proveedor</h3>
                <form action="proveedores.php" method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nombre:</label>
                            <input type="text" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label>Contacto:</label>
                            <input type="text" name="contacto">
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" name="email">
                        </div>
                        <div class="form-group">
                            <label>Teléfono:</label>
                            <input type="text" name="telefono">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Agregar proveedor</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <h3>Lista de proveedores</h3>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Contacto</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (pg_num_rows($proveedores) === 0): ?>
                            <tr><td colspan="5" style="text-align:center; color:#a78bfa;">No hay proveedores registrados.</td></tr>
                        <?php else: ?>
                            <?php while ($p = pg_fetch_assoc($proveedores)): ?>
                            <tr>
                                <td><?= htmlspecialchars($p['nombre']) ?></td>
                                <td><?= htmlspecialchars($p['contacto']) ?></td>
                                <td><?= htmlspecialchars($p['email']) ?></td>
                                <td><?= htmlspecialchars($p['telefono']) ?></td>
                                <td>
                                    <a href="proveedores.php?eliminar=<?= $p['id'] ?>" class="btn btn-danger" onclick="return confirm('¿Eliminar este proveedor?')">Eliminar</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
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
