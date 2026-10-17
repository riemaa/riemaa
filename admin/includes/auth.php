<?php
/**
 * حماية صفحات لوحة التحكم والتحقق من الجلسة
 */

require_once __DIR__ . '/../../config.php';

if (empty($_SESSION['admin_logged_in']) || empty($_SESSION['admin_id'])) {
    if (!empty($_COOKIE['admin_auth'])) {
        $parts = explode(':', $_COOKIE['admin_auth']);
        if (count($parts) === 4) {
            [$uid, $uname, $ts, $sig] = $parts;
            $key = defined('APP_KEY') ? APP_KEY : 'afsh_secret_key_riemaa_2026_x791a8c3';
            $expected = hash_hmac('sha256', "admin:{$uid}:{$uname}:{$ts}", $key);
            if (hash_equals($expected, $sig) && (time() - (int)$ts) <= (86400 * 14)) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id']        = (int)$uid;
                $_SESSION['admin_username']  = $uname;
            }
        }
    }
}

if (empty($_SESSION['admin_logged_in']) || empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
