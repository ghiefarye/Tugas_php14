<?php
/**
 * Middleware Autentikasi & Proteksi Role
 *
 * Cara pakai:
 *   - Auth::requireLogin()       : harus login (role apa pun)
 *   - Auth::requireAdmin()       : hanya admin
 *   - Auth::requireUser()        : hanya user biasa
 *   - Auth::check()              : true jika sudah login
 *   - Auth::user()               : data user yang sedang login
 *   - Auth::logout()             : hapus session
 *
 * Jika role tidak sesuai, redirect ke dashboard sesuai role aslinya
 * (mencegah user paksa masuk URL admin dan sebaliknya).
 */
class Auth {

    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function check() {
        self::start();
        return isset($_SESSION['user_id']);
    }

    public static function user() {
        self::start();
        if (!self::check()) return null;
        return [
            'id'           => $_SESSION['user_id'],
            'username'     => $_SESSION['username'] ?? null,
            'nama_lengkap' => $_SESSION['nama_lengkap'] ?? null,
            'role'         => $_SESSION['role'] ?? null,
        ];
    }

    public static function role() {
        self::start();
        return $_SESSION['role'] ?? null;
    }

    public static function requireLogin($redirect = '/login.php') {
        self::start();
        if (!self::check()) {
            $_SESSION['flash_error'] = 'Anda harus login terlebih dahulu.';
            header("Location: " . self::base($redirect));
            exit;
        }
    }

    public static function requireAdmin() {
        self::requireLogin();
        if (self::role() !== 'admin') {
            // user biasa tidak boleh masuk → tendang ke dashboard user
            $_SESSION['flash_error'] = 'Akses ditolak. Halaman ini khusus Administrator.';
            header("Location: " . self::base('/user/dashboard.php'));
            exit;
        }
    }

    public static function requireUser() {
        self::requireLogin();
        if (self::role() !== 'user') {
            // admin tidak boleh masuk → arahkan ke dashboard admin
            $_SESSION['flash_error'] = 'Akses ditolak. Halaman ini khusus User.';
            header("Location: " . self::base('/admin/dashboard.php'));
            exit;
        }
    }

    public static function login(array $userData) {
        self::start();
        session_regenerate_id(true);
        $_SESSION['user_id']      = $userData['id'];
        $_SESSION['username']     = $userData['username'];
        $_SESSION['nama_lengkap'] = $userData['nama_lengkap'];
        $_SESSION['role']         = $userData['role'];
    }

    public static function logout() {
        self::start();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p["path"], $p["domain"], $p["secure"], $p["httponly"]);
        }
        session_destroy();
    }

    /**
     * Membuat URL relatif berdasarkan folder proyek.
     * Mendukung Laragon (pretty URL atau /folder/).
     */
    public static function base($path = '/') {
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        // Jika script ada di subfolder (mis. /portal_akademik/admin/dashboard.php),
        // ambil root-nya (potong /admin atau /user).
        $scriptDir = preg_replace('#/(admin|user)$#', '', $scriptDir);
        $scriptDir = rtrim($scriptDir, '/');
        return $scriptDir . $path;
    }
}
