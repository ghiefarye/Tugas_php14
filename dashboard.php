<?php $pageTitle = 'Dashboard Mahasiswa'; include __DIR__ . '/../layouts/header.php'; ?>

<div class="welcome-banner welcome-user">
  <div>
    <small style="opacity:.85;">SELAMAT DATANG</small>
    <h3>Halo, <?= htmlspecialchars($me['nama_lengkap']) ?>!</h3>
    <p>
      <?php if (!empty($profil['nim'])): ?>
        NIM <strong><?= htmlspecialchars($profil['nim']) ?></strong>
        <?php if (!empty($profil['prodi'])): ?> &middot; <?= htmlspecialchars($profil['prodi']) ?><?php endif; ?>
      <?php endif; ?>
    </p>
    <p style="margin-top:8px;">Ajukan surat akademik secara online dan pantau status pengajuan Anda di sini.</p>
  </div>
  <div class="welcome-illu"><i class="bi bi-mortarboard-fill"></i></div>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-sm-6">
    <div class="stat-card">
      <div class="stat-icon"><i class="bi bi-envelope-paper-fill"></i></div>
      <div>
        <div class="stat-label">Total Pengajuan</div>
        <div class="stat-value"><?= $stats['total'] ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6">
    <div class="stat-card">
      <div class="stat-icon orange"><i class="bi bi-hourglass-split"></i></div>
      <div>
        <div class="stat-label">Menunggu</div>
        <div class="stat-value"><?= $stats['pending'] ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6">
    <div class="stat-card">
      <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
      <div>
        <div class="stat-label">Disetujui</div>
        <div class="stat-value"><?= $stats['disetujui'] ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6">
    <div class="stat-card">
      <div class="stat-icon red"><i class="bi bi-x-circle-fill"></i></div>
      <div>
        <div class="stat-label">Ditolak</div>
        <div class="stat-value"><?= $stats['ditolak'] ?></div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <i class="bi bi-clock-history"></i> Pengajuan Terbaru Anda
        <a href="<?= $base ?>/user/pengajuan.php" class="float-end" style="font-size:13px; color:#1e40af;">Lihat Semua →</a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead><tr><th>Nomor Surat</th><th>Jenis</th><th>Dibutuhkan</th><th>Status</th></tr></thead>
            <tbody>
              <?php if (empty($recent)): ?>
                <tr><td colspan="4" class="text-center text-muted py-4">
                  Belum ada pengajuan. <a href="<?= $base ?>/user/pengajuan_create.php">Buat sekarang</a>.
                </td></tr>
              <?php else: foreach ($recent as $row): ?>
                <tr>
                  <td><strong><?= htmlspecialchars($row['nomor_surat']) ?></strong></td>
                  <td><small><?= htmlspecialchars($row['jenis_surat']) ?></small></td>
                  <td><small><?= date('d M Y', strtotime($row['tanggal_dibutuhkan'])) ?></small></td>
                  <td>
                    <?php $badge = ['pending'=>'badge-warning','disetujui'=>'badge-success','ditolak'=>'badge-danger']; ?>
                    <span class="badge-pill <?= $badge[$row['status']] ?>"><?= ucfirst($row['status']) ?></span>
                  </td>
                </tr>
              <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card">
      <div class="card-header"><i class="bi bi-lightning-charge-fill"></i> Aksi Cepat</div>
      <div class="card-body">
        <a href="<?= $base ?>/user/pengajuan_create.php" class="btn btn-primary w-100 mb-2">
          <i class="bi bi-plus-circle-fill"></i> Ajukan Surat Baru
        </a>
        <a href="<?= $base ?>/user/pengajuan.php" class="btn btn-light w-100 text-start mb-2">
          <i class="bi bi-list-check text-primary"></i> Daftar Pengajuan Saya
        </a>
        <a href="<?= $base ?>/logout.php" class="btn btn-light w-100 text-start">
          <i class="bi bi-box-arrow-right text-danger"></i> Keluar
        </a>
      </div>
    </div>

    <div class="card mt-3" style="background:#eff6ff; border-color:#60a5fa;">
      <div class="card-body">
        <h6 style="color:#1e40af;"><i class="bi bi-info-circle-fill"></i> Informasi</h6>
        <p style="font-size:13px; color:#374151; margin:0;">
          Pengajuan surat akan diverifikasi oleh Administrator. Status pengajuan dapat dipantau langsung pada halaman daftar pengajuan.
        </p>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
