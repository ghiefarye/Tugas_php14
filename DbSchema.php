<?php
/**
 * Helper skema DB agar aplikasi tidak crash jika tabel lama belum sinkron.
 */
class DbSchema {

    public static function ensureUsersColumns(PDO $pdo): void {
        // Pastikan kolom nama_lengkap ada; jika tidak, tambahkan.
        $cols = $pdo->query("SHOW COLUMNS FROM users")->fetchAll(PDO::FETCH_ASSOC);
        $colNames = array_map(fn($c) => $c['Field'], $cols);

        if (!in_array('nama_lengkap', $colNames, true)) {
            // Kalau skema lama memakai kolom 'nama', kita buat 'nama_lengkap' dan salin data.
            $hasNama = in_array('nama', $colNames, true);
            $pdo->exec("ALTER TABLE users ADD COLUMN nama_lengkap VARCHAR(100) NOT NULL DEFAULT ''");
            if ($hasNama) {
                $pdo->exec("UPDATE users SET nama_lengkap = nama WHERE (nama_lengkap = '' OR nama_lengkap IS NULL)");
            }
        }

        // Jika kolom 'email','nim','prodi' hilang, tambahkan sebagai NULL (lebih aman untuk kompatibilitas).
        if (!in_array('email', $colNames, true)) {
            $pdo->exec("ALTER TABLE users ADD COLUMN email VARCHAR(100) DEFAULT NULL");
        }
        if (!in_array('nim', $colNames, true)) {
            $pdo->exec("ALTER TABLE users ADD COLUMN nim VARCHAR(20) DEFAULT NULL");
        }
        if (!in_array('prodi', $colNames, true)) {
            $pdo->exec("ALTER TABLE users ADD COLUMN prodi VARCHAR(100) DEFAULT NULL");
        }
    }
}

