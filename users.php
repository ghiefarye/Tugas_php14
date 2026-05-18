<?php $pageTitle = 'Manajemen Pengguna'; include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
  <h4><i class="bi bi-people-fill"></i> Daftar Pengguna</h4>
  <a href="<?= $base ?>/admin/users_create.php" class="btn btn-primary">
    <i class="bi bi-person-plus-fill"></i> Tambah Pengguna
  </a>
</div>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead>
          <tr><th>#</th><th>Username</th><th>Nama Lengkap</th><th>NIM / Prodi</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          <?php if (empty($items)): ?>
            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pengguna.</td></tr>
          <?php else: foreach ($items as $i => $u): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><strong><?= htmlspecialchars($u['username']) ?></strong></td>
              <td><?= htmlspecialchars($u['nama_lengkap']) ?></td>
              <td>
                <?php if ($u['nim']): ?>
                  <strong><?= htmlspecialchars($u['nim']) ?></strong>
                  <br><small class="text-muted"><?= htmlspecialchars($u['prodi'] ?? '') ?></small>
                <?php else: ?>
                  <small class="text-muted">-</small>
                <?php endif; ?>
              </td>
              <td><small><?= htmlspecialchars($u['email'] ?? '-') ?></small></td>
              <td>
                <?php if ($u['role'] === 'admin'): ?>
                  <span class="badge-pill badge-primary"><i class="bi bi-shield-fill-check"></i> Admin</span>
                <?php else: ?>
                  <span class="badge-pill badge-info"><i class="bi bi-mortarboard-fill"></i> Mahasiswa</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="<?= $base ?>/admin/users_edit.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-pencil-fill"></i>
                </a>
                <?php if ($u['id'] != $me['id']): ?>
                <a href="<?= $base ?>/admin/users_delete.php?id=<?= $u['id'] ?>"
                   class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Hapus pengguna ini?')">
                  <i class="bi bi-trash-fill"></i>
                </a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
