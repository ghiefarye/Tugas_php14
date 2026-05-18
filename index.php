<?php
/**
 * Root index — arahkan ke halaman yang sesuai.
 */
require_once __DIR__ . '/app/core/Auth.php';
Auth::start();

if (Auth::check()) {
    if (Auth::role() === 'admin') {
        header("Location: " . Auth::base('/admin/dashboard.php'));
    } else {
        header("Location: " . Auth::base('/user/dashboard.php'));
    }
} else {
    header("Location: " . Auth::base('/login.php'));
}
exit;
