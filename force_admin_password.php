<?php
// Force admin password (temporary) to fix "Username atau password salah".
// Access: http://localhost/portal_akademik/tools/force_admin_password.php
// After fixing, delete this file.

require_once __DIR__ . '/../config/database.php';

$ip = $_SERVER['REMOTE_ADDR'] ?? '';
if (!in_array($ip, ['127.0.0.1', '::1'], true)) {
    http_response_code(403);
    echo 'Forbidden: script must be executed from localhost.';
    exit;
}

$pdo = getDB();

$pdo->beginTransaction();
try {
    // Update password for existing admin (username='admin')
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
    $stmt->execute(['admin']);
    $user = $stmt->fetch();

    if (!$user) {
        // If admin not exists, create it with role admin
        $ins = $pdo->prepare(
            "INSERT INTO users (username, password, nama_lengkap, email, nim, prodi, role)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $ins->execute([
            'admin',
            password_hash('admin123', PASSWORD_DEFAULT),
            'Administrator Kampus',
            'admin@kampus.ac.id',
            null,
            null,
            'admin',
        ]);
        $pdo->commit();
        echo "OK: Created admin with password admin123";
        exit;
    }

    $up = $pdo->prepare("UPDATE users SET password = ? , role = 'admin' WHERE id = ?");
    $up->execute([password_hash('admin123', PASSWORD_DEFAULT), (int)$user['id']]);

    $pdo->commit();
    echo "OK: Updated admin password to admin123 and role='admin'";
} catch (Throwable $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo 'Error: ' . htmlspecialchars($e->getMessage());
}

