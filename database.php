<?php
/**
 * Konfigurasi Database (PDO)
 * Auto-create database & tabel saat aplikasi pertama kali dijalankan.
 *
 * Stack: Laragon (MySQL port 3306, user root, password kosong).
 */

// ============================================================
// KONFIGURASI - Sesuaikan jika perlu
// ============================================================
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_USER', 'root');
define('DB_PASS', '');               // Laragon default: kosong
define('DB_NAME', 'portal_akademik');
define('DB_CHARSET', 'utf8mb4');

// ============================================================
// AUTO-CREATE DATABASE
// ============================================================
function createDatabaseIfNotExists() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "`
                    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    } catch (PDOException $e) {
        die("
        <div style='font-family:sans-serif;max-width:600px;margin:80px auto;padding:30px;
                    border:1px solid #fca5a5;background:#fef2f2;border-radius:10px;color:#7f1d1d;'>
            <h2 style='margin-top:0;'>❌ Gagal Terhubung ke MySQL</h2>
            <p>Pastikan <strong>Laragon</strong> sudah berjalan dan layanan <strong>MySQL aktif</strong> pada port 3306.</p>
            <p><strong>Detail Error:</strong><br><code>" . htmlspecialchars($e->getMessage()) . "</code></p>
        </div>");
    }
}

// ============================================================
// AMBIL KONEKSI PDO KE DATABASE APLIKASI
// ============================================================
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        createDatabaseIfNotExists();
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT
                 . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
            require_once __DIR__ . '/../app/core/DbSchema.php';
            initTables($pdo);
            DbSchema::ensureUsersColumns($pdo);

        } catch (PDOException $e) {
            die("Koneksi DB gagal: " . $e->getMessage());
        }
    }
    return $pdo;
}

// ============================================================
// AUTO-CREATE TABEL & SEED DATA AWAL
// ============================================================
function initTables(PDO $pdo) {
    // ----- Tabel users -----
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            nama_lengkap VARCHAR(100) NOT NULL,
            email VARCHAR(100) DEFAULT NULL,
            nim VARCHAR(20) DEFAULT NULL,
            prodi VARCHAR(100) DEFAULT NULL,
            role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    // ----- Tabel pengajuan_surat (modul: pengajuan surat akademik mahasiswa) -----
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS pengajuan_surat (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            nomor_surat VARCHAR(50) UNIQUE NOT NULL,
            jenis_surat ENUM(
                'Surat Aktif Kuliah',
                'Surat Keterangan Lulus',
                'Surat Izin Penelitian',
                'Surat Cuti Akademik',
                'Surat Rekomendasi Beasiswa',
                'Surat Pengantar'
            ) NOT NULL,
            keperluan TEXT NOT NULL,
            tanggal_dibutuhkan DATE NOT NULL,
            status ENUM('pending','disetujui','ditolak') DEFAULT 'pending',
            catatan_admin TEXT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    // ----- Seed user default jika tabel users kosong -----
    $count = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($count === 0) {
        $stmt = $pdo->prepare("
            INSERT INTO users (username, password, nama_lengkap, email, nim, prodi, role)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        // Admin
        $stmt->execute([
            'admin',
            password_hash('admin123', PASSWORD_DEFAULT),
            'Administrator Kampus',
            'admin@kampus.ac.id',
            null,
            null,
            'admin'
        ]);
        // Mahasiswa
        $stmt->execute([
            'andi',
            password_hash('user123', PASSWORD_DEFAULT),
            'Andi Pratama',
            'andi@student.kampus.ac.id',
            '2023010001',
            'Teknik Informatika',
            'user'
        ]);
        $stmt->execute([
            'bunga',
            password_hash('user123', PASSWORD_DEFAULT),
            'Bunga Lestari',
            'bunga@student.kampus.ac.id',
            '2023010002',
            'Sistem Informasi',
            'user'
        ]);
    }
}
