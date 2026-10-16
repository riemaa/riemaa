<?php
/**
 * حماية صفحات لوحة التحكم والتحقق من الجلسة
 */

require_once __DIR__ . '/../../config.php';

if (empty($_SESSION['admin_logged_in']) || empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
