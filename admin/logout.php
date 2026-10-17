<?php
/**
 * تسجيل الخروج من لوحة التحكم
 */

require_once __DIR__ . '/../config.php';

$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// مسح كوكي المصادقة المشفر وكوكي CSRF
setcookie('admin_auth', '', time() - 86400, '/');
setcookie('csrf_token', '', time() - 86400, '/');

session_destroy();

header('Location: login.php');
exit;
