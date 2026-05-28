<?php
require_once __DIR__ . '/funciones.php';
require_once __DIR__ . '/../config/db.php';
requerirLogin();
?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($titulo_pagina) ? e($titulo_pagina) . ' | ' : '' ?>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body class="admin-body">

<div class="admin-layout">

  <?php include __DIR__ . '/admin_sidebar.php'; ?>

  <main class="admin-main">

    <?php $flash = getFlash(); if ($flash): ?>
      <div class="alert alert-<?= $flash['tipo'] === 'ok' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
        <i class="bi bi-<?= $flash['tipo'] === 'ok' ? 'check-circle-fill' : 'exclamation-triangle-fill' ?>"></i>
        <?= e($flash['mensaje']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>
