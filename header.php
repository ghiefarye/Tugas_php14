<?php
require_once __DIR__ . '/../../app/core/Auth.php';
Auth::start();
$me   = Auth::user();
$role = $me['role'] ?? null;
$base = Auth::base('');

$current = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Portal Akademik Mahasiswa' ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
<link rel="stylesheet" href="<?= $base ?>/assets/css/style.css">
</head>
<body>

<aside class="sidebar">
  <div class="brand">
    <div class="brand-logo"><i class="bi bi-mortarboard-fill"></i></div>
    <div>
      <h5>Portal Akademik</h5>
      <small>Sistem Informasi Mahasiswa</small>
    </div>
  </div>

  <div class="nav-section">Menu Utama</div>

  <?php if ($role === 'admin'): ?>
    <a class="nav-link <?= $current === 'dashboard.php' && strpos($_SERVER['PHP_SELF'], '/admin/') !== false ? 'active' : '' ?>"
       href="<?= $base ?>/admin/dashboard.php">
      <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>
    <a class="nav-link <?= in_array($current, ['users.php','users_create.php','users_edit.php']) ? 'active' : '' ?>"
       href="<?= $base ?>/admin/users.php">
      <i class="bi bi-people-fill"></i> Manajemen Pengguna
    </a>
    <a class="nav-link <?= in_array($current, ['pengajuan.php','pengajuan_detail.php']) ? 'active' : '' ?>"
       href="<?= $base ?>/admin/pengajuan.php">
      <i class="bi bi-envelope-paper-fill"></i> Verifikasi Surat
    </a>
  <?php else: ?>
    <a class="nav-link <?= $current === 'dashboard.php' && strpos($_SERVER['PHP_SELF'], '/user/') !== false ? 'active' : '' ?>"
       href="<?= $base ?>/user/dashboard.php">
      <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>
    <a class="nav-link <?= $current === 'pengajuan.php' || $current === 'pengajuan_detail.php' ? 'active' : '' ?>"
       href="<?= $base ?>/user/pengajuan.php">
      <i class="bi bi-envelope-paper-fill"></i> Pengajuan Surat
    </a>
    <a class="nav-link <?= $current === 'pengajuan_create.php' ? 'active' : '' ?>"
       href="<?= $base ?>/user/pengajuan_create.php">
      <i class="bi bi-plus-circle-fill"></i> Ajukan Surat Baru
    </a>
  <?php endif; ?>

  <div class="nav-section" style="margin-top:24px;">Akun</div>
  <a class="nav-link" href="<?= $base ?>/logout.php">
    <i class="bi bi-box-arrow-right"></i> Keluar
  </a>
</aside>

<div class="main-wrap">
  <header class="topbar">
    <h1 class="topbar-title"><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : '' ?></h1>
    <div class="user-pill">
      <div class="avatar"><?= strtoupper(substr($me['nama_lengkap'] ?? 'U', 0, 1)) ?></div>
      <div class="user-info">
        <div class="name"><?= htmlspecialchars($me['nama_lengkap'] ?? '') ?></div>
        <small><?= $me['role'] === 'admin' ? 'Administrator' : 'Mahasiswa' ?></small>
      </div>
    </div>
  </header>

  <main class="content">
    <?php if (!empty($_SESSION['flash_success'])): ?>
      <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($_SESSION['flash_success']) ?></div>
      <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['flash_error'])): ?>
      <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($_SESSION['flash_error']) ?></div>
      <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>
