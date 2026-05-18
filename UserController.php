<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';

/**
 * Controller untuk Mahasiswa (role: user).
 * Semua aksi otomatis terproteksi dengan Auth::requireUser().
 */
class UserController extends Controller {

    public function __construct() {
        Auth::requireUser();
    }

    public function dashboard() {
        $me = Auth::user();
        $pengajuanModel = $this->model('PengajuanSurat');
        $myList = $pengajuanModel->byUser($me['id']);

        $stats = [
            'total'     => count($myList),
            'pending'   => count(array_filter($myList, fn($r) => $r['status'] === 'pending')),
            'disetujui' => count(array_filter($myList, fn($r) => $r['status'] === 'disetujui')),
            'ditolak'   => count(array_filter($myList, fn($r) => $r['status'] === 'ditolak')),
        ];

        // Ambil data lengkap user untuk tampilkan NIM/prodi di banner
        $profil = $this->model('User')->findById($me['id']);

        $this->view('user/dashboard', [
            'me'     => $me,
            'profil' => $profil,
            'stats'  => $stats,
            'recent' => array_slice($myList, 0, 5),
        ]);
    }

    public function pengajuan() {
        $me = Auth::user();
        $items = $this->model('PengajuanSurat')->byUser($me['id']);
        $this->view('user/pengajuan', ['items' => $items]);
    }

    public function pengajuanCreate() {
        $me = Auth::user();
        $pengajuanModel = $this->model('PengajuanSurat');
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jenisValid = PengajuanSurat::jenisSuratList();
            $jenis = $_POST['jenis_surat'] ?? '';

            $data = [
                'user_id'            => $me['id'],
                'nomor_surat'        => $pengajuanModel->generateNomorSurat(),
                'jenis_surat'        => $jenis,
                'keperluan'          => trim($_POST['keperluan'] ?? ''),
                'tanggal_dibutuhkan' => $_POST['tanggal_dibutuhkan'] ?? null,
            ];
            if (!in_array($jenis, $jenisValid, true)) {
                $error = 'Jenis surat tidak valid.';
            } elseif (!$data['keperluan'] || !$data['tanggal_dibutuhkan']) {
                $error = 'Semua field wajib diisi.';
            } elseif (strtotime($data['tanggal_dibutuhkan']) < strtotime(date('Y-m-d'))) {
                $error = 'Tanggal dibutuhkan tidak boleh di masa lalu.';
            } else {
                $pengajuanModel->create($data);
                $_SESSION['flash_success'] = 'Pengajuan surat berhasil dikirim.';
                header("Location: " . Auth::base('/user/pengajuan.php'));
                exit;
            }
        }
        $this->view('user/pengajuan_form', ['error' => $error]);
    }

    public function pengajuanDetail($id) {
        $me = Auth::user();
        $data = $this->model('PengajuanSurat')->find($id);
        if (!$data || $data['user_id'] != $me['id']) {
            $_SESSION['flash_error'] = 'Data tidak ditemukan.';
            header("Location: " . Auth::base('/user/pengajuan.php'));
            exit;
        }
        $this->view('user/pengajuan_detail', ['data' => $data]);
    }
}
