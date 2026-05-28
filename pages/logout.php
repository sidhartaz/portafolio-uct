<?php
require_once '../includes/funciones.php';
$_SESSION = [];
session_destroy();
redirigir('../index.php');
