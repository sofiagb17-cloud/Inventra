<?php
// Variables requeridas: $nombre, $iniciales, $rol, $pagina_activa
?>

<!-- Botón hamburguesa (solo móvil) -->
<button class="hamburger" id="btnMenu" onclick="toggleSidebar()">☰</button>

<!-- Fondo oscuro al abrir el menú -->
<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

<div class="sidebar" id="sidebar">

    <!-- Botón cerrar dentro del menú -->
    <button class="sidebar-close" onclick="toggleSidebar()">✕</button>

    <div class="sb-logo">
        <div class="sb-logo-text">📦 Inventra</div>
        <div class="sb-logo-sub">Sistema de gestión</div>
    </div>

    <div class="sb-section">
        <div class="sb-label">General</div>
        <?php
        $dashboard_link = 'dashboard_admin.php';
        if ($rol === 'almacenista') $dashboard_link = 'dashboard_almacen.php';
        if ($rol === 'vendedor')    $dashboard_link = 'dashboard_vendedor.php';
        ?>
        <a href="../pages/<?= $dashboard_link ?>" class="sb-item <?= ($pagina_activa === 'dashboard') ? 'active' : '' ?>">
            <div class="sb-dot"></div><span class="sb-text">Inicio</span>
        </a>
        <a href="../pages/productos.php" class="sb-item <?= ($pagina_activa === 'productos') ? 'active' : '' ?>">
            <div class="sb-dot"></div><span class="sb-text">Productos</span>
        </a>
        <a href="../pages/movimientos.php" class="sb-item <?= ($pagina_activa === 'movimientos') ? 'active' : '' ?>">
            <div class="sb-dot"></div><span class="sb-text"><?= ($rol === 'vendedor') ? 'Registrar venta' : 'Movimientos' ?></span>
        </a>
    </div>

    <?php if ($rol === 'admin'): ?>
    <div class="sb-section" style="margin-top:14px;">
        <div class="sb-label">Administración</div>
        <a href="../pages/proveedores.php" class="sb-item <?= ($pagina_activa === 'proveedores') ? 'active' : '' ?>">
            <div class="sb-dot"></div><span class="sb-text">Proveedores</span>
        </a>
        <a href="../pages/usuarios.php" class="sb-item <?= ($pagina_activa === 'usuarios') ? 'active' : '' ?>">
            <div class="sb-dot"></div><span class="sb-text">Usuarios</span>
        </a>
        <a href="../pages/reportes.php" class="sb-item <?= ($pagina_activa === 'reportes') ? 'active' : '' ?>">
            <div class="sb-dot"></div><span class="sb-text">Reportes</span>
        </a>
    </div>
    <?php endif; ?>

    <div class="sb-footer">
        <div class="sb-avatar"><?= $iniciales ?></div>
        <div>
            <div class="sb-user-name"><?= $nombre ?></div>
            <div class="sb-user-role"><?= ucfirst($rol) ?></div>
        </div>
    </div>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('active');
}
</script>