<?php
$pageTitle = 'Ajukan Surat Baru';
include __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../app/models/PengajuanSurat.php';
$jenisList = PengajuanSurat::jenisSuratList();
?>

<div class="page-header">
  <h4><i class="bi bi-plus-circle-fill"></i> Form Pengajuan Surat Baru</h4>
  <a href="<?= $base ?>/user/pengajuan.php" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left"></i> Kembali
  </a>
</div>

<?php if (!empty($error)): ?>
  <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
  <div class="card-body">
    <form method="post">
      <div class="row g-3">
        <div class="col-md-8">
          <label class="form-label">Jenis Surat <span class="text-danger">*</span></label>
          <select name="jenis_surat" class="form-select" required>
            <option value="">-- Pilih Jenis Surat --</option>
            <?php foreach ($jenisList as $j): ?>
              <option value="<?= htmlspecialchars($j) ?>"
                <?= (($_POST['jenis_surat'] ?? '') === $j) ? 'selected' : '' ?>>
                <?= htmlspecialchars($j) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Tanggal Dibutuhkan <span class="text-danger">*</span></label>
          <input type="date" name="tanggal_dibutuhkan" class="form-control" required
                 min="<?= date('Y-m-d') ?>"
                 value="<?= htmlspecialchars($_POST['tanggal_dibutuhkan'] ?? '') ?>">
        </div>
        <div class="col-md-12">
          <label class="form-label">Keperluan / Maksud <span class="text-danger">*</span></label>
          <textarea name="keperluan" class="form-control" rows="4" required
                    placeholder="Jelaskan keperluan pengajuan surat ini..."><?= htmlspecialchars($_POST['keperluan'] ?? '') ?></textarea>
        </div>
      </div>

      <div class="alert alert-info mt-4" style="font-size:13px;">
        <i class="bi bi-info-circle-fill"></i>
        Nomor surat akan dibuat <strong>otomatis</strong> oleh sistem setelah pengajuan disimpan.
        Status awal adalah <strong>Pending</strong> menunggu verifikasi Administrator.
      </div>

      <div class="mt-3">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-send-fill"></i> Kirim Pengajuan
        </button>
        <a href="<?= $base ?>/user/pengajuan.php" class="btn btn-outline-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
