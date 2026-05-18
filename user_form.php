<?php
$pageTitle = $data ? 'Edit Pengguna' : 'Tambah Pengguna';
include __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
  <h4><i class="bi bi-person-plus-fill"></i> <?= $data ? 'Edit' : 'Tambah' ?> Pengguna</h4>
  <a href="<?= $base ?>/admin/users.php" class="btn btn-outline-secondary">
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
        <div class="col-md-6">
          <label class="form-label">Username <span class="text-danger">*</span></label>
          <input type="text" name="username" class="form-control"
                 value="<?= $data ? htmlspecialchars($data['username']) : '' ?>"
                 <?= $data ? 'readonly' : 'required' ?>>
        </div>
        <div class="col-md-6">
          <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
          <input type="text" name="nama_lengkap" class="form-control"
                 value="<?= $data ? htmlspecialchars($data['nama_lengkap']) : '' ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control"
                 value="<?= $data ? htmlspecialchars($data['email'] ?? '') : '' ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Role <span class="text-danger">*</span></label>
          <select name="role" class="form-select" id="roleSelect" onchange="toggleNimProdi()">
            <option value="user" <?= $data && $data['role']==='user' ? 'selected' : '' ?>>Mahasiswa</option>
            <option value="admin" <?= $data && $data['role']==='admin' ? 'selected' : '' ?>>Admin</option>
          </select>
        </div>
        <div class="col-md-6 nim-prodi-row">
          <label class="form-label">NIM</label>
          <input type="text" name="nim" class="form-control"
                 value="<?= $data ? htmlspecialchars($data['nim'] ?? '') : '' ?>">
        </div>
        <div class="col-md-6 nim-prodi-row">
          <label class="form-label">Program Studi</label>
          <input type="text" name="prodi" class="form-control"
                 value="<?= $data ? htmlspecialchars($data['prodi'] ?? '') : '' ?>"
                 placeholder="Contoh: Teknik Informatika">
        </div>
        <div class="col-md-6">
          <label class="form-label">
            Password <?= $data ? '<small class="text-muted">(kosongkan jika tidak diubah)</small>' : '<span class="text-danger">*</span>' ?>
          </label>
          <input type="text" name="password" class="form-control"
                 <?= $data ? '' : 'required minlength="6"' ?>>
        </div>
      </div>
      <div class="mt-4">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save-fill"></i> Simpan</button>
        <a href="<?= $base ?>/admin/users.php" class="btn btn-outline-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>

<script>
function toggleNimProdi() {
  const role = document.getElementById('roleSelect').value;
  document.querySelectorAll('.nim-prodi-row').forEach(el => {
    el.style.display = (role === 'user') ? '' : 'none';
  });
}
toggleNimProdi();
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
