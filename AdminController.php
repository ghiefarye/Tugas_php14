<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';

/**
 * Controller untuk Admin.
 * Semua aksi otomatis terproteksi dengan Auth::requireAdmin().
 */
class AdminController extends Controller {

    public function __construct() {
        Auth::requireAdmin();
    }

    public function dashboard() {
        $userModel      = $this->model('User');
        $pengajuanModel = $this->model('PengajuanSurat');

        $stats      = $pengajuanModel->statistik();
        $countUsers = $userModel->countByRole();
        $latest     = array_slice($pengajuanModel->all(), 0, 5);

        $this->view('admin/dashboard', [
            'stats'      => $stats,
            'countUsers' => $countUsers,
            'latest'     => $latest,
        ]);
    }

    // ---------- USERS ----------
    public function users() {
        $items = $this->model('User')->all();
        $this->view('admin/users', ['items' => $items]);
    }

    public function userCreate() {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = $this->model('User');
            $username  = trim($_POST['username'] ?? '');
            if (!$username || !$_POST['password'] || !$_POST['nama_lengkap']) {
                $error = 'Field wajib belum lengkap.';
            } elseif ($userModel->findByUsername($username)) {
                $error = 'Username sudah dipakai.';
            } else {
                $userModel->create([
                    'username'     => $username,
                    'password'     => $_POST['password'],
                    'nama_lengkap' => $_POST['nama_lengkap'],
                    'email'        => $_POST['email'] ?? null,
                    'nim'          => $_POST['nim'] ?? null,
                    'prodi'        => $_POST['prodi'] ?? null,
                    'role'         => $_POST['role'] ?? 'user',
                ]);
                $_SESSION['flash_success'] = 'User berhasil ditambahkan.';
                header("Location: " . Auth::base('/admin/users.php'));
                exit;
            }
        }
        $this->view('admin/user_form', ['data' => null, 'error' => $error]);
    }

    public function userEdit($id) {
        $userModel = $this->model('User');
        $data = $userModel->findById($id);
        if (!$data) {
            $_SESSION['flash_error'] = 'User tidak ditemukan.';
            header("Location: " . Auth::base('/admin/users.php'));
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel->update($id, [
                'nama_lengkap' => $_POST['nama_lengkap'],
                'email'        => $_POST['email'] ?? null,
                'nim'          => $_POST['nim'] ?? null,
                'prodi'        => $_POST['prodi'] ?? null,
                'role'         => $_POST['role'] ?? 'user',
            ]);
            if (!empty($_POST['password'])) {
                $userModel->resetPassword($id, $_POST['password']);
            }
            $_SESSION['flash_success'] = 'Data user diperbarui.';
            header("Location: " . Auth::base('/admin/users.php'));
            exit;
        }
        $this->view('admin/user_form', ['data' => $data, 'error' => null]);
    }

    public function userDelete($id) {
        if ((int)$id === (int)Auth::user()['id']) {
            $_SESSION['flash_error'] = 'Tidak dapat menghapus akun Anda sendiri.';
        } else {
            $this->model('User')->delete($id);
            $_SESSION['flash_success'] = 'User dihapus.';
        }
        header("Location: " . Auth::base('/admin/users.php'));
        exit;
    }

    // ---------- PENGAJUAN SURAT ----------
    public function pengajuan() {
        $items = $this->model('PengajuanSurat')->all();
        $this->view('admin/pengajuan', ['items' => $items]);
    }

    public function pengajuanDetail($id) {
        $pengajuanModel = $this->model('PengajuanSurat');
        $data = $pengajuanModel->find($id);
        if (!$data) {
            $_SESSION['flash_error'] = 'Data tidak ditemukan.';
            header("Location: " . Auth::base('/admin/pengajuan.php'));
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status  = $_POST['status']        ?? 'pending';
            $catatan = $_POST['catatan_admin'] ?? null;
            $pengajuanModel->updateStatus($id, $status, $catatan);
            $_SESSION['flash_success'] = 'Status pengajuan diperbarui.';
            header("Location: " . Auth::base('/admin/pengajuan.php'));
            exit;
        }
        $this->view('admin/pengajuan_detail', ['data' => $data]);
    }

    public function pengajuanDelete($id) {
        $this->model('PengajuanSurat')->delete($id);
        $_SESSION['flash_success'] = 'Pengajuan dihapus.';
        header("Location: " . Auth::base('/admin/pengajuan.php'));
        exit;
    }
}
