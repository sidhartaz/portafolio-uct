<?php
require_once '../includes/funciones.php';
require_once '../config/db.php';
requerirLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirigir('proyectos.php');

if (!validarCsrf($_POST['csrf_token'] ?? null)) {
    setFlash('error', 'Token CSRF inválido.');
    redirigir('proyectos.php');
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    setFlash('error', 'ID inválido.');
    redirigir('proyectos.php');
}

$stmt = $conexion->prepare("DELETE FROM proyectos WHERE id = ?");
$stmt->bind_param('i', $id);
$ok = $stmt->execute();
$stmt->close();

setFlash($ok ? 'ok' : 'error',
         $ok ? 'Proyecto eliminado.' : 'Error al eliminar.');
redirigir('proyectos.php');
