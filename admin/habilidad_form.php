<?php
$titulo_pagina = 'Editar habilidad';
require_once '../includes/admin_header.php';

$id = (int)($_GET['id'] ?? 0);
$habilidad = [
    'id' => 0, 'nombre' => '', 'icono' => 'bi-code-slash',
    'porcentaje' => 50, 'nivel' => 'intermedio',
    'categoria' => 'lenguaje', 'orden' => 0, 'activo' => 1
];

if ($id > 0) {
    $stmt = $conexion->prepare("SELECT * FROM habilidades WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $fila = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($fila) $habilidad = $fila;
    else { setFlash('error', 'Habilidad no encontrada.'); redirigir('habilidades.php'); }
}

$es_edicion = $habilidad['id'] > 0;
?>

<header class="admin-page-header">
  <div>
    <h1>
      <i class="bi bi-<?= $es_edicion ? 'pencil-square' : 'plus-circle' ?>"></i>
      <?= $es_edicion ? 'Editar habilidad' : 'Nueva habilidad' ?>
    </h1>
  </div>
  <a href="habilidades.php" class="btn-admin btn-admin-outline">
    <i class="bi bi-arrow-left"></i> Volver
  </a>
</header>

<div class="admin-card">
  <form method="POST" action="habilidad_guardar.php" class="admin-form">
    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
    <input type="hidden" name="id" value="<?= (int)$habilidad['id'] ?>">

    <div class="row g-3">

      <div class="col-md-8">
        <label class="form-label-custom">Nombre *</label>
        <input type="text" name="nombre" class="form-input-custom"
               value="<?= e($habilidad['nombre']) ?>" required maxlength="60">
      </div>

      <div class="col-md-4">
        <label class="form-label-custom">Icono (Bootstrap Icons)</label>
        <input type="text" name="icono" class="form-input-custom"
               value="<?= e($habilidad['icono']) ?>" placeholder="bi-filetype-html">
      </div>

      <div class="col-md-4">
        <label class="form-label-custom">Categoría *</label>
        <select name="categoria" class="form-input-custom" required>
          <?php foreach (['lenguaje','herramienta','blanda'] as $cat): ?>
            <option value="<?= $cat ?>" <?= $habilidad['categoria'] === $cat ? 'selected' : '' ?>>
              <?= ucfirst($cat) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label-custom">Nivel *</label>
        <select name="nivel" class="form-input-custom" required>
          <?php foreach (['basico','intermedio','avanzado'] as $niv): ?>
            <option value="<?= $niv ?>" <?= $habilidad['nivel'] === $niv ? 'selected' : '' ?>>
              <?= ucfirst($niv) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label-custom">Porcentaje (0-100) *</label>
        <input type="number" name="porcentaje" class="form-input-custom"
               value="<?= (int)$habilidad['porcentaje'] ?>"
               min="0" max="100" required>
      </div>

      <div class="col-md-4">
        <label class="form-label-custom">Orden (menor = primero)</label>
        <input type="number" name="orden" class="form-input-custom"
               value="<?= (int)$habilidad['orden'] ?>" min="0">
      </div>

      <div class="col-md-4 d-flex align-items-end">
        <label class="form-check-label">
          <input type="checkbox" name="activo" value="1"
                 <?= $habilidad['activo'] ? 'checked' : '' ?>>
          Mostrar en el portafolio
        </label>
      </div>

      <div class="col-12 d-flex gap-2 mt-3">
        <button type="submit" class="btn-admin btn-admin-primary">
          <i class="bi bi-check-lg"></i> <?= $es_edicion ? 'Guardar' : 'Crear' ?>
        </button>
        <a href="habilidades.php" class="btn-admin btn-admin-outline">Cancelar</a>
      </div>

    </div>
  </form>
</div>

<?php require_once '../includes/admin_footer.php'; ?>
