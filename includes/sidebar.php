<?php
// =========================================
// INVENTRA - Sidebar reutilizable
// =========================================
// Variables requeridas antes de incluir este archivo:
//   $nombre    → nombre del usuario logueado
//   $iniciales → primeras 2 letras del nombre
//   $rol       → 'admin' | 'almacenista' | 'vendedor'
//   $pagina_activa → nombre del archivo activo para resaltar el menú
?>

<div class="sidebar">
    <div class="sb-logo">
        <div class="sb-logo-text">📦 Inventra</div>
        <div class="sb-logo-sub">Sistema de gestión</div>
    </div>

    <!-- Sección General (todos los roles) -->
    <div class="sb-section">
        <div class="sb-label">General</div>

        <?php
        // El link de Inicio apunta al dashboard según el rol
        $dashboard_link = 'dashboard_admin.php';
        if ($rol === 'almacenista') $dashboard_link = 'dashboard_almacen.php';
        if ($rol === 'vendedor')    $dashboard_link = 'dashboard_vendedor.php';
        ?>

        <a href="../pages/<?= $dashboard_link ?>" class="sb-item <?= ($pagina_activa === 'dashboard') ? 'active' : '' ?>">
            <div class="sb-dot"></div>
            <span class="sb-text">Inicio</span>
        </a>
        <a href="../pages/productos.php" class="sb-item <?= ($pagina_activa === 'productos') ? 'active' : '' ?>">
            <div class="sb-dot"></div>
            <span class="sb-text">Productos</span>
        </a>
        <a href="../pages/movimientos.php" class="sb-item <?= ($pagina_activa === 'movimientos') ? 'active' : '' ?>">
            <div class="sb-dot"></div>
            <span class="sb-text"><?= ($rol === 'vendedor') ? 'Registrar venta' : 'Movimientos' ?></span>
        </a>
    </div>

    <!-- Sección Admin (solo para administradores) -->
    <?php if ($rol === 'admin'): ?>
    <div class="sb-section" style="margin-top:14px;">
        <div class="sb-label">Administración</div>
        <a href="../pages/proveedores.php" class="sb-item <?= ($pagina_activa === 'proveedores') ? 'active' : '' ?>">
            <div class="sb-dot"></div>
            <span class="sb-text">Proveedores</span>
        </a>
        <a href="../pages/usuarios.php" class="sb-item <?= ($pagina_activa === 'usuarios') ? 'active' : '' ?>">
            <div class="sb-dot"></div>
            <span class="sb-text">Usuarios</span>
        </a>
        <a href="../pages/reportes.php" class="sb-item <?= ($pagina_activa === 'reportes') ? 'active' : '' ?>">
            <div class="sb-dot"></div>
            <span class="sb-text">Reportes</span>
        </a>
    </div>
    <?php endif; ?>

    <!-- Footer del sidebar con datos del usuario -->
    <div class="sb-footer">
        <div class="sb-avatar"><?= $iniciales ?></div>
        <div>
            <div class="sb-user-name"><?= $nombre ?></div>
            <div class="sb-user-role"><?= ucfirst($rol) ?></div>
        </div>
    </div>
</div>
