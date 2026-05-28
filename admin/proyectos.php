<?php
$titulo_pagina = 'Proyectos';
require_once '../includes/admin_header.php';

$proyectos = $conexion->query(
    "SELECT * FROM proyectos ORDER BY destacado DESC, id DESC"
)->fetch_all(MYSQLI_ASSOC);
?>

<header class="admin-page-header">
  <div>
    <h1><i class="bi bi-folder-fill"></i> Proyectos</h1>
    <p>Administra los proyectos que se muestran en tu portafolio.</p>
  </div>
  <a href="proyecto_form.php" class="btn-admin btn-admin-primary">
    <i class="bi bi-plus-lg"></i> Nuevo proyecto
  </a>
</header>

<div class="admin-card">

  <?php if (!$proyectos): ?>
    <p class="text-muted text-center py-4">No hay proyectos aún. ¡Crea el primero!</p>
  <?php else: ?>
    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Título</th>
            <th>Categoría</th>
            <th>Tags</th>
            <th>Destacado</th>
            <th>Estado</th>
            <th class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($proyectos as $p): ?>
            <tr>
              <td>#<?= $p['id'] ?></td>
              <td>
                <i class="bi <?= e($p['icono']) ?>"></i>
                <strong><?= e($p['titulo']) ?></strong>
              </td>
              <td><span class="badge-cat"><?= e($p['categoria']) ?></span></td>
              <td><small><?= e($p['tags']) ?></small></td>
              <td class="text-center">
                <?= $p['destacado']
                    ? '<i class="bi bi-star-fill text-warning"></i>'
                    : '<i class="bi bi-star text-muted"></i>' ?>
              </td>
              <td>
                <?= $p['activo']
                    ? '<span class="badge-status badge-leido">Activo</span>'
                    : '<span class="badge-status badge-nuevo">Oculto</span>' ?>
              </td>
              <td class="text-end">
                <div class="d-flex align-items-center justify-content-end gap-1">
                  <a href="proyecto_form.php?id=<?= $p['id'] ?>"
                     class="btn-admin btn-admin-sm btn-admin-outline">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form method="POST" action="proyecto_eliminar.php" class="d-inline"
                        onsubmit="return confirm('¿Eliminar el proyecto «<?= e($p['titulo']) ?>»?');">
                    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
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
