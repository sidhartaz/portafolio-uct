<?php
/**
 * Conexión a la base de datos MySQL
 * Portafolio UCT — Carlos Sepúlveda
 *
 * IMPORTANTE: Ajustar las credenciales antes de subir a teclab.
 */

// Evitar mostrar errores sensibles en producción
ini_set('display_errors', 0);
error_reporting(E_ALL);


define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'portafolio_uct');
define('DB_CHARSET', 'utf8mb4');

// ===== CONEXIÓN =====
$conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conexion->connect_errno) {
    // En producción: log + mensaje genérico
    error_log("Error de conexión BD: " . $conexion->connect_error);
    die("No se pudo conectar al servidor de base de datos.");
}

$conexion->set_charset(DB_CHARSET);
