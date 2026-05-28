<?php
$titulo_pagina = 'Mensajes';
require_once '../includes/admin_header.php';

// Marcar como leído si se solicita
if (isset($_GET['leer'])) {
    $id_leer = (int)$_GET['leer'];
    $stmt = $conexion->prepare("UPDATE mensajes_contacto SET leido = 1 WHERE id = ?");
    $stmt->bind_param('i', $id_leer);
    $stmt->execute();
    $stmt->close();
}

$mensajes = $conexion->query(
    "SELECT * FROM mensajes_contacto ORDER BY recibido_en DESC"
)->fetch_all(MYSQLI_ASSOC);
?>

<header class="admin-page-header">
  <div>
    <h1><i class="bi bi-envelope-fill"></i> Bandeja de mensajes</h1>
    <p>Mensajes recibidos a través del formulario de contacto.</p>
  </div>
</header>

<div class="admin-card">

  <?php if (!$mensajes): ?>
    <p class="text-muted text-center py-4">No hay mensajes aún.</p>
  <?php else: ?>
    <div class="accordion" id="mensajesAcc">
      <?php foreach ($mensajes as $i => $m): ?>
        <div class="accordion-item mensaje-item <?= !$m['leido'] ? 'no-leido' : '' ?>">
          <h2 class="accordion-header">
            <a class="accordion-button collapsed" data-bs-toggle="collapse"
               href="#m<?= $m['id'] ?>" role="button"
               onclick="if(!<?= $m['leido'] ?>) location.href='?leer=<?= $m['id'] ?>#m<?= $m['id'] ?>';">
              <div class="mensaje-resumen">
                <strong><?= e($m['nombre']) ?></strong>
                <span class="mensaje-asunto"><?= e($m['asunto'] ?: '(sin asunto)') ?></span>
                <small class="mensaje-fecha"><?= e(date('d/m/Y H:i', strtotime($m['recibido_en']))) ?></small>
                <?php if (!$m['leido']): ?>
                  <span class="badge-status badge-nuevo ms-2">Nuevo</span>
                <?php endif; ?>
              </div>
            </a>
          </h2>
          <div id="m<?= $m['id'] ?>" class="accordion-collapse collapse" data-bs-parent="#mensajesAcc">
            <div class="accordion-body">
              <p><strong>De:</strong> <?= e($m['nombre']) ?>
                 &lt;<a href="mailto:<?= e($m['email']) ?>"><?= e($m['email']) ?></a>&gt;</p>
              <hr>
              <p style="white-space: pre-wrap;"><?= e($m['mensaje']) ?></p>
              <div class="mt-3">
                <a href="mailto:<?= e($m['email']) ?>?subject=Re:%20<?= e(urlencode($m['asunto'] ?: 'Tu mensaje')) ?>"
                   class="btn-admin btn-admin-primary btn-admin-sm">
                  <i class="bi bi-reply-fill"></i> Responder
                </a>
                <form method="POST" action="mensaje_eliminar.php" class="d-inline"
                      onsubmit="return confirm('¿Eliminar este mensaje?');">
                  <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                  <input type="hidden" name="id" value="<?= $m['id'] ?>">
                  <button class="btn-admin btn-admin-danger btn-admin-sm">
                    <i class="bi bi-trash"></i> Eliminar
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php require_once '../includes/admin_footer.php'; ?>
