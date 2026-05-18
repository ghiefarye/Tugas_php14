<?php $pageTitle = 'Detail Pengajuan'; include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
  <h4><i class="bi bi-envelope-paper-fill"></i> Detail Pengajuan Surat</h4>
  <a href="<?= $base ?>/user/pengajuan.php" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left"></i> Kembali
  </a>
</div>

<div class="card">
  <div class="card-header">
    <i class="bi bi-info-circle-fill"></i> Informasi Pengajuan
    <span class="float-end">
      <?php $badge = ['pending'=>'badge-warning','disetujui'=>'badge-success','ditolak'=>'badge-danger']; ?>
      <span class="badge-pill <?= $badge[$data['status']] ?>"><?= ucfirst($data['status']) ?></span>
    </span>
  </div>
  <div class="card-body">
    <table class="detail-table">
      <tr><th>Nomor Surat</th><td><strong><?= htmlspecialchars($data['nomor_surat']) ?></strong></td></tr>
      <tr><th>Jenis Surat</th><td><strong><?= htmlspecialchars($data['jenis_surat']) ?></strong></td></tr>
      <tr><th>Keperluan</th><td><?= nl2br(htmlspecialchars($data['keperluan'])) ?></td></tr>
      <tr><th>Tanggal Dibutuhkan</th><td><?= date('d F Y', strtotime($data['tanggal_dibutuhkan'])) ?></td></tr>
      <tr><th>Diajukan Pada</th><td><?= date('d M Y H:i', strtotime($data['created_at'])) ?></td></tr>
      <tr><th>Status</th>
        <td>
          <?php
            $msg = [
              'pending'   => 'Pengajuan Anda masih menunggu verifikasi Administrator.',
              'disetujui' => 'Pengajuan Anda telah disetujui. Silakan ambil surat di TU.',
              'ditolak'   => 'Pengajuan Anda ditolak. Silakan lihat catatan dari Administrator.',
            ];
          ?>
          <span class="badge-pill <?= $badge[$data['status']] ?>"><?= ucfirst($data['status']) ?></span>
          <small class="d-block text-muted mt-1"><?= $msg[$data['status']] ?></small>
        </td>
      </tr>
      <?php if ($data['catatan_admin']): ?>
      <tr>
        <th>Catatan Admin</th>
        <td>
          <div class="alert alert-info mb-0" style="font-size:14px;">
            <?= nl2br(htmlspecialchars($data['catatan_admin'])) ?>
          </div>
        </td>
      </tr>
      <?php endif; ?>
    </table>
  </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
