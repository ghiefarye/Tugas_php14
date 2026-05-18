<?php $pageTitle = 'Pengajuan Surat Saya'; include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
  <h4><i class="bi bi-envelope-paper-fill"></i> Daftar Pengajuan Surat Saya</h4>
  <a href="<?= $base ?>/user/pengajuan_create.php" class="btn btn-primary">
    <i class="bi bi-plus-circle-fill"></i> Ajukan Surat Baru
  </a>
</div>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead>
          <tr><th>Nomor Surat</th><th>Jenis Surat</th><th>Tgl Dibutuhkan</th><th>Diajukan</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          <?php if (empty($items)): ?>
            <tr><td colspan="6" class="text-center text-muted py-4">
              Anda belum pernah mengajukan surat.
              <a href="<?= $base ?>/user/pengajuan_create.php">Buat sekarang →</a>
            </td></tr>
          <?php else: foreach ($items as $row): ?>
            <tr>
              <td><strong><?= htmlspecialchars($row['nomor_surat']) ?></strong></td>
              <td><?= htmlspecialchars($row['jenis_surat']) ?></td>
              <td><small><?= date('d/m/Y', strtotime($row['tanggal_dibutuhkan'])) ?></small></td>
              <td><small><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></small></td>
              <td>
                <?php $badge = ['pending'=>'badge-warning','disetujui'=>'badge-success','ditolak'=>'badge-danger']; ?>
                <span class="badge-pill <?= $badge[$row['status']] ?>"><?= ucfirst($row['status']) ?></span>
              </td>
              <td>
                <a href="<?= $base ?>/user/pengajuan_detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">
                  <i class="bi bi-eye-fill"></i> Detail
                </a>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
