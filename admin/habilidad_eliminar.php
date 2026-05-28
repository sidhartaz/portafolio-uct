<?php
require_once '../includes/funciones.php';
require_once '../config/db.php';
requerirLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirigir('habilidades.php');
if (!validarCsrf($_POST['csrf_token'] ?? null)) {
    setFlash('error', 'Token CSRF inválido.');
    redirigir('habilidades.php');
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) { setFlash('error', 'ID inválido.'); redirigir('habilidades.php'); }

$stmt = $conexion->prepare("DELETE FROM habilidades WHERE id = ?");
$stmt->bind_param('i', $id);
$ok = $stmt->execute();
$stmt->close();

setFlash($ok ? 'ok' : 'error',
         $ok ? 'Habilidad eliminada.' : 'Error al eliminar.');
redirigir('habilidades.php');
