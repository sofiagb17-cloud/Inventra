<?php
// =========================================
// INVENTRA — Dashboard Almacenista
// =========================================

session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'almacenista') {
    header('Location: ../index.php');
    exit;
}

include('../includes/conexion.php');

$nombre        = htmlspecialchars($_SESSION['usuario_nombre']);
$iniciales     = strtoupper(substr($nombre, 0, 2));
$rol           = $_SESSION['usuario_rol'];
$pagina_activa = 'dashboard';

$total_productos = pg_fetch_result(pg_query($conn, "SELECT COUNT(*) FROM productos"), 0, 0);
$stock_bajo      = pg_fetch_result(pg_query($conn, "SELECT COUNT(*) FROM productos WHERE stock <= stock_minimo"), 0, 0);
$entradas_hoy    = pg_fetch_result(pg_query($conn, "SELECT COUNT(*) FROM movimientos WHERE tipo = 'entrada' AND DATE(created_at) = CURRENT_DATE"), 0, 0);
$salidas_hoy     = pg_fetch_result(pg_query($conn, "SELECT COUNT(*) FROM movimientos WHERE tipo = 'salida'  AND DATE(created_at) = CURRENT_DATE"), 0, 0);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Almacenista — INVENTRA</title>
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
                <div class="stat-card success">
                    <p class="stat-label">Total productos</p>
                    <p class="stat-val"><?= $total_productos ?></p>
                </div>
                <div class="stat-card danger">
                    <p class="stat-label">Stock bajo</p>
                    <p class="stat-val"><?= $stock_bajo ?></p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Entradas hoy</p>
                    <p class="stat-val"><?= $entradas_hoy ?></p>
                </div>
                <div class="stat-card warning">
                    <p class="stat-label">Salidas hoy</p>
                    <p class="stat-val"><?= $salidas_hoy ?></p>
                </div>
            </div>

            <div class="card">
                <h3>⚠ Alertas de stock bajo</h3>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Stock actual</th>
                                <th>Stock mínimo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $res = pg_query($conn, "SELECT nombre, categoria, stock, stock_minimo FROM productos WHERE stock <= stock_minimo ORDER BY stock ASC");
                        if (pg_num_rows($res) === 0):
                        ?>
                            <tr><td colspan="5" style="text-align:center; color:#a78bfa;">No hay alertas de stock bajo ✔</td></tr>
                        <?php else: ?>
                            <?php while ($p = pg_fetch_assoc($res)): ?>
                            <tr>
                                <td><?= htmlspecialchars($p['nombre']) ?></td>
                                <td><?= htmlspecialchars($p['categoria']) ?></td>
                                <td class="stock-bajo"><?= $p['stock'] ?></td>
                                <td><?= $p['stock_minimo'] ?></td>
                                <td><span class="badge badge-bajo">Stock bajo</span></td>
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
