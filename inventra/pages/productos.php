<?php
// =========================================
// INVENTRA — Gestión de Productos
// =========================================

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

include('../includes/conexion.php');

$rol           = $_SESSION['usuario_rol'];
$nombre        = htmlspecialchars($_SESSION['usuario_nombre']);
$iniciales     = strtoupper(substr($nombre, 0, 2));
$pagina_activa = 'productos';

// Eliminar producto (solo admin)
if (isset($_GET['eliminar']) && $rol === 'admin') {
    $id = (int) $_GET['eliminar'];
    pg_query($conn, "DELETE FROM productos WHERE id = $id");
    header('Location: productos.php?ok=eliminado');
    exit;
}

// Agregar producto (solo admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $rol === 'admin') {
    $nombre_p     = trim(pg_escape_string($conn, $_POST['nombre']));
    $categoria    = trim(pg_escape_string($conn, $_POST['categoria']));
    $stock        = (int) $_POST['stock'];
    $stock_minimo = (int) $_POST['stock_minimo'];
    $precio       = (float) $_POST['precio'];
    $proveedor_id = !empty($_POST['proveedor_id']) ? (int) $_POST['proveedor_id'] : 'NULL';

    pg_query($conn, "INSERT INTO productos (nombre, categoria, stock, stock_minimo, precio, proveedor_id) 
                     VALUES ('$nombre_p', '$categoria', $stock, $stock_minimo, $precio, $proveedor_id)");
    header('Location: productos.php?ok=creado');
    exit;
}

$productos   = pg_query($conn, "SELECT p.*, pr.nombre as proveedor FROM productos p LEFT JOIN proveedores pr ON p.proveedor_id = pr.id ORDER BY p.nombre ASC");
$proveedores = pg_query($conn, "SELECT id, nombre FROM proveedores ORDER BY nombre ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos — INVENTRA</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="layout">

    <?php include('../includes/sidebar.php'); ?>

    <div class="main">
        <div class="topbar">
            <div>
                <div class="topbar-title">Productos</div>
                <div class="topbar-sub">Gestión de inventario</div>
            </div>
            <a href="../auth/logout.php" class="btn-logout">Cerrar sesión</a>
        </div>

        <div class="main-content">

            <?php if (isset($_GET['ok'])): ?>
                <div class="alert alert-success">Operación realizada correctamente ✔</div>
            <?php endif; ?>

            <!-- Formulario para agregar (solo admin) -->
            <?php if ($rol === 'admin'): ?>
            <div class="card">
                <h3>Agregar nuevo producto</h3>
                <form action="productos.php" method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nombre:</label>
                            <input type="text" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label>Categoría:</label>
                            <input type="text" name="categoria">
                        </div>
                        <div class="form-group">
                            <label>Stock inicial:</label>
                            <input type="number" name="stock" min="0" value="0">
                        </div>
                        <div class="form-group">
                            <label>Stock mínimo:</label>
                            <input type="number" name="stock_minimo" min="0" value="5">
                        </div>
                        <div class="form-group">
                            <label>Precio:</label>
                            <input type="number" name="precio" step="0.01" min="0" value="0">
                        </div>
                        <div class="form-group">
                            <label>Proveedor:</label>
                            <select name="proveedor_id">
                                <option value="">Sin proveedor</option>
                                <?php while ($prov = pg_fetch_assoc($proveedores)): ?>
                                    <option value="<?= $prov['id'] ?>"><?= htmlspecialchars($prov['nombre']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Agregar producto</button>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <!-- Lista de productos -->
            <div class="card">
                <h3>Lista de productos</h3>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Stock</th>
                                <th>Stock mínimo</th>
                                <th>Precio</th>
                                <th>Proveedor</th>
                                <th>Estado</th>
                                <?php if ($rol === 'admin'): ?>
                                    <th>Acciones</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($p = pg_fetch_assoc($productos)): ?>
                            <tr>
                                <td><?= htmlspecialchars($p['nombre']) ?></td>
                                <td><?= htmlspecialchars($p['categoria']) ?></td>
                                <td class="<?= $p['stock'] <= $p['stock_minimo'] ? 'stock-bajo' : '' ?>"><?= $p['stock'] ?></td>
                                <td><?= $p['stock_minimo'] ?></td>
                                <td>$<?= number_format($p['precio'], 2) ?></td>
                                <td><?= $p['proveedor'] ? htmlspecialchars($p['proveedor']) : '—' ?></td>
                                <td>
                                    <?php if ($p['stock'] <= $p['stock_minimo']): ?>
                                        <span class="badge badge-bajo">Stock bajo</span>
                                    <?php else: ?>
                                        <span class="badge badge-ok">OK</span>
                                    <?php endif; ?>
                                </td>
                                <?php if ($rol === 'admin'): ?>
                                <td style="display:flex; gap:6px;">
                                    <a href="editar_producto.php?id=<?= $p['id'] ?>" class="btn btn-warning">Editar</a>
                                    <a href="productos.php?eliminar=<?= $p['id'] ?>" class="btn btn-danger" onclick="return confirm('¿Eliminar este producto?')">Eliminar</a>
                                </td>
                                <?php endif; ?>
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
