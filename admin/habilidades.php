<?php
$titulo_pagina = 'Habilidades';
require_once '../includes/admin_header.php';

$habilidades = $conexion->query(
    "SELECT * FROM habilidades ORDER BY categoria, orden, id"
)->fetch_all(MYSQLI_ASSOC);
?>

<header class="admin-page-header">
  <div>
    <h1><i class="bi bi-bar-chart-fill"></i> Habilidades</h1>
    <p>Administra las habilidades y tecnologías de tu portafolio.</p>
  </div>
  <a href="habilidad_form.php" class="btn-admin btn-admin-primary">
    <i class="bi bi-plus-lg"></i> Nueva habilidad
  </a>
</header>

<div class="admin-card">

  <?php if (!$habilidades): ?>
    <p class="text-muted text-center py-4">No hay habilidades aún.</p>
  <?php else: ?>
    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Habilidad</th>
            <th>Categoría</th>
            <th>Nivel</th>
            <th>Porcentaje</th>
            <th>Estado</th>
            <th class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($habilidades as $h): ?>
            <tr>
              <td>#<?= $h['id'] ?></td>
              <td>
                <i class="bi <?= e($h['icono']) ?>"></i>
                <strong><?= e($h['nombre']) ?></strong>
              </td>
              <td><span class="badge-cat"><?= e($h['categoria']) ?></span></td>
              <td><span class="tech-level nivel-<?= e($h['nivel']) ?>"><?= ucfirst(e($h['nivel'])) ?></span></td>
              <td>
                <div class="skill-track" style="width: 120px;">
                  <div class="skill-fill" style="width: <?= (int)$h['porcentaje'] ?>%;"></div>
                </div>
                <small><?= (int)$h['porcentaje'] ?>%</small>
              </td>
              <td>
                <?= $h['activo']
                    ? '<span class="badge-status badge-leido">Activa</span>'
                    : '<span class="badge-status badge-nuevo">Oculta</span>' ?>
              </td>
              <td class="text-end">
                <div class="d-flex align-items-center justify-content-end gap-1">
                  <a href="habilidad_form.php?id=<?= $h['id'] ?>"
                     class="btn-admin btn-admin-sm btn-admin-outline">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form method="POST" action="habilidad_eliminar.php" class="d-inline"
                        onsubmit="return confirm('¿Eliminar «<?= e($h['nombre']) ?>»?');">
                    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                    <input type="hidden" name="id" value="<?= $h['id'] ?>">
                    <button type="submit" class="btn-admin btn-admin-sm btn-admin-danger">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require_once '../includes/admin_footer.php'; ?>
