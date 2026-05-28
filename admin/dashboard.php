<?php
$titulo_pagina = 'Dashboard';
require_once '../includes/admin_header.php';

// Estadísticas
$total_proyectos    = $conexion->query("SELECT COUNT(*) AS n FROM proyectos")->fetch_assoc()['n'];
$total_habilidades  = $conexion->query("SELECT COUNT(*) AS n FROM habilidades")->fetch_assoc()['n'];
$total_mensajes     = $conexion->query("SELECT COUNT(*) AS n FROM mensajes_contacto")->fetch_assoc()['n'];
$mensajes_no_leidos = $conexion->query("SELECT COUNT(*) AS n FROM mensajes_contacto WHERE leido = 0")->fetch_assoc()['n'];

$ultimos_mensajes = $conexion->query(
    "SELECT id, nombre, email, asunto, recibido_en, leido
     FROM mensajes_contacto
     ORDER BY recibido_en DESC LIMIT 5"
)->fetch_all(MYSQLI_ASSOC);
?>

<header class="admin-page-header">
  <div>
    <h1>Panel de Control</h1>
    <p>Bienvenido, <?= e($_SESSION['admin_nombre']) ?>. Aquí tienes un resumen de tu portafolio.</p>
  </div>
</header>

<div class="row g-3 mb-4">

  <div class="col-md-6 col-lg-3">
    <div class="stat-card stat-card-1">
      <div class="stat-card-icon"><i class="bi bi-folder-fill"></i></div>
      <div class="stat-card-info">
        <span class="stat-card-num"><?= $total_proyectos ?></span>
        <span class="stat-card-label">Proyectos</span>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-lg-3">
    <div class="stat-card stat-card-2">
      <div class="stat-card-icon"><i class="bi bi-bar-chart-fill"></i></div>
      <div class="stat-card-info">
        <span class="stat-card-num"><?= $total_habilidades ?></span>
        <span class="stat-card-label">Habilidades</span>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-lg-3">
    <div class="stat-card stat-card-3">
      <div class="stat-card-icon"><i class="bi bi-envelope-fill"></i></div>
      <div class="stat-card-info">
        <span class="stat-card-num"><?= $total_mensajes ?></span>
        <span class="stat-card-label">Mensajes totales</span>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-lg-3">
    <div class="stat-card stat-card-4">
      <div class="stat-card-icon"><i class="bi bi-envelope-exclamation-fill"></i></div>
      <div class="stat-card-info">
        <span class="stat-card-num"><?= $mensajes_no_leidos ?></span>
        <span class="stat-card-label">Sin leer</span>
      </div>
    </div>
  </div>

</div>

<div class="admin-card">
  <div class="admin-card-header">
    <h2><i class="bi bi-clock-history"></i> Últimos mensajes recibidos</h2>
    <a href="mensajes.php" class="btn-admin btn-admin-outline">Ver todos</a>
  </div>

  <?php if (!$ultimos_mensajes): ?>
    <p class="text-muted text-center py-4">Aún no hay mensajes en tu bandeja.</p>
  <?php else: ?>
    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>De</th>
            <th>Asunto</th>
            <th>Fecha</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($ultimos_mensajes as $m): ?>
            <tr class="<?= !$m['leido'] ? 'fw-bold' : '' ?>">
              <td>
                <strong><?= e($m['nombre']) ?></strong><br>
                <small class="text-muted"><?= e($m['email']) ?></small>
              </td>
              <td><?= e($m['asunto'] ?: '(sin asunto)') ?></td>
              <td><small><?= e(date('d/m/Y H:i', strtotime($m['recibido_en']))) ?></small></td>
              <td>
                <?php if ($m['leido']): ?>
                  <span class="badge-status badge-leido">Leído</span>
                <?php else: ?>
                  <span class="badge-status badge-nuevo">Nuevo</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require_once '../includes/admin_footer.php'; ?>
