<?php $base = Auth::base(''); ?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login - Portal Akademik Mahasiswa</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
<link rel="stylesheet" href="<?= $base ?>/assets/css/style.css">
</head>
<body class="auth-body">
<div class="auth-wrap">
  <div class="auth-card">
    <div class="auth-left">
      <div class="auth-brand">
        <i class="bi bi-mortarboard-fill"></i>
        <div>
          <h1>Portal Akademik</h1>
          <p>Sistem Informasi Mahasiswa</p>
        </div>
      </div>
      <div class="auth-feature">
        <i class="bi bi-shield-lock-fill"></i>
        <div>
          <strong>Aman & Terstandar</strong>
          <p>Autentikasi terenkripsi dengan password hashing.</p>
        </div>
      </div>
      <div class="auth-feature">
        <i class="bi bi-envelope-paper-fill"></i>
        <div>
          <strong>Layanan Administrasi</strong>
          <p>Ajukan surat akademik secara online dengan mudah.</p>
        </div>
      </div>
      <div class="auth-feature">
        <i class="bi bi-people-fill"></i>
        <div>
          <strong>Multi Role</strong>
          <p>Admin & Mahasiswa dalam satu platform terintegrasi.</p>
        </div>
      </div>
    </div>

    <div class="auth-right">
      <h3>Masuk ke Sistem</h3>
      <p class="auth-sub">Silakan masukkan kredensial Anda</p>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <?php if (!empty($flash)): ?>
        <div class="alert alert-warning"><i class="bi bi-info-circle-fill"></i> <?= htmlspecialchars($flash) ?></div>
      <?php endif; ?>

      <form method="post" action="<?= $base ?>/login.php">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
            <input type="text" name="username" class="form-control form-control-lg" required autofocus placeholder="Masukkan username">
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" name="password" class="form-control form-control-lg" required placeholder="Masukkan password">
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-lg w-100 mt-2">
          <i class="bi bi-box-arrow-in-right"></i> Masuk
        </button>
      </form>

      <p class="text-center mt-3" style="font-size:14px; color:#6b7280;">
        Belum punya akun? <a href="<?= $base ?>/register.php" style="color:#1e40af; font-weight:600;">Daftar di sini</a>
      </p>

      <div class="demo-creds">
        <strong><i class="bi bi-info-circle-fill"></i> Akun Demo:</strong><br>
        Admin: <code>admin</code> / <code>admin123</code><br>
        Mahasiswa: <code>andi</code> / <code>user123</code>
      </div>
    </div>
  </div>
</div>
</body>
</html>
