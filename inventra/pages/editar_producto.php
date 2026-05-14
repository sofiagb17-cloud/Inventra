<?php
// =========================================
// INVENTRA — Editar Producto
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
$pagina_activa = 'productos';

$id       = (int) $_GET['id'];
$res      = pg_query($conn, "SELECT * FROM productos WHERE id = $id");
$producto = pg_fetch_assoc($res);

if (!$producto) {
    header('Location: productos.php');
    exit;
}

// Guardar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_p     = trim(pg_escape_string($conn, $_POST['nombre']));
    $categoria    = trim(pg_escape_string($conn, $_POST['categoria']));
    $stock        = (int) $_POST['stock'];
    $stock_minimo = (int) $_POST['stock_minimo'];
    $precio       = (float) $_POST['precio'];
    $proveedor_id = !empty($_POST['proveedor_id']) ? (int) $_POST['proveedor_id'] : 'NULL';

    pg_query($conn, "UPDATE productos SET 
                     nombre = '$nombre_p', 
                     categoria = '$categoria', 
                     stock = $stock, 
                     stock_minimo = $stock_minimo, 
                     precio = $precio, 
                     proveedor_id = $proveedor_id 
                     WHERE id = $id");
    header('Location: productos.php?ok=editado');
    exit;
}

$proveedores = pg_query($conn, "SELECT id, nombre FROM proveedores ORDER BY nombre ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto — INVENTRA</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="layout">

    <?php include('../includes/sidebar.php'); ?>

    <div class="main">
        <div class="topbar">
            <div>
                <div class="topbar-title">Editar Producto</div>
                <div class="topbar-sub">Modifica la información del producto</div>
            </div>
            <a href="../auth/logout.php" class="btn-logout">Cerrar sesión</a>
        </div>

        <div class="main-content">

            <div class="card">
                <h3>✎ Información del producto</h3>
                <form action="editar_producto.php?id=<?= $id ?>" method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nombre:</label>
                            <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Categoría:</label>
                            <input type="text" name="categoria" value="<?= htmlspecialchars($producto['categoria']) ?>">
                        </div>
                        <div class="form-group">
                            <label>Stock actual:</label>
                            <input type="number" name="stock" min="0" value="<?= $producto['stock'] ?>">
                        </div>
                        <div class="form-group">
                            <label>Stock mínimo:</label>
                            <input type="number" name="stock_minimo" min="0" value="<?= $producto['stock_minimo'] ?>">
                        </div>
                        <div class="form-group">
                            <label>Precio:</label>
                            <input type="number" name="precio" step="0.01" min="0" value="<?= $producto['precio'] ?>">
                        </div>
                        <div class="form-group">
                            <label>Proveedor:</label>
                            <select name="proveedor_id">
                                <option value="">Sin proveedor</option>
                                <?php while ($prov = pg_fetch_assoc($proveedores)): ?>
                                    <option value="<?= $prov['id'] ?>" <?= $prov['id'] == $producto['proveedor_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($prov['nombre']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        <a href="productos.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>

        </div>

        <?php include('../includes/footer.php'); ?>
    </div>

</div>
</body>
</html>
