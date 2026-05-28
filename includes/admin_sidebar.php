<?php
// Determina el item activo del sidebar
$pagina_actual = basename($_SERVER['PHP_SELF']);
?>
<aside class="admin-sidebar">
  <div class="admin-brand">
    <span class="brand-bracket">&lt;</span>
    <span>Admin</span>
    <span class="brand-bracket">/&gt;</span>
  </div>

  <nav class="admin-nav">
    <a href="dashboard.php"      class="admin-nav-link <?= $pagina_actual === 'dashboard.php' ? 'active' : '' ?>">
      <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
    </a>
    <a href="proyectos.php"      class="admin-nav-link <?= str_contains($pagina_actual, 'proyectos') ? 'active' : '' ?>">
      <i class="bi bi-folder-fill"></i> <span>Proyectos</span>
    </a>
    <a href="habilidades.php"    class="admin-nav-link <?= str_contains($pagina_actual, 'habilidades') ? 'active' : '' ?>">
      <i class="bi bi-bar-chart-fill"></i> <span>Habilidades</span>
    </a>
    <a href="mensajes.php"       class="admin-nav-link <?= $pagina_actual === 'mensajes.php' ? 'active' : '' ?>">
      <i class="bi bi-envelope-fill"></i> <span>Mensajes</span>
    </a>
    <a href="../index.php"       class="admin-nav-link" target="_blank">
      <i class="bi bi-box-arrow-up-right"></i> <span>Ver sitio</span>
    </a>
  </nav>

  <div class="admin-user">
    <div class="admin-user-info">
      <i class="bi bi-person-circle"></i>
      <div>
        <strong><?= e($_SESSION['admin_nombre'] ?? 'Admin') ?></strong>
        <small>@<?= e($_SESSION['admin_user'] ?? '') ?></small>
      </div>
    </div>
    <a href="../pages/logout.php" class="admin-logout">
      <i class="bi bi-box-arrow-right"></i> Cerrar sesión
    </a>
  </div>
</aside>
