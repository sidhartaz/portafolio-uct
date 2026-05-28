<?php
$titulo_pagina = 'Editar proyecto';
require_once '../includes/admin_header.php';

$id = (int)($_GET['id'] ?? 0);
$proyecto = [
    'id' => 0, 'titulo' => '', 'descripcion' => '',
    'categoria' => 'web', 'tags' => '', 'icono' => 'bi-folder',
    'url_demo' => '', 'url_github' => '', 'destacado' => 0, 'activo' => 1
];

if ($id > 0) {
    $stmt = $conexion->prepare("SELECT * FROM proyectos WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $fila = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($fila) $proyecto = $fila;
    else {
        setFlash('error', 'Proyecto no encontrado.');
        redirigir('proyectos.php');
    }
}

$es_edicion = $proyecto['id'] > 0;
?>

<header class="admin-page-header">
  <div>
    <h1>
      <i class="bi bi-<?= $es_edicion ? 'pencil-square' : 'plus-circle' ?>"></i>
      <?= $es_edicion ? 'Editar proyecto' : 'Nuevo proyecto' ?>
    </h1>
    <p><?= $es_edicion ? 'Modifica los datos del proyecto.' : 'Completa los datos del nuevo proyecto.' ?></p>
  </div>
  <a href="proyectos.php" class="btn-admin btn-admin-outline">
    <i class="bi bi-arrow-left"></i> Volver
  </a>
</header>

<div class="admin-card">
  <form method="POST" action="proyecto_guardar.php" class="admin-form">
    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
    <input type="hidden" name="id" value="<?= (int)$proyecto['id'] ?>">

    <div class="row g-3">

      <div class="col-md-8">
        <label class="form-label-custom">Título *</label>
        <input type="text" name="titulo" class="form-input-custom"
               value="<?= e($proyecto['titulo']) ?>"
               required maxlength="120">
      </div>

      <div class="col-md-4">
        <label class="form-label-custom">Categoría *</label>
        <select name="categoria" class="form-input-custom" required>
          <?php foreach (['web','python','linux','otro'] as $cat): ?>
            <option value="<?= $cat ?>" <?= $proyecto['categoria'] === $cat ? 'selected' : '' ?>>
              <?= ucfirst($cat) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-12">
        <label class="form-label-custom">Descripción *</label>
        <textarea name="descripcion" class="form-input-custom" rows="4"
                  required minlength="20"><?= e($proyecto['descripcion']) ?></textarea>
      </div>

      <div class="col-md-6">
        <label class="form-label-custom">Tags (separados por coma)</label>
        <input type="text" name="tags" class="form-input-custom"
               value="<?= e($proyecto['tags']) ?>"
               placeholder="HTML, CSS, JavaScript">
      </div>

      <div class="col-md-6">
        <label class="form-label-custom">Icono (clase Bootstrap Icons)</label>
        <input type="text" name="icono" class="form-input-custom"
               value="<?= e($proyecto['icono']) ?>"
               placeholder="bi-shop">
        <small class="text-muted">Lista en <a href="https://icons.getbootstrap.com" target="_blank">icons.getbootstrap.com</a></small>
      </div>

      <div class="col-md-6">
        <label class="form-label-custom">URL Demo</label>
        <input type="url" name="url_demo" class="form-input-custom"
               value="<?= e($proyecto['url_demo']) ?>"
               placeholder="https://...">
      </div>

      <div class="col-md-6">
        <label class="form-label-custom">URL GitHub</label>
        <input type="url" name="url_github" class="form-input-custom"
               value="<?= e($proyecto['url_github']) ?>"
               placeholder="https://github.com/...">
      </div>

      <div class="col-md-6">
        <label class="form-check-label">
          <input type="checkbox" name="destacado" value="1"
                 <?= $proyecto['destacado'] ? 'checked' : '' ?>>
          ⭐ Marcar como destacado
        </label>
      </div>

      <div class="col-md-6">
        <label class="form-check-label">
          <input type="checkbox" name="activo" value="1"
                 <?= $proyecto['activo'] ? 'checked' : '' ?>>
          Mostrar en el portafolio
        </label>
      </div>

      <div class="col-12 d-flex gap-2 mt-3">
        <button type="submit" class="btn-admin btn-admin-primary">
          <i class="bi bi-check-lg"></i> <?= $es_edicion ? 'Guardar cambios' : 'Crear proyecto' ?>
        </button>
        <a href="proyectos.php" class="btn-admin btn-admin-outline">Cancelar</a>
      </div>

    </div>
  </form>
</div>

<?php require_once '../includes/admin_footer.php'; ?>
