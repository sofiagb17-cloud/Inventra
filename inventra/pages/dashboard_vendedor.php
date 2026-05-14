<?php
// =========================================
// INVENTRA — Dashboard Vendedor
// =========================================

session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'vendedor') {
    header('Location: ../index.php');
    exit;
}

include('../includes/conexion.php');

$nombre        = htmlspecialchars($_SESSION['usuario_nombre']);
$iniciales     = strtoupper(substr($nombre, 0, 2));
$rol           = $_SESSION['usuario_rol'];
$pagina_activa = 'dashboard';

$total_productos = pg_fetch_result(pg_query($conn, "SELECT COUNT(*) FROM productos"), 0, 0);
$disponibles     = pg_fetch_result(pg_query($conn, "SELECT COUNT(*) FROM productos WHERE stock > 0"), 0, 0);
$mis_ventas      = pg_fetch_result(pg_query($conn, "SELECT COUNT(*) FROM movimientos WHERE tipo = 'salida' AND usuario_id = {$_SESSION['usuario_id']}"), 0, 0);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Vendedor — INVENTRA</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="layout">

    <?php include('../includes/sidebar.php'); ?>

    <div class="main">
        <div class="topbar">
            <div>
                <div class="topbar-title">Panel principal</div>
                <div class="topbar-sub">Bienvenido, <?= $nombre ?></div>
            </div>
            <a href="../auth/logout.php" class="btn-logout">Cerrar sesión</a>
        </div>

        <div class="main-content">

            <div class="stats-grid">
                <div class="stat-card">
                    <p class="stat-label">Total productos</p>
                    <p class="stat-val"><?= $total_productos ?></p>
                </div>
                <div class="stat-card success">
                    <p class="stat-label">Disponibles</p>
                    <p class="stat-val"><?= $disponibles ?></p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Mis ventas</p>
                    <p class="stat-val"><?= $mis_ventas ?></p>
                </div>
            </div>

            <div class="card">
                <h3>Productos disponibles</h3>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Stock</th>
                                <th>Precio</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $res = pg_query($conn, "SELECT nombre, categoria, stock, precio FROM productos ORDER BY nombre ASC");
                        while ($p = pg_fetch_assoc($res)):
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($p['nombre']) ?></td>
                                <td><?= htmlspecialchars($p['categoria']) ?></td>
                                <td class="<?= $p['stock'] <= 0 ? 'stock-bajo' : '' ?>"><?= $p['stock'] ?></td>
                                <td>$<?= number_format($p['precio'], 2) ?></td>
                                <td>
                                    <?php if ($p['stock'] > 0): ?>
                                        <span class="badge badge-ok">Disponible</span>
                                    <?php else: ?>
                                        <span class="badge badge-bajo">Agotado</span>
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
