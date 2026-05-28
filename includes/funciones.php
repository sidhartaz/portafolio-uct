<?php
/**
 * Funciones auxiliares del proyecto
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Escapa HTML para evitar XSS al imprimir en vista */
function e(?string $valor): string {
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
}

/** Redirige a una URL y termina ejecución */
function redirigir(string $url): void {
    header("Location: $url");
    exit;
}

/** Verifica si hay sesión activa de admin */
function estaLogueado(): bool {
    return isset($_SESSION['admin_id']);
}

/** Protege rutas administrativas */
function requerirLogin(): void {
    if (!estaLogueado()) {
        redirigir('../pages/login.php');
    }
}

/** Genera/recupera token CSRF para formularios */
function csrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Valida token CSRF enviado por formulario */
function validarCsrf(?string $token): bool {
    return !empty($token)
        && !empty($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

/** Mensajes flash entre redirecciones */
function setFlash(string $tipo, string $mensaje): void {
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
}

function getFlash(): ?array {
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
