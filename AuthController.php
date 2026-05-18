<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';

/**
 * Controller untuk autentikasi: login, register, logout.
 */
class AuthController extends Controller {

    public function login() {
        Auth::start();
        if (Auth::check()) $this->redirectByRole();

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($username === '' || $password === '') {
                $error = 'Username dan password wajib diisi.';
            } else {
                $userModel = $this->model('User');
                $user = $userModel->authenticate($username, $password);
                if ($user) {
                    Auth::login($user);
                    $this->redirectByRole();
                } else {
                    $error = 'Username atau password salah.';
                }
            }
        }

        $flash = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);
        $this->view('auth/login', ['error' => $error, 'flash' => $flash]);
    }

    public function register() {
        Auth::start();
        if (Auth::check()) $this->redirectByRole();

        $error = null;
        $success = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username     = trim($_POST['username'] ?? '');
            $password     = $_POST['password'] ?? '';
            $confirm      = $_POST['confirm'] ?? '';
            $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
            $email        = trim($_POST['email'] ?? '');
            $nim          = trim($_POST['nim'] ?? '');
            $prodi        = trim($_POST['prodi'] ?? '');

            if (!$username || !$password || !$nama_lengkap || !$nim || !$prodi) {
                $error = 'Semua field wajib diisi (kecuali email).';
            } elseif (strlen($password) < 6) {
                $error = 'Password minimal 6 karakter.';
            } elseif ($password !== $confirm) {
                $error = 'Konfirmasi password tidak cocok.';
            } else {
                $userModel = $this->model('User');
                if ($userModel->findByUsername($username)) {
                    $error = 'Username sudah digunakan.';
                } else {
                    $ok = $userModel->create([
                        'username'     => $username,
                        'password'     => $password,
                        'nama_lengkap' => $nama_lengkap,
                        'email'        => $email,
                        'nim'          => $nim,
                        'prodi'        => $prodi,
                        'role'         => 'user',  // pendaftaran via form selalu mahasiswa
                    ]);
                    if ($ok) {
                        $success = 'Pendaftaran berhasil! Silakan login.';
                    } else {
                        $error = 'Pendaftaran gagal, coba lagi.';
                    }
                }
            }
        }

        $this->view('auth/register', ['error' => $error, 'success' => $success]);
    }

    public function logout() {
        Auth::logout();
        header("Location: " . Auth::base('/login.php'));
        exit;
    }

    private function redirectByRole() {
        $role = Auth::role();
        if ($role === 'admin') {
            header("Location: " . Auth::base('/admin/dashboard.php'));
        } else {
            header("Location: " . Auth::base('/user/dashboard.php'));
        }
        exit;
    }
}
