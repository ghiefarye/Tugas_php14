<?php $base = Auth::base(''); ?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Daftar - Portal Akademik Mahasiswa</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
<link rel="stylesheet" href="<?= $base ?>/assets/css/style.css">
</head>
<body class="auth-body">
<div class="auth-wrap">
  <div class="auth-card" style="max-width:600px;">
    <div class="auth-right" style="flex:1; padding:50px 40px;">
      <div class="auth-brand" style="color:#1e40af; margin-bottom:24px;">
        <i class="bi bi-mortarboard-fill" style="color:#1e40af;"></i>
        <div>
          <h1 style="color:#1e40af;">Daftar Akun Mahasiswa</h1>
          <p style="color:#6b7280;">Buat akun untuk mengakses layanan akademik</p>
        </div>
      </div>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <?php if (!empty($success)): ?>
        <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <form method="post" action="<?= $base ?>/register.php">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama_lengkap" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">NIM <span class="text-danger">*</span></label>
            <input type="text" name="nim" class="form-control" required placeholder="Contoh: 2026010001">
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Program Studi <span class="text-danger">*</span></label>
          <input type="text" name="prodi" class="form-control" required placeholder="Contoh: Teknik Informatika">
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Username <span class="text-danger">*</span></label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control">
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Password <span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control" required minlength="6">
            <small class="text-muted">Minimal 6 karakter</small>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
            <input type="password" name="confirm" class="form-control" required minlength="6">
          </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-person-plus-fill"></i> Daftar Sekarang
        </button>
      </form>

      <p class="text-center mt-3" style="font-size:14px; color:#6b7280;">
        Sudah punya akun? <a href="<?= $base ?>/login.php" style="color:#1e40af; font-weight:600;">Login di sini</a>
      </p>
    </div>
  </div>
</div>
</body>
</html>
