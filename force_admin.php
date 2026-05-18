<?php
// Force admin script (temporary) to fix role issues.
// Access: http://localhost/portal_akademik/tools/force_admin.php

require_once __DIR__ . '/../config/database.php';

// Block web execution without local access (basic safety).
$ip = $_SERVER['REMOTE_ADDR'] ?? '';
if (!in_array($ip, ['127.0.0.1', '::1'], true)) {
    http_response_code(403);
    echo 'Forbidden: script must be executed from localhost.';
    exit;
}

$pdo = getDB();

$pdo->beginTransaction();
try {
    // Ensure tables exist (initTables called inside getDB())

    $stmt = $pdo->prepare("SELECT id, role FROM users WHERE username = ? LIMIT 1");
    $stmt->execute(['admin']);
    $user = $stmt->fetch();

    if ($user) {
        // Update role to admin
        $up = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $up->execute(['admin', $user['id']]);

        // Optional: keep password as-is; role fix is enough.
        $pdo->commit();
        echo "OK: Admin role updated for username 'admin'. Current role: admin";
        exit;
    }

    // If admin user doesn't exist, create it.
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
    echo "OK: Admin account created (admin/admin123) with role 'admin'.";
} catch (Throwable $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo 'Error: ' . htmlspecialchars($e->getMessage());
}

