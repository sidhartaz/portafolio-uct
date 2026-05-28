<?php
require_once '../includes/funciones.php';
require_once '../config/db.php';
requerirLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirigir('habilidades.php');
if (!validarCsrf($_POST['csrf_token'] ?? null)) {
    setFlash('error', 'Token CSRF inválido.');
    redirigir('habilidades.php');
}

$id         = (int)($_POST['id'] ?? 0);
$nombre     = trim($_POST['nombre'] ?? '');
$icono      = trim($_POST['icono'] ?? 'bi-code-slash');
$porcentaje = (int)($_POST['porcentaje'] ?? 50);
$nivel      = trim($_POST['nivel'] ?? 'intermedio');
$categoria  = trim($_POST['categoria'] ?? 'lenguaje');
$orden      = (int)($_POST['orden'] ?? 0);
$activo     = isset($_POST['activo']) ? 1 : 0;

$porcentaje = max(0, min(100, $porcentaje));

$errores = [];
if (mb_strlen($nombre) < 1) $errores[] = 'Nombre requerido.';
if (!in_array($nivel, ['basico','intermedio','avanzado'])) $errores[] = 'Nivel inválido.';
if (!in_array($categoria, ['lenguaje','herramienta','blanda'])) $errores[] = 'Categoría inválida.';

if ($errores) {
    setFlash('error', implode(' ', $errores));
    redirigir($id ? "habilidad_form.php?id=$id" : 'habilidad_form.php');
}

if ($id > 0) {
    $stmt = $conexion->prepare(
        "UPDATE habilidades
         SET nombre=?, icono=?, porcentaje=?, nivel=?, categoria=?, orden=?, activo=?
         WHERE id = ?"
    );
    $stmt->bind_param('ssissiii',
        $nombre, $icono, $porcentaje, $nivel, $categoria, $orden, $activo, $id);
    $ok = $stmt->execute();
    $stmt->close();
    setFlash($ok ? 'ok' : 'error',
             $ok ? 'Habilidad actualizada.' : 'Error al actualizar.');
} else {
    $stmt = $conexion->prepare(
        "INSERT INTO habilidades (nombre, icono, porcentaje, nivel, categoria, orden, activo)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param('ssissii',
        $nombre, $icono, $porcentaje, $nivel, $categoria, $orden, $activo);
    $ok = $stmt->execute();
    $stmt->close();
    setFlash($ok ? 'ok' : 'error',
             $ok ? 'Habilidad creada.' : 'Error al crear.');
}

redirigir('habilidades.php');
